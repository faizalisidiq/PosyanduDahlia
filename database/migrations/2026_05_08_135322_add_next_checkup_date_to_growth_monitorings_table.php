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
        Schema::table('growth_monitorings', function (Blueprint $table) {
            // Kita gunakan if untuk mencegah error jika kolom ternyata sudah ada
            if (!Schema::hasColumn('growth_monitorings', 'next_checkup_date')) {
                $table->date('next_checkup_date')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('growth_monitorings', function (Blueprint $table) {
            if (Schema::hasColumn('growth_monitorings', 'next_checkup_date')) {
                $table->dropColumn('next_checkup_date');
            }
        });
    }
};