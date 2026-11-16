<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->foreignId('visa_lot_id')->nullable()->constrained('visa_lots')->nullOnDelete();
            $table->enum('mofa_status', ['pending', 'submitted', 'approved', 'rejected', 'returned'])->default('pending');
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'returned', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('recruitment_id');
            $table->index('status');
            $table->index('visa_lot_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
