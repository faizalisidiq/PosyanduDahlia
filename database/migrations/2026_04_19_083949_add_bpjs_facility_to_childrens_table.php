<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('childrens', function (Blueprint $table) {
            $table->enum('bpjs_facility', ['Klinik', 'Puskesmas', 'RS'])->nullable()->after('birth_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('childrens', function (Blueprint $table) {
            $table->dropColumn('bpjs_facility');
        });
    }
};
