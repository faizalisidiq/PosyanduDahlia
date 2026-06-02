<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Children;
use App\Models\Mother;
use Faker\Factory as Faker;

class ChildrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $mothers = Mother::all();

        foreach ($mothers as $mother) {
            // Create 1-3 children for each mother randomly
            $numberOfChildren = rand(1, 3);
            
            for ($i = 0; $i < $numberOfChildren; $i++) {
                $birthDate = $faker->dateTimeBetween('-5 years', '-1 month'); // Children up to 5 years old
                
                Children::create([
                    'mother_id' => $mother->id,
                    'identity_number' => $faker->nik(),
                    'name' => $faker->name($faker->randomElement(['male', 'female'])),
                    'birth_place' => $faker->city(),
                    'birth_date' => $birthDate,
                    'gender' => $faker->randomElement(['male', 'female']),
                    'birth_weight' => $faker->randomFloat(2, 2.5, 4.5), // 2.5kg - 4.5kg
                    'birth_height' => $faker->numberBetween(45, 55), // 45cm - 55cm
                    'created_at' => $faker->dateTimeBetween('-24 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
