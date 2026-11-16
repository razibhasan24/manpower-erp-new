<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_arrivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->date('arrival_date')->nullable();
            $table->boolean('airport_pickup')->default(false);
            $table->string('employer_contact')->nullable();
            $table->text('accommodation')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'arrived', 'settled', 'issue_reported', 'completed'])->default('pending');
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
        Schema::dropIfExists('post_arrivals');
    }
};
