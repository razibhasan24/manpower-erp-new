<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruitment_id')->constrained('recruitments')->cascadeOnDelete();
            $table->foreignId('airline_id')->nullable()->constrained('airlines')->nullOnDelete();
            $table->string('flight_number')->nullable();
            $table->foreignId('travel_agency_id')->nullable()->constrained('travel_agencies')->nullOnDelete();
            $table->dateTime('departure_date')->nullable();
            $table->string('departure_airport')->nullable();
            $table->string('arrival_airport')->nullable();
            $table->string('ticket_number')->nullable();
            $table->string('ticket_path')->nullable();
            $table->string('noc_path')->nullable();
            $table->enum('status', ['pending', 'ticket_issued', 'cancelled', 'rescheduled'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('recruitment_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
