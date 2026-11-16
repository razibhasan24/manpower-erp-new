<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentDocument;
use App\Models\PassportHolder;
use App\Models\PassportHolderDocument;
use App\Models\Voucher;
use App\Services\SubLedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Agent / Passport Holder self-service portal.
 *
 * The party is always resolved from the authenticated user — the URL never
 * accepts an agent / passport-holder id. This makes cross-party access
 * impossible even before the policy check runs.
 */
class MyPortalController extends Controller
{
    /**
     * @return array{type: string, id: int, name: string, model: Agent|PassportHolder}
     */
    protected function party(): array
    {
        $user = auth()->user();

        if ($user === null) {
            abort(403);
        }

        if ($user->hasRole('Agent') && $user->agent instanceof Agent) {
            return [
                'type' => 'agent',
                'id' => (int) $user->agent->id,
                'name' => (string) $user->agent->name,
                'model' => $user->agent,
            ];
        }

        if ($user->hasRole('Passport Holder') && $user->passportHolder instanceof PassportHolder) {
            return [
                'type' => 'passport_holder',
                'id' => (int) $user->passportHolder->id,
                'name' => (string) $user->passportHolder->full_name,
                'model' => $user->passportHolder,
            ];
        }

        abort(403, 'No agent or passport-holder profile is linked to this account.');
    }

    public function ledger(Request $request, SubLedgerService $ledgers): View
    {
        $party = $this->party();
        $this->authorize('viewSubLedger', [Voucher::class, $party['type'], $party['id']]);

        $filters = $request->only(['from', 'to']);
        $ledger = $ledgers->ledger($party['type'], $party['id'], $filters);

        return view('my.ledger', [
            'party' => $party,
            'ledger' => $ledger,
            'filters' => $filters,
        ]);
    }

    public function payments(Request $request, SubLedgerService $ledgers): View
    {
        $party = $this->party();
        $this->authorize('viewSubLedger', [Voucher::class, $party['type'], $party['id']]);

        $filters = $request->only(['from', 'to']);
        $ledger = $ledgers->ledger($party['type'], $party['id'], $filters);

        $payments = array_values(array_filter(
            $ledger['lines'],
            static fn (array $line): bool => (float) $line['credit'] > 0
        ));

        return view('my.payments', [
            'party' => $party,
            'payments' => $payments,
            'total_paid' => round(array_sum(array_column($payments, 'credit')), 2),
            'closing_balance' => $ledger['closing_balance'],
            'filters' => $filters,
        ]);
    }

    public function documents(): View
    {
        $party = $this->party();
        $this->authorize('view', $party['model']);

        $documents = match ($party['type']) {
            'agent' => AgentDocument::query()
                ->where('agent_id', $party['id'])
                ->latest()
                ->get(),
            default => PassportHolderDocument::query()
                ->where('passport_holder_id', $party['id'])
                ->latest()
                ->get(),
        };

        return view('my.documents', [
            'party' => $party,
            'documents' => $documents,
        ]);
    }

    public function downloadDocument(string $document): StreamedResponse
    {
        $party = $this->party();
        $this->authorize('view', $party['model']);

        $documentId = (int) $document;

        $record = match ($party['type']) {
            'agent' => AgentDocument::query()
                ->where('agent_id', $party['id'])
                ->findOrFail($documentId),
            default => PassportHolderDocument::query()
                ->where('passport_holder_id', $party['id'])
                ->findOrFail($documentId),
        };

        $disk = Storage::disk('private');

        abort_unless($disk->exists($record->file_path), 404, 'The requested file is no longer available.');

        return $disk->download(
            $record->file_path,
            $record->original_name ?? basename($record->file_path)
        );
    }
}
