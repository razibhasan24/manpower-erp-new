<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->foreignId('payment_mode_id')->nullable()->after('branch_id')->constrained('payment_modes')->nullOnDelete();
            $table->string('payee')->nullable()->after('narration');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_mode_id');
            $table->dropColumn('payee');
        });
    }
};
