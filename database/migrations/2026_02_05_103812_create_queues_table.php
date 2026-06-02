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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('childrens')->cascadeOnDelete();
            $table->string('queue_number');
            $table->date('date');
            $table->enum('status', ['waiting', 'called', 'processing', 'completed', 'skipped'])->default('waiting');
            $table->enum('type', ['growth_monitoring', 'immunization', 'general'])->default('growth_monitoring');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
