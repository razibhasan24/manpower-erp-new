<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->integer('year');
            $table->integer('sequence');
            $table->timestamps();
            $table->index('branch_id');
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_numbers');
    }
};
