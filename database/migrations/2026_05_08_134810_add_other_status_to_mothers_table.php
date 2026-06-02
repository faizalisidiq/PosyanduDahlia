<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        // For MySQL, we need to modify the enum
        DB::statement("ALTER TABLE mothers MODIFY COLUMN status ENUM('hamil', 'menyusui', 'lainnya') DEFAULT 'hamil'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Moving back might fail if there's data with 'lainnya' status
        DB::statement("ALTER TABLE mothers MODIFY COLUMN status ENUM('hamil', 'menyusui') DEFAULT 'hamil'");
    }
};
