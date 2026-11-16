<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PassportHolder;
use App\Models\Recruitment;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Header notification feed.
 *
 * There is no notifications table in this phase. Instead of inventing one,
 * the bell shows real, permission-scoped counts derived from live records.
 * Every query starts from the model's visibleTo() scope, so an Agent or
 * Passport Holder only ever sees their own numbers. Results are cached for
 * 60 seconds per user and fail closed if the schema is not ready yet.
 */
class HeaderNotificationService
{
    /** @return array<int, array{icon: string, label: string, url: string, tone: string}> */
    public function for(User $user): array
    {
        try {
            return Cache::remember('header.notifications.'.$user->id, 60, function () use ($user) {
                return $this->build($user);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return $this->build($user);
        }
    }

    /** @return array<int, array{icon: string, label: string, url: string, tone: string}> */
    protected function build(User $user): array
    {
        $items = [];

        try {
            if (Schema::hasTable('recruitments') && $user->can('recruitment.view')) {
                $pending = Recruitment::query()
                    ->visibleTo($user)
                    ->where('current_status', 'pending')
                    ->count();

                if ($pending > 0) {
                    $items[] = [
                        'icon' => 'clock',
                        'label' => trans_choice(':count registration is waiting for review|:count registrations are waiting for review', $pending, ['count' => $pending]),
                        'url' => route('recruitments.index', ['current_status' => 'pending']),
                        'tone' => 'warning',
                    ];
                }
            }

            if (Schema::hasTable('passport_holders') && $user->can('passport-holder.view')) {
                $expiring = PassportHolder::query()
                    ->visibleTo($user)
                    ->whereNotNull('passport_expiry_date')
                    ->whereBetween('passport_expiry_date', [now()->toDateString(), now()->addDays(60)->toDateString()])
                    ->count();

                if ($expiring > 0) {
                    $items[] = [
                        'icon' => 'book-user',
                        'label' => trans_choice(':count passport expires within 60 days|:count passports expire within 60 days', $expiring, ['count' => $expiring]),
                        'url' => route('dashboard'),
                        'tone' => 'danger',
                    ];
                }
            }

            if (Schema::hasTable('vouchers') && $user->can('voucher.view')) {
                $drafts = Voucher::query()
                    ->visibleTo($user)
                    ->where('status', 'draft')
                    ->count();

                if ($drafts > 0) {
                    $items[] = [
                        'icon' => 'receipt',
                        'label' => trans_choice(':count voucher is still in draft|:count vouchers are still in draft', $drafts, ['count' => $drafts]),
                        'url' => route('accounting.vouchers.index', ['status' => 'draft']),
                        'tone' => 'info',
                    ];
                }
            }
        } catch (\Throwable $exception) {
            report($exception);

            return [];
        }

        return array_slice($items, 0, 5);
    }
}
