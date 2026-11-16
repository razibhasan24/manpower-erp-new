<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_country', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->timestamps();
            $table->index('agent_id');
            $table->index('country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_country');
    }
};
