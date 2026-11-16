<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Append-only audit trail (Phase 01 sections 6.5 and 13).
 *
 * Attached to every business model by AppServiceProvider. Controllers
 * never write audit rows directly. Passport/NID values and passwords are
 * never stored in an audit row.
 */
class AuditObserver
{
    /** Values that must never appear in an audit log. */
    public const HIDDEN = ['password', 'remember_token', 'passport_number', 'nid'];

    public function created(Model $model): void
    {
        $this->write($model, 'created', [], $this->visibleAttributes($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();
        $original = [];
        foreach ($changes as $key => $value) {
            $original[$key] = $model->getOriginal($key);
        }

        $this->write($model, 'updated', $this->visibleAttributes($original), $this->visibleAttributes($changes));
    }

    public function deleted(Model $model): void
    {
        $this->write($model, 'deleted', $this->visibleAttributes($model->getAttributes()), []);
    }

    private function write(Model $model, string $action, array $old, array $new): void
    {
        if (! auth()->check() && ! app()->runningInConsole()) {
            return;
        }

        try {
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
                'old_values' => $old === [] ? null : $old,
                'new_values' => $new === [] ? null : $new,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Throwable) {
            // Auditing must never break the business operation itself.
        }
    }

    private function visibleAttributes(array $attributes): array
    {
        return collect($attributes)
            ->except(self::HIDDEN)
            ->map(fn ($v) => is_scalar($v) || $v === null ? $v : json_encode($v))
            ->all();
    }
}
