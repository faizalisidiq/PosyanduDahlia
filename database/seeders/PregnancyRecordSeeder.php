<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PregnancyRecord;
use App\Models\Mother;
use App\Models\Staff;
use Faker\Factory as Faker;

class PregnancyRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $mothers = Mother::all();
        $staffs = Staff::all();

        if ($mothers->count() === 0 || $staffs->count() === 0) {
            return;
        }

        foreach ($mothers as $mother) {
            // Create 0-4 pregnancy records (visits) for each mother
            $numberOfVisits = rand(0, 4);

            for ($i = 0; $i < $numberOfVisits; $i++) {
                $visitDate = $faker->dateTimeBetween('-9 months', 'now');
                
                PregnancyRecord::create([
                    'mother_id' => $mother->id,
                    'staff_id' => $staffs->random()->id,
                    'visit_date' => $visitDate,
                    'pregnancy_order' => rand(1, 4),
                    'gestational_age' => rand(4, 38) . ' Minggu',
                    'weight' => rand(50, 85),
                    'arm_circumference' => rand(210, 300) / 10,
                    'blood_pressure' => rand(110, 130) . '/' . rand(70, 90),
                ]);
            }
        }
    }
}
