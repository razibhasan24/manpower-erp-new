<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mofa_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_id')->constrained('visas')->cascadeOnDelete();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'returned'])->default('pending');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('visa_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mofa_processes');
    }
};
