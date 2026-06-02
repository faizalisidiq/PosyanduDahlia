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
        Schema::create('ilp_screenings', function (Blueprint $table) {
            $table->id();
            $table->morphs('subjectable');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->date('checkup_date');
            $table->json('results')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ilp_screenings');
    }
};
