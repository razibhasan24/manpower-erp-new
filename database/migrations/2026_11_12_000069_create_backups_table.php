<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->enum('type', ['db', 'files', 'full'])->default('full');
            $table->integer('size')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'verified'])->default('pending');
            $table->string('path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('type');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
