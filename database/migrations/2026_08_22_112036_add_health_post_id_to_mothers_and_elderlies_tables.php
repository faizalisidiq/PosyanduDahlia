<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\HealthPost;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->foreignId('health_post_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('elderlies', function (Blueprint $table) {
            $table->foreignId('health_post_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        // Backfill: data lama dianggap milik Posyandu pertama yang sudah ada (Posyandu Dahlia)
        $firstHealthPostId = HealthPost::first()?->id;
        if ($firstHealthPostId) {
            DB::table('mothers')->whereNull('health_post_id')->update(['health_post_id' => $firstHealthPostId]);
            DB::table('elderlies')->whereNull('health_post_id')->update(['health_post_id' => $firstHealthPostId]);
        }
    }

    public function down(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('health_post_id');
        });
        Schema::table('elderlies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('health_post_id');
        });
    }
};