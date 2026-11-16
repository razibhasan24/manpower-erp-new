<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number')->unique();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->enum('status', ['open', 'submitted', 'closed', 'cancelled'])->default('open');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_lots');
    }
};
