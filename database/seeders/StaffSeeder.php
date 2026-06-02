<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Staff;
use App\Models\User;
use App\Models\HealthPost;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get the Health Post created by HealthPostSeeder (or create one if missing)
        $healthPost = HealthPost::first();
        if (!$healthPost) {
             $healthPost = HealthPost::create([
                'name' => 'Posyandu Melati 1',
                'address' => 'Jl. Mawar No. 12, Desa Sukamaju',
                'phone' => '081234567890',
            ]);
        }

        // 0. Ensure Admin user from UserSeeder has Staff role
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            Staff::updateOrCreate(
                ['user_id' => $adminUser->id],
                [
                    'health_post_id' => $healthPost->id,
                    'role' => 'ketua-kader',
                    'phone' => '08123456789',
                    'address' => 'Alamat Admin',
                    'status' => 'active',
                ]
            );
        }

        // 1. Create Ketua Kader
        $ketuaUser = User::updateOrCreate(
            ['email' => 'ketua@posyandu.id'],
            [
                'name' => $faker->name('female'),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Staff::updateOrCreate(
            ['user_id' => $ketuaUser->id],
            [
                'health_post_id' => $healthPost->id,
                'role' => 'ketua-kader',
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'status' => 'active',
            ]
        );

        // 2. Create 5 Anggota Kader
        for ($i = 1; $i <= 5; $i++) {
            $user = User::updateOrCreate(
                ['email' => "kader{$i}@posyandu.id"],
                [
                    'name' => $faker->name('female'),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'health_post_id' => $healthPost->id,
                    'role' => 'anggota-kader',
                    'phone' => $faker->phoneNumber(),
                    'address' => $faker->address(),
                    'status' => 'active',
                ]
            );
        }
    }
}
