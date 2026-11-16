<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'leave'])->default('present');
            $table->enum('source', ['manual', 'biometric'])->default('manual');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('employee_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
