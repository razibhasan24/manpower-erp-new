<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryAdministrationController extends Controller
{
    public function userPermissions(Request $request): View
    {
        abort_unless($request->user()->can('user.manage'), 403);

        $users = User::visibleTo($request->user())->with(['branch', 'countries', 'roles'])
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('name', 'like', $search)->orWhere('email', 'like', $search);
            }))
            ->when($request->filled('country_id'), fn ($q) => $q->whereHas('countries', fn ($countries) => $countries->where('countries.id', $request->integer('country_id'))))
            ->orderBy('name')->paginate(15)->withQueryString();

        $countries = Country::visibleTo($request->user())->orderBy('name')->get(['id', 'name']);

        return view('country-administration.user-permissions', compact('users', 'countries'));
    }

    public function workflowSettings(Request $request): View
    {
        abort_unless($request->user()->can('core.manage'), 403);

        $countries = Country::visibleTo($request->user())
            ->withCount('recruitments')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%')
                ->orWhere('code', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('country-administration.workflow-settings', [
            'countries' => $countries,
            'stages' => WorkflowService::STAGES,
            'stageStatuses' => WorkflowService::STAGE_STATUSES,
        ]);
    }
}
