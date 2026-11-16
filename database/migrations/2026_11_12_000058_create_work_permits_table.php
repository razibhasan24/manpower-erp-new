<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_permits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_id')->constrained('visas')->cascadeOnDelete();
            $table->string('permit_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('visa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_permits');
    }
};
