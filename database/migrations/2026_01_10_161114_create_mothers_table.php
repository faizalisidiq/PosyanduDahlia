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
        Schema::create('mothers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('husband_name')->nullable();
            $table->string('identity_number');
            $table->string('phone_number');
            $table->string('address');
            $table->string('social_security_number');
            $table->string('health_facility')->nullable();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('blood_type');
            $table->string('height');
            $table->string('weight');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mothers');
    }
};
