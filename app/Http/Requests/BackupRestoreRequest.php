<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Restore is the most destructive action in the system. It requires:
 *   - the `backup.restore` permission (checked by the controller/policy),
 *   - a checked confirmation box, AND
 *   - the typed confirmation phrase "RESTORE".
 * The service also takes a safety backup of the current state first.
 */
class BackupRestoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // No `backup.restore` permission exists in the seeder; restore
        // is authorised by `backup.manage` (see BackupPolicy).
        return $this->user()->can('backup.manage');
    }

    public function rules(): array
    {
        return [
            'confirmed' => ['required', 'accepted'],
            'confirmation_phrase' => ['required', 'string', 'in:RESTORE'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirmation_phrase.in' => 'Type RESTORE exactly to confirm the restore.',
        ];
    }
}
