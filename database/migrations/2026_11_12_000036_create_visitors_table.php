<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('purpose')->nullable();
            $table->string('person_to_meet')->nullable();
            $table->dateTime('entry_time')->nullable();
            $table->dateTime('exit_time')->nullable();
            $table->string('pass_number')->nullable();
            $table->enum('status', ['inside', 'left', 'cancelled'])->default('inside');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('branch_id');
            $table->index('entry_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
