<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->foreignId('medical_centre_id')->nullable()->constrained('medical_centres')->nullOnDelete();
            $table->date('medical_date')->nullable();
            $table->enum('result', ['pending', 'fit', 'unfit', 'expired', 'cancelled'])->default('pending');
            $table->string('report_path')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('reassessment_of')->nullable()->constrained('medicals')->nullOnDelete();
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
        Schema::dropIfExists('medicals');
    }
};
