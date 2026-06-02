<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IlpScreening;
use App\Models\Mother;
use App\Models\Children;
use App\Models\Staff;
use Faker\Factory as Faker;

class IlpScreeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $staffs = Staff::all();

        if ($staffs->isEmpty()) {
            return;
        }

        // Seed for Mothers
        $mothers = Mother::all();
        foreach ($mothers as $mother) {
            // Create 1-3 screenings for each mother
            for ($i = 0; $i < rand(1, 3); $i++) {
                $checkupDate = $faker->dateTimeBetween('-1 year', 'now');
                
                $systolic = $faker->numberBetween(100, 160);
                $diastolic = $faker->numberBetween(60, 100);
                
                $results = [
                    'weight' => $faker->numberBetween(45, 90),
                    'height' => $faker->numberBetween(145, 170),
                    'waist_circumference' => $faker->numberBetween(60, 100),
                    'blood_pressure' => "{$systolic}/{$diastolic}",
                    'blood_sugar' => $faker->numberBetween(70, 200),
                    'uric_acid' => $faker->randomFloat(1, 2.5, 8.0),
                    'cholesterol' => $faker->numberBetween(120, 300),
                    'eyes' => $faker->randomElement(['Normal', 'Rabun Jauh', 'Rabun Dekat', 'Buta Warna']),
                    'ears' => $faker->randomElement(['Normal', 'Kurang Dengar', 'Tuli']),
                    'smoking_status' => $faker->randomElement(['Tidak Merokok', 'Perokok Pasif', 'Perokok Aktif']),
                    'emotional_disorders' => $faker->boolean(10) ? 'Ada' : 'Tidak Ada', // 10% chance
                    'note' => $faker->sentence(),
                ];

                IlpScreening::create([
                    'subjectable_type' => Mother::class,
                    'subjectable_id' => $mother->id,
                    'staff_id' => $staffs->random()->id,
                    'checkup_date' => $checkupDate,
                    'results' => $results,
                ]);
            }
        }

        // Seed for Children
        $childrens = Children::all();
        foreach ($childrens as $child) {
            if (rand(0, 100) < 30) {
                $checkupDate = $faker->dateTimeBetween('-1 year', 'now');
                
                $results = [
                    'weight' => $faker->randomFloat(1, 10, 20),
                    'height' => $faker->numberBetween(80, 100),
                    'waist_circumference' => $faker->numberBetween(40, 60),
                    'eyes' => $faker->randomElement(['Normal', 'Mata Merah']),
                    'ears' => $faker->randomElement(['Normal', 'Ada Serumen']),
                    'note' => $faker->sentence(),
                ];

                IlpScreening::create([
                    'subjectable_type' => Children::class,
                    'subjectable_id' => $child->id,
                    'staff_id' => $staffs->random()->id,
                    'checkup_date' => $checkupDate,
                    'results' => $results,
                ]);
            }
        }
    }
}
