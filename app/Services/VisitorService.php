<?php

namespace App\Services;

use App\Models\Visitor;
use App\Models\VisitorPass;
use Illuminate\Support\Facades\DB;

/**
 * Visitor management (Phase 27). Registration, entry/exit, pass number,
 * daily/monthly reports. Pass numbers are sequential per day: VP-YYYYMMDD-###.
 */
class VisitorService
{
    public function register(array $data): Visitor
    {
        return DB::transaction(function () use ($data) {
            $data['entry_time'] = $data['entry_time'] ?? now();
            $data['pass_number'] = $data['pass_number'] ?? $this->nextPassNumber();

            $visitor = Visitor::create($data);

            VisitorPass::create([
                'visitor_id' => $visitor->id,
                'pass_number' => $visitor->pass_number,
                'issued_at' => $visitor->entry_time,
                'status' => 'issued',
            ]);

            return $visitor;
        });
    }

    public function checkOut(Visitor $visitor, ?string $time = null): Visitor
    {
        return DB::transaction(function () use ($visitor, $time) {
            $visitor->update(['exit_time' => $time ?? now(), 'status' => 'left']);

            $visitor->passes()->where('status', 'issued')->update([
                'returned_at' => $time ?? now(),
                'status' => 'returned',
            ]);

            return $visitor->fresh();
        });
    }

    /** Visitors still inside (no exit time). */
    public function currentlyInside()
    {
        return Visitor::whereNull('exit_time')->orderBy('entry_time')->get();
    }

    public function dailyReport(string $date)
    {
        return Visitor::whereDate('entry_time', $date)->orderBy('entry_time')->get();
    }

    public function monthlyReport(string $month)
    {
        return Visitor::where('entry_time', 'like', $month . '%')
            ->selectRaw('DATE(entry_time) as day, COUNT(*) as total')
            ->groupBy('day')->orderBy('day')->get();
    }

    protected function nextPassNumber(): string
    {
        $day = now()->format('Ymd');
        $count = Visitor::whereDate('entry_time', now()->toDateString())->count();

        return sprintf('VP-%s-%03d', $day, $count + 1);
    }
}
