<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->enum('voucher_type', ['payment', 'receipt', 'journal', 'contra'])->default('journal');
            $table->date('voucher_date');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->enum('status', ['draft', 'posted', 'reversed', 'cancelled'])->default('draft');
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('reversed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reversal_voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->text('narration')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('branch_id');
            $table->index('voucher_type');
            $table->index('voucher_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
