<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->string('pass_number')->unique();
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->enum('status', ['issued', 'returned', 'lost'])->default('issued');
            $table->timestamps();
            $table->index('visitor_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_passes');
    }
};
