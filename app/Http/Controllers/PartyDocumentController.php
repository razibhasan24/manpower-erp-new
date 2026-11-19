<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentDocument;
use App\Models\PassportHolder;
use App\Models\PassportHolderDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PartyDocumentController extends Controller
{
    public function agentDocuments(Request $request): View
    {
        $this->authorize('viewAny', Agent::class);
        $records = AgentDocument::visibleTo($request->user())->with('agent')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('original_name', 'like', $search)->orWhere('type', 'like', $search)
                    ->orWhereHas('agent', fn ($agent) => $agent->where('name', 'like', $search));
            }))
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('party-documents.agents', compact('records'));
    }

    public function passportHolderDocuments(Request $request): View
    {
        $this->authorize('viewAny', PassportHolder::class);
        $records = PassportHolderDocument::visibleTo($request->user())->with('passportHolder')
            ->when($request->filled('search'), fn ($q) => $q->where(function ($filter) use ($request) {
                $search = '%'.$request->string('search').'%';
                $filter->where('original_name', 'like', $search)->orWhere('type', 'like', $search)
                    ->orWhereHas('passportHolder', fn ($holder) => $holder->where('full_name', 'like', $search)
                        ->orWhere('passport_number', 'like', $search));
            }))
            ->orderByDesc('id')->paginate(15)->withQueryString();

        return view('party-documents.passport-holders', compact('records'));
    }

    public function downloadAgentDocument(Request $request, Agent $agent, AgentDocument $document): StreamedResponse
    {
        $this->authorize('view', $agent);
        abort_unless((int) $document->agent_id === (int) $agent->id, 404);
        $disk = Storage::disk('private');
        abort_unless($disk->exists($document->file_path), 404, 'The requested file is no longer available.');

        return $disk->download($document->file_path, $document->original_name ?? basename($document->file_path));
    }
}
