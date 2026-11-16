<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\Request;

/**
 * Branch web controller (Phases 08-12). Thin: validate via Form Request,
 * authorise via Policy, delegate to BranchService, then respond.
 */
class BranchController extends Controller
{
    public function __construct(protected BranchService $service)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Branch::class);

        $branches = $this->service->list($request->only(['search', 'status', 'branch_id', 'country_id', 'company_id', 'agent_id', 'current_status']), $request->user());

        return view('branches.index', ['items' => $branches]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Branch::class);

        $users = \App\Models\User::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('branches.create', ['users' => $users]);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->service->create($request->validated(), $request->user());

        return redirect()->route('branches.show', $branch)
            ->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);

        return view('branches.show', ['branch' => $branch]);
    }

    public function edit(Request $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $users = \App\Models\User::query()->visibleTo($request->user())->orderBy('name')->get();

        return view('branches.edit', ['branch' => $branch, 'users' => $users]);
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $this->service->update($branch, $request->validated(), $request->user());

        return redirect()->route('branches.show', $branch)
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);

        $this->service->delete($branch, request()->user());

        return redirect()->route('branches.index')
            ->with('success', 'Branch removed successfully.');
    }
}
