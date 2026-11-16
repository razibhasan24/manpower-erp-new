<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passport_holder_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passport_holder_id')->constrained('passport_holders')->cascadeOnDelete();
            $table->string('type');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('passport_holder_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_holder_documents');
    }
};
