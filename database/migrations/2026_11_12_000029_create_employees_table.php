<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('employee_code')->unique();
            $table->date('joining_date')->nullable();
            $table->decimal('salary', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('branch_id');
            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
