<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delegations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('decided_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('recruitment_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegations');
    }
};
