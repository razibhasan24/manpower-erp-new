<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_centre_id')->nullable()->constrained('training_centres')->nullOnDelete();
            $table->string('title');
            $table->integer('duration_days')->nullable();
            $table->decimal('fee', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('status');
            $table->index('training_centre_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_courses');
    }
};
