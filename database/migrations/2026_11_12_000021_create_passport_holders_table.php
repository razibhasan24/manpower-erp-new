<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passport_holders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->string('full_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->string('nationality')->default('Bangladeshi');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('nid')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed', 'cancelled'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('country_id');
            $table->index('branch_id');
            $table->index('agent_id');
            $table->index('passport_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_holders');
    }
};
