<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arrival_supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_arrival_id')->constrained('post_arrivals')->cascadeOnDelete();
            $table->string('type');
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->dateTime('resolved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('post_arrival_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arrival_supports');
    }
};
