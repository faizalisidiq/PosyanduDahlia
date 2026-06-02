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
        Schema::create('elderlies', function (Blueprint $table) {
            $table->id();
            $table->string('identity_number')->unique();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('health_facility')->nullable();
            $table->string('blood_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elderlies');
    }
};
