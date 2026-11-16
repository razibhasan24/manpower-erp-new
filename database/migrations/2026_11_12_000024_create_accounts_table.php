<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('account_type_id')->constrained('account_types');
            $table->enum('normal_balance', ['debit', 'credit'])->default('debit');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('account_type_id');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
