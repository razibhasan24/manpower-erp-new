<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_stage_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->string('stage');
            $table->string('status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('changed_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('recruitment_id');
            $table->index('stage');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_stage_histories');
    }
};
