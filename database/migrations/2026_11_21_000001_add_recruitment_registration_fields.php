<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('passport_holders', function (Blueprint $table) {
            $table->string('referer')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('village_road')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('district')->nullable();
        });
        Schema::table('recruitments', function (Blueprint $table) {
            $table->unsignedTinyInteger('local_experience_years')->default(0);
            $table->string('education_type')->nullable();
            $table->string('education_degree')->nullable();
            $table->string('education_school')->nullable();
            $table->string('education_grade')->nullable();
            $table->string('education_year')->nullable();
            $table->json('language_proficiency')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('recruitments', function (Blueprint $table) {
            $table->dropColumn(['local_experience_years', 'education_type', 'education_degree', 'education_school', 'education_grade', 'education_year', 'language_proficiency']);
        });
        Schema::table('passport_holders', function (Blueprint $table) {
            $table->dropColumn(['referer', 'religion', 'marital_status', 'height', 'weight', 'village_road', 'post_office', 'police_station', 'district']);
        });
    }
};
