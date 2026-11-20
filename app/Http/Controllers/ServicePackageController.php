<?php

namespace App\Http\Controllers;

use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServicePackageController extends Controller
{
    private const LABELS = ['student' => 'Student Packages', 'hajj_umrah' => 'Hajj & Umrah Packages'];

    public function index(Request $request, string $type): View
    {
        $this->validateType($type);
        $permission = $this->permissionPrefix($type);
        abort_unless($request->user()->can($permission.'.view'), 403);

        $query = ServicePackage::query()->where('type', $type);
        // Agent accounts can browse currently offered packages, but cannot
        // see inactive offers or use package management actions.
        if ($request->user()->hasRole('Agent')) {
            $query->where('is_active', true);
        }
        $query->when($request->filled('search'), function ($q) use ($request) {
            $term = '%'.trim((string) $request->input('search')).'%';
            $q->where(fn ($inner) => $inner->where('code', 'like', $term)->orWhere('name', 'like', $term));
        });

        return view('service-packages.index', [
            'packages' => $query->orderBy('name')->paginate(15)->withQueryString(),
            'type' => $type,
            'title' => __(self::LABELS[$type]),
            'permissionPrefix' => $permission,
        ]);
    }

    public function create(string $type): View
    {
        $this->validateType($type);
        $this->authorizePermission($type, 'create');

        return view('service-packages.form', ['package' => new ServicePackage(['type' => $type, 'currency' => 'BDT', 'is_active' => true]), 'type' => $type, 'title' => __(self::LABELS[$type])]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $this->validateType($type);
        $this->authorizePermission($type, 'create');
        $data = $this->validated($request, $type);
        ServicePackage::create($data + ['type' => $type]);

        return redirect()->route('service-packages.index', $type)->with('success', __('Package added successfully.'));
    }

    public function edit(string $type, ServicePackage $package): View
    {
        $this->validateType($type);
        $this->ensurePackageType($package, $type);
        $this->authorizePermission($type, 'edit');

        return view('service-packages.form', ['package' => $package, 'type' => $type, 'title' => __(self::LABELS[$type])]);
    }

    public function update(Request $request, string $type, ServicePackage $package): RedirectResponse
    {
        $this->validateType($type);
        $this->ensurePackageType($package, $type);
        $this->authorizePermission($type, 'edit');
        $package->update($this->validated($request, $type, $package));

        return redirect()->route('service-packages.index', $type)->with('success', __('Package updated successfully.'));
    }

    public function destroy(string $type, ServicePackage $package): RedirectResponse
    {
        $this->validateType($type);
        $this->ensurePackageType($package, $type);
        $this->authorizePermission($type, 'delete');
        $package->delete();

        return redirect()->route('service-packages.index', $type)->with('success', __('Package deleted successfully.'));
    }

    private function validated(Request $request, string $type, ?ServicePackage $package = null): array
    {
        $request->merge([
            'code' => strtoupper(trim((string) $request->input('code'))),
            'currency' => strtoupper(trim((string) $request->input('currency'))),
        ]);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('service_packages', 'code')->where('type', $type)->ignore($package?->id)],
            'name' => ['required', 'string', 'max:255'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:3650'],
            'currency' => ['required', 'string', 'size:3', 'alpha'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'description' => ['nullable', 'string', 'max:5000'],
            'inclusions' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['required', 'boolean'],
        ]);

        return $data;
    }

    private function validateType(string $type): void
    {
        abort_unless(in_array($type, ServicePackage::TYPES, true), 404);
    }

    private function permissionPrefix(string $type): string
    {
        return $type === 'student' ? 'student-package' : 'hajj-umrah-package';
    }

    private function authorizePermission(string $type, string $action): void
    {
        abort_unless(auth()->user()?->can($this->permissionPrefix($type).'.'.$action), 403);
    }

    private function ensurePackageType(ServicePackage $package, string $type): void
    {
        abort_unless($package->type === $type, 404);
    }
}
