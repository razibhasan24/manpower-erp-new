<?php

namespace App\Http\Controllers;

use App\Models\PassportReturn;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PassportReceivedController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', PassportReturn::class);

        $records = PassportReturn::visibleTo($request->user())
            ->with('recruitment.passportHolder')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->whereHas('recruitment', function ($recruitments) use ($request) {
                $search = '%'.$request->string('search').'%';
                $recruitments->where('tracking_number', 'like', $search)
                    ->orWhereHas('passportHolder', fn ($holders) => $holders->where('full_name', 'like', $search)
                        ->orWhere('passport_number', 'like', $search));
            }))
            ->orderByDesc('return_date')->orderByDesc('id')
            ->paginate(15)->withQueryString();

        return view('passport-received.index', compact('records'));
    }
}
