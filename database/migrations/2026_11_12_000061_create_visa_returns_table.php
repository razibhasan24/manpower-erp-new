<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_id')->constrained('visas')->cascadeOnDelete();
            $table->date('return_date')->nullable();
            $table->text('return_reason')->nullable();
            $table->enum('return_type', ['handover', 'courier', 'agent_collection', 'other'])->default('handover');
            $table->string('responsible_person')->nullable();
            $table->string('received_by')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'returned', 'cancelled'])->default('pending');
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
        Schema::dropIfExists('visa_returns');
    }
};
