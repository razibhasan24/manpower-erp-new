<?php

namespace App\Models;

use App\Models\Concerns\HasDataIsolation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Employee document (MERGE FIX 2026-11-13, coordinator).
 *
 * Group D's EmployeeDocumentController used this model, but no model
 * or migration existed for it, so every employee-document action
 * would fatal. Follows the PassportHolderDocument pattern: private
 * storage, authorised download only (see the controller), soft deletes.
 */
class EmployeeDocument extends Model
{
    use HasFactory, HasDataIsolation, SoftDeletes;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Data-isolation scope (Phase 01 section 8). An employee's
     * documents are visible only through the employee's own scope.
     */
    public function scopeVisibleTo($query, User $user)
    {
        if ($this->isolationSuperAdmin($user)) {
            return $query;
        }

        return $query->whereHas('employee', function ($q) use ($user) {
            $q->visibleTo($user);
        });
    }
}
