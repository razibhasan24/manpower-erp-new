<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('country_id')->constrained('countries');
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->string('currency', 10)->default('BDT');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('company_id');
            $table->index('country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
