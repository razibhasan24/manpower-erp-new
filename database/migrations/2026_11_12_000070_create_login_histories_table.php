<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('login_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->enum('status', ['success', 'failed', 'locked'])->default('success');
            $table->string('failed_reason')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
            $table->index('login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
