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
        Schema::create('childbirth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->cascadeOnDelete();
            $table->foreignId('children_id')->constrained('childrens')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->integer('child_order');
            $table->string('delivery_method');
            $table->date('delivery_date');
            $table->string('delivery_location');
            $table->string('baby_condition');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childbirth_records');
    }
};
