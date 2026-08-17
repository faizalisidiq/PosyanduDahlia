<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->string('social_security_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->string('social_security_number')->nullable(false)->change();
        });
    }
};