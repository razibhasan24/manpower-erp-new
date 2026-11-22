<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('recruitments', function (Blueprint $table) {
            $table->string('service_type', 24)->default('overseas')->after('tracking_number')->index();
            $table->foreignId('service_package_id')->nullable()->after('service_type')->constrained('service_packages')->nullOnDelete();
            $table->decimal('quoted_amount', 15, 2)->nullable()->after('service_package_id');
            $table->string('quoted_currency', 8)->default('BDT')->after('quoted_amount');
            $table->foreignId('university_id')->nullable()->after('quoted_currency')->constrained('universities')->nullOnDelete();
        });

        Schema::table('flights', function (Blueprint $table) {
            $table->string('hotel_name')->nullable()->after('arrival_airport');
            $table->date('hotel_check_in')->nullable()->after('hotel_name');
            $table->date('hotel_check_out')->nullable()->after('hotel_check_in');
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn(['hotel_name', 'hotel_check_in', 'hotel_check_out']);
        });
        Schema::table('recruitments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('university_id');
            $table->dropConstrainedForeignId('service_package_id');
            $table->dropColumn(['service_type', 'quoted_amount', 'quoted_currency']);
        });
        Schema::dropIfExists('universities');
    }
};
