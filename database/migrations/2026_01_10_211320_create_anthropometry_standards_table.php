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
        Schema::create('anthropometry_standards', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['male', 'female']);
            $table->integer('age_in_months');
            $table->float('l_value');
            $table->float('m_value');
            $table->float('s_value');
            $table->float('sd_3_neg', 8, 3);
            $table->float('sd_2_neg', 8, 3);
            $table->float('sd_1_neg', 8, 3);
            $table->float('median', 8, 3);
            $table->float('sd_1_pos', 8, 3);
            $table->float('sd_2_pos', 8, 3);
            $table->float('sd_3_pos', 8, 3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anthropometry_standards');
    }
};
