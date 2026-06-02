<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mother;
use Faker\Factory as Faker;

class MotherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create 20 sample mothers
        for ($i = 0; $i < 20; $i++) {
            Mother::create([
                'name' => $faker->name('female'),
                'husband_name' => $faker->name('male'),
                'identity_number' => $faker->nik(),
                'phone_number' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'social_security_number' => $faker->numerify('################'), // 16 digits BPJS
                'health_facility' => $faker->company(),
                'birth_place' => $faker->city(),
                'birth_date' => $faker->dateTimeBetween('-40 years', '-20 years'),
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O', '-']),
                'height' => $faker->randomFloat(2, 140, 190), // Height in cm
                'weight' => $faker->randomFloat(2, 40, 120), // Weight in kg
            ]);
        }
    }
}
