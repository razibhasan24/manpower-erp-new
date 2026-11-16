<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('selection_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selection_id')->constrained('selections')->cascadeOnDelete();
            $table->foreignId('grading_criterion_id')->constrained('grading_criteria')->cascadeOnDelete();
            $table->decimal('score', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('selection_id');
            $table->index('grading_criterion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('selection_grades');
    }
};
