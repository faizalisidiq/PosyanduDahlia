<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HealthPost;

class HealthPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthPost::create([
            'name' => 'Posyandu Melati 1',
            'address' => 'Jl. Mawar No. 12, Desa Sukamaju',
            'phone' => '081234567890',
        ]);
    }
}
