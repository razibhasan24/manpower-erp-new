<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('month');
            $table->decimal('gross', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('bonus', 15, 2)->default(0);
            $table->decimal('overtime', 15, 2)->default(0);
            $table->decimal('net', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('employee_id');
            $table->index('month');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
