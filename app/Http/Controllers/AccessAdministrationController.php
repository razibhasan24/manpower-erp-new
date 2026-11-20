<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Branch;
use App\Models\Country;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessAdministrationController extends Controller
{
    public function auditLogs(Request $request): View
    {
        abort_unless($request->user()->can('audit-log.view'), 403);
        $request->validate(['from' => ['nullable', 'date'], 'to' => ['nullable', 'date', 'after_or_equal:from']]);
        $records = AuditLog::visibleTo($request->user())->with('user')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('action', 'like', $search)->orWhere('auditable_type', 'like', $search)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $search)->orWhere('email', 'like', $search));
            }))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date('to')))
            ->latest('created_at')->paginate(25)->withQueryString();

        return view('access-administration.audit-logs', compact('records'));
    }

    public function loginHistory(Request $request): View
    {
        abort_unless($request->user()->can('user.view'), 403);
        $request->validate(['from' => ['nullable', 'date'], 'to' => ['nullable', 'date', 'after_or_equal:from']]);
        $visibleUserIds = User::visibleTo($request->user())->select('id');
        $records = LoginHistory::query()->whereIn('user_id', $visibleUserIds)->with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('ip_address', 'like', $search)->orWhereHas('user', fn ($u) => $u->where('name', 'like', $search)->orWhere('email', 'like', $search));
            }))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('login_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('login_at', '<=', $request->date('to')))
            ->latest('login_at')->paginate(25)->withQueryString();

        return view('access-administration.login-history', compact('records'));
    }

    public function userActivity(Request $request): View
    {
        abort_unless($request->user()->can('user.view'), 403);
        $records = AuditLog::visibleTo($request->user())->with('user')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('action', 'like', $search)->orWhere('auditable_type', 'like', $search)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $search));
            }))
            ->latest('created_at')->paginate(25)->withQueryString();

        return view('access-administration.user-activity', compact('records'));
    }

    public function branchUserPermissions(Request $request): View
    {
        abort_unless($request->user()->can('user.manage'), 403);
        $users = User::visibleTo($request->user())->with(['branch', 'branches', 'roles'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('name', 'like', $search)->orWhere('email', 'like', $search);
            }))
            ->when($request->filled('branch_id'), fn ($q) => $q->where(function ($filter) use ($request) {
                $filter->where('branch_id', $request->integer('branch_id'))
                    ->orWhereHas('branches', fn ($branches) => $branches->where('branches.id', $request->integer('branch_id')));
            }))
            ->orderBy('name')->paginate(20)->withQueryString();
        $branches = Branch::visibleTo($request->user())->orderBy('name')->get(['id', 'name']);

        return view('access-administration.branch-user-permissions', compact('users', 'branches'));
    }

    public function roles(Request $request): View
    {
        abort_unless($request->user()->can('role.view'), 403);
        $roles = Role::query()->with('permissions')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('access-administration.roles', compact('roles'));
    }

    public function permissions(Request $request): View
    {
        abort_unless($request->user()->can('role.view'), 403);
        $permissions = Permission::query()->with('roles')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')->paginate(30)->withQueryString();

        return view('access-administration.permissions', compact('permissions'));
    }

    public function users(Request $request): View
    {
        abort_unless($request->user()->can('user.view'), 403);
        $users = User::visibleTo($request->user())->with(['branch', 'branches', 'countries', 'roles'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('name', 'like', $search)->orWhere('email', 'like', $search);
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('access-administration.users', compact('users'));
    }

    public function editUser(Request $request, User $user): View
    {
        $this->authorize('update', $user);
        abort_unless($request->user()->can('role.manage'), 403);
        [$roles, $permissions, $branches, $countries] = $this->userAssignmentOptions($request->user());
        abort_if($user->roles->contains('name', 'Super Admin'), 404);

        return view('access-administration.create-user', compact('user', 'roles', 'permissions', 'branches', 'countries'));
    }

    public function updateUser(Request $request, User $user)
    {
        $this->authorize('update', $user);
        abort_unless($request->user()->can('role.manage'), 403);
        abort_if($user->roles()->where('name', 'Super Admin')->exists(), 404);
        [$roles, $permissions, $branches, $countries] = $this->userAssignmentOptions($request->user());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', Rule::in($roles->pluck('id')->all())],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', Rule::in($permissions->pluck('id')->all())],
            'branch_id' => ['nullable', 'integer', Rule::in($branches->pluck('id')->all())],
            'branch_ids' => ['nullable', 'array'], 'branch_ids.*' => ['integer', Rule::in($branches->pluck('id')->all())],
            'country_ids' => ['nullable', 'array'], 'country_ids.*' => ['integer', Rule::in($countries->pluck('id')->all())],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        DB::transaction(function () use ($user, $validated, $roles, $permissions) {
            $data = collect($validated)->only(['name', 'email', 'phone', 'status', 'branch_id'])->all();
            if (! empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
                $data['must_change_password'] = true;
            }
            $user->update($data);
            $user->syncRoles($roles->firstWhere('id', (int) $validated['role_id']));
            $user->syncPermissions($permissions->whereIn('id', $validated['permission_ids'] ?? []));
            $branchIds = collect($validated['branch_ids'] ?? [])->map(fn ($id) => (int) $id);
            if (! empty($validated['branch_id'])) $branchIds->push((int) $validated['branch_id']);
            $user->branches()->sync($branchIds->unique()->values()->all());
            $user->countries()->sync($validated['country_ids'] ?? []);
        });

        return redirect()->route('users.index')->with('success', __('User updated successfully.'));
    }

    public function editRole(Request $request, Role $role): View
    {
        abort_unless($request->user()->can('role.manage'), 403);
        abort_if($role->name === 'Super Admin' || $role->guard_name !== 'web', 404);
        $permissions = Permission::where('guard_name', 'web')
            ->whereIn('name', $request->user()->getAllPermissions()->pluck('name'))
            ->orderBy('name')->get();

        return view('access-administration.edit-role', compact('role', 'permissions'));
    }

    public function updateRole(Request $request, Role $role)
    {
        abort_unless($request->user()->can('role.manage'), 403);
        abort_if($role->name === 'Super Admin' || $role->guard_name !== 'web', 404);
        $permissions = Permission::where('guard_name', 'web')
            ->whereIn('name', $request->user()->getAllPermissions()->pluck('name'))
            ->get();
        $validated = $request->validate([
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', Rule::in($permissions->pluck('id')->all())],
        ]);
        $role->syncPermissions($permissions->whereIn('id', $validated['permission_ids'] ?? []));

        return redirect()->route('roles.index')->with('success', __('Role permissions updated successfully.'));
    }

    public function createUser(Request $request): View
    {
        $this->authorize('create', User::class);
        abort_unless($request->user()->can('role.manage'), 403);

        [$roles, $permissions, $branches, $countries] = $this->userAssignmentOptions($request->user());

        return view('access-administration.create-user', compact('roles', 'permissions', 'branches', 'countries'));
    }

    public function storeUser(Request $request)
    {
        $this->authorize('create', User::class);
        abort_unless($request->user()->can('role.manage'), 403);

        [$roles, $permissions, $branches, $countries] = $this->userAssignmentOptions($request->user());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', Rule::in($roles->pluck('id')->all())],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['integer', Rule::in($permissions->pluck('id')->all())],
            'branch_id' => ['nullable', 'integer', Rule::in($branches->pluck('id')->all())],
            'branch_ids' => ['nullable', 'array'],
            'branch_ids.*' => ['integer', Rule::in($branches->pluck('id')->all())],
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', Rule::in($countries->pluck('id')->all())],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $user = DB::transaction(function () use ($validated, $roles, $permissions) {
            $created = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
                'branch_id' => $validated['branch_id'] ?? null,
                'must_change_password' => true,
            ]);
            $created->syncRoles($roles->firstWhere('id', (int) $validated['role_id']));
            $created->syncPermissions($permissions->whereIn('id', $validated['permission_ids'] ?? []));
            $branchIds = collect($validated['branch_ids'] ?? [])->map(fn ($id) => (int) $id);
            if (! empty($validated['branch_id'])) {
                $branchIds->push((int) $validated['branch_id']);
            }
            $created->branches()->sync($branchIds->unique()->values()->all());
            $created->countries()->sync($validated['country_ids'] ?? []);

            return $created;
        });

        return redirect()->route('users.index')->with('success', __('User created successfully.'));
    }

    private function userAssignmentOptions(User $actor): array
    {
        $permissionNames = $actor->getAllPermissions()->pluck('name')->all();
        $permissions = \Spatie\Permission\Models\Permission::query()
            ->where('guard_name', 'web')->whereIn('name', $permissionNames)->orderBy('name')->get();
        $allowedPermissionNames = $permissions->pluck('name')->all();
        $roles = Role::query()->with('permissions')->where('guard_name', 'web')->orderBy('name')->get()
            ->reject(fn (Role $role) => $role->name === 'Super Admin')
            ->filter(fn (Role $role) => $role->permissions->every(fn ($permission) => in_array($permission->name, $allowedPermissionNames, true)))
            ->values();
        $branches = Branch::visibleTo($actor)->orderBy('name')->get(['id', 'name']);
        $countries = Country::visibleTo($actor)->orderBy('name')->get(['id', 'name']);

        return [$roles, $permissions, $branches, $countries];
    }
}
