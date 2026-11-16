<?php

namespace App\Policies;

use App\Models\JournalEntry;
use App\Models\User;

/** Journal entries are read-only: view only, never create/update/delete. */
class JournalEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('voucher.view');
    }

    public function view(User $user, JournalEntry $entry): bool
    {
        if (! ($user->hasRole('Super Admin') || $user->can('voucher.view'))) {
            return false;
        }

        return $user->branch_id === null || (int) $user->branch_id === (int) $entry->branch_id;
    }

    public function create(User $user): bool { return false; }
    public function update(User $user, JournalEntry $entry): bool { return false; }
    public function delete(User $user, JournalEntry $entry): bool { return false; }
}
