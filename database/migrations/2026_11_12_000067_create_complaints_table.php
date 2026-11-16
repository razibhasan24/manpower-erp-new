<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_arrival_id')->nullable()->constrained('post_arrivals')->cascadeOnDelete();
            $table->foreignId('recruitment_id')->nullable()->constrained('recruitments')->nullOnDelete();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('raised_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('post_arrival_id');
            $table->index('recruitment_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
