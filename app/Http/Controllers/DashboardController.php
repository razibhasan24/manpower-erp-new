<?php

namespace App\Http\Controllers;

use App\Models\JournalEntryLine;
use App\Models\PassportHolder;
use App\Models\Recruitment;
use App\Models\Voucher;
use App\Services\WorkflowService;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Phase 07 — dashboard. Aggregated queries only; no own tables.
 * Every query starts from visibleTo() so Agents and Passport Holders
 * only ever see their own numbers.
 */
class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $recruitments = Recruitment::query()->visibleTo($user);

        $byStage = (clone $recruitments)
            ->select('current_stage', DB::raw('count(*) as total'))
            ->groupBy('current_stage')
            ->pluck('total', 'current_stage')
            ->all();

        $byBranch = (clone $recruitments)
            ->join('branches', 'recruitments.branch_id', '=', 'branches.id')
            ->select('branches.name', DB::raw('count(*) as total'))
            ->groupBy('branches.name')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'name')
            ->all();

        $byCountry = (clone $recruitments)
            ->join('countries', 'recruitments.country_id', '=', 'countries.id')
            ->select('countries.name', DB::raw('count(*) as total'))
            ->groupBy('countries.name')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'name')
            ->all();

        $stats = [
            'total_recruitments' => (clone $recruitments)->count(),
            'in_progress' => (clone $recruitments)->whereNotIn('current_status', ['cancelled', 'rejected'])->count(),
            'completed' => (clone $recruitments)->where('current_status', 'completed')->count(),
            'passport_holders' => PassportHolder::query()->visibleTo($user)->count(),
            'posted_vouchers_this_month' => Voucher::query()->visibleTo($user)
                ->where('status', 'posted')
                ->whereMonth('voucher_date', now()->month)
                ->whereYear('voucher_date', now()->year)
                ->count(),
            'total_collected_this_month' => Voucher::query()->visibleTo($user)
                ->where('status', 'posted')
                ->where('voucher_type', 'receipt')
                ->whereMonth('voucher_date', now()->month)
                ->whereYear('voucher_date', now()->year)
                ->sum('total_credit'),
        ];

        $recent = (clone $recruitments)
            ->with(['passportHolder', 'agent', 'country', 'company'])
            ->latest()
            ->limit(10)
            ->get();

        $expiringPassports = PassportHolder::query()->visibleTo($user)
            ->whereNotNull('passport_expiry_date')
            ->whereBetween('passport_expiry_date', [now()->toDateString(), now()->addDays(60)->toDateString()])
            ->orderBy('passport_expiry_date')
            ->limit(10)
            ->get();

        return view('dashboard.index', [
            'stats' => $stats,
            'byStage' => $byStage,
            'byBranch' => $byBranch,
            'byCountry' => $byCountry,
            'recent' => $recent,
            'expiringPassports' => $expiringPassports,
            'stages' => WorkflowService::stages(),
        ]);
    }
    /**
     * Recruitment dashboard — read-only pipeline overview. Every query is
     * scoped through visibleTo(), so Agent / Passport Holder users only see
     * their own rows.
     */
    public function recruitment(): View
    {
        $user = auth()->user();
        $base = Recruitment::query()->visibleTo($user);

        $stats = [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->where('current_status', 'pending')->count(),
            'in_progress' => (clone $base)->whereNotIn('current_status', ['completed', 'cancelled', 'rejected'])->count(),
            'completed' => (clone $base)->where('current_status', 'completed')->count(),
            'cancelled' => (clone $base)->where('current_status', 'cancelled')->count(),
        ];

        $stageCounts = [];
        foreach (WorkflowService::STAGES as $stage) {
            $stageCounts[$stage] = (clone $base)->where('current_stage', $stage)->count();
        }

        $maxStageCount = max(1, ...array_values($stageCounts));

        $byCountry = (clone $base)
            ->join('countries', 'recruitments.country_id', '=', 'countries.id')
            ->select('countries.name', DB::raw('count(*) as total'))
            ->groupBy('countries.name')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'name')
            ->all();

        $recent = (clone $base)
            ->with(['passportHolder', 'agent', 'country', 'company', 'position'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.recruitment', [
            'stats' => $stats,
            'stageCounts' => $stageCounts,
            'maxStageCount' => $maxStageCount,
            'byCountry' => $byCountry,
            'recent' => $recent,
        ]);
    }

    /**
     * Financial dashboard — posted-voucher totals and account balances.
     * Uses the same visibleTo() scope as the voucher register.
     */
    public function financial(): View
    {
        $user = auth()->user();
        $vouchers = Voucher::query()->visibleTo($user);

        $postedThisMonth = (clone $vouchers)
            ->where('status', 'posted')
            ->whereMonth('voucher_date', now()->month)
            ->whereYear('voucher_date', now()->year);

        $stats = [
            'posted_this_month' => (clone $postedThisMonth)->count(),
            'receipts_this_month' => (float) (clone $postedThisMonth)->where('voucher_type', 'receipt')->sum('total_credit'),
            'payments_this_month' => (float) (clone $postedThisMonth)->where('voucher_type', 'payment')->sum('total_debit'),
            'drafts' => (clone $vouchers)->where('status', 'draft')->count(),
            'total_debit' => (float) (clone $vouchers)->where('status', 'posted')->sum('total_debit'),
            'total_credit' => (float) (clone $vouchers)->where('status', 'posted')->sum('total_credit'),
        ];

        $balances = JournalEntryLine::query()
            ->visibleTo($user)
            ->whereHas('journalEntry.voucher', fn ($query) => $query->where('status', 'posted'))
            ->selectRaw('account_id, SUM(debit) as debit, SUM(credit) as credit')
            ->groupBy('account_id')
            ->with('account:id,code,name')
            ->get()
            ->map(fn (JournalEntryLine $line) => [
                'account' => $line->account?->code.' — '.$line->account?->name,
                'debit' => (float) $line->debit,
                'credit' => (float) $line->credit,
                'balance' => round((float) $line->debit - (float) $line->credit, 2),
            ])
            ->sortByDesc(fn (array $row) => abs($row['balance']))
            ->take(8)
            ->values();

        $recent = (clone $vouchers)
            ->with('branch')
            ->orderByDesc('voucher_date')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('dashboard.financial', [
            'stats' => $stats,
            'balances' => $balances,
            'recent' => $recent,
        ]);
    }

    /**
     * Same aggregated numbers as the web dashboard, as JSON for /api/v1.
     */
    public function apiStats(): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $recruitments = Recruitment::query()->visibleTo($user);

        $byStage = (clone $recruitments)
            ->select('current_stage', DB::raw('count(*) as total'))
            ->groupBy('current_stage')
            ->pluck('total', 'current_stage')
            ->all();

        return response()->json([
            'data' => [
                'total_recruitments' => (clone $recruitments)->count(),
                'by_stage' => $byStage,
                'passport_holders' => PassportHolder::query()->visibleTo($user)->count(),
            ],
        ]);
    }

}
