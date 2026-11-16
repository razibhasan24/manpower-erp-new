<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->foreignId('training_centre_id')->nullable()->constrained('training_centres')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('training_courses')->nullOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('attendance_percentage', 15, 2)->nullable();
            $table->enum('result', ['pending', 'passed', 'failed', 'cancelled'])->default('pending');
            $table->string('certificate_path')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('recruitment_id');
            $table->index('result');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
