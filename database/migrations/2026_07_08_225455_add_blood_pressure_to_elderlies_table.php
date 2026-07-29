<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up(): void
    {
        Schema::table('elderlies', function (Blueprint $table) {
            $table->unsignedSmallInteger('systolic_pressure')->nullable()->after('temperature');
            $table->unsignedSmallInteger('diastolic_pressure')->nullable()->after('systolic_pressure');
            $table->unsignedSmallInteger('pulse')->nullable()->after('diastolic_pressure');
        });
    }

    public function down(): void
    {
        Schema::table('elderlies', function (Blueprint $table) {
            $table->dropColumn(['systolic_pressure', 'diastolic_pressure', 'pulse']);
        });
    }
};
