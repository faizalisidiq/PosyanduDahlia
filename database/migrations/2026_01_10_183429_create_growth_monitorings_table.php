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
        Schema::create('growth_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('childrens')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('checkup_date');
            $table->float('weight');
            $table->float('height');
            $table->float('arm_circumference')->nullable();
            $table->float('head_circumference')->nullable();
            $table->float('z_score');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_monitorings');
    }
};
