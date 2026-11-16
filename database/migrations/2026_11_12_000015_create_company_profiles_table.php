<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('software_name')->default('Manpower Recruitment & Overseas Employment ERP');
            $table->string('software_short_name')->default('Manpower ERP');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('login_background_path')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('primary_color', 20)->default('#0d6efd');
            $table->string('secondary_color', 20)->default('#6c757d');
            $table->string('currency', 10)->default('BDT');
            $table->string('language', 10)->default('en');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('date_format')->default('d-m-Y');
            $table->string('invoice_prefix')->default('INV');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
