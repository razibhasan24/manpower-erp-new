<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();

            // Recruitment Identification
            $table->string('tracking_number')->unique();

            // Basic Relationships
            $table->foreignId('passport_holder_id')
                ->constrained('passport_holders');

            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->nullOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('position_id')
                ->nullable()
                ->constrained('positions')
                ->nullOnDelete();

            // Salary and Financial Responsibility
            $table->decimal('expected_salary', 15, 2)->nullable();

            $table->string('financial_responsible_type')->nullable();
            $table->unsignedBigInteger('financial_responsible_id')->nullable();

            // Recruitment Workflow
            $table->string('current_stage')->default('registration');
            $table->string('current_status')->default('pending');

            $table->text('remarks')->nullable();

            // Financial Information
            $table->decimal('salary', 15, 2)->default(0);
            $table->decimal('company_service_charge', 15, 2)->default(0);
            $table->decimal('agent_charge', 15, 2)->default(0);

            $table->decimal('medical_cost', 15, 2)->default(0);
            $table->decimal('training_cost', 15, 2)->default(0);
            $table->decimal('visa_cost', 15, 2)->default(0);
            $table->decimal('other_cost', 15, 2)->default(0);

            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('advance_amount', 15, 2)->default(0);
            $table->decimal('remaining_due', 15, 2)->default(0);

            // Audit Information
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Database Indexes
            $table->index('country_id');
            $table->index('branch_id');
            $table->index('agent_id');
            $table->index('passport_holder_id');
            $table->index('current_stage');
            $table->index('current_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};