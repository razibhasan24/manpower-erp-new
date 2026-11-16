<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// MERGE FIX 2026-11-13 (coordinator): Group D's EmployeeDocumentController
// used an employee_documents table that no migration created. This migration
// (000074, after Group A's 000001–000073) creates it, matching the fields
// the controller writes: employee_id, type, file_path, original_name,
// uploaded_by. Soft deletes, like the other document tables.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('type', 100);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('employee_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
