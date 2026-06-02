<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Children;
use App\Models\GrowthMonitoring;
use App\Models\Staff;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GrowthMonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $childrens = Children::all();
        $staffs = Staff::all();
        
        if ($staffs->isEmpty()) {
            return;
        }

        foreach ($childrens as $child) {
            $birthDate = Carbon::parse($child->birth_date);
            // Start checking from 1 month after birth
            $currentDate = $birthDate->copy()->addMonth();
            $now = Carbon::now();

            // Initial stats from birth (approximation if not set, otherwise use birth data)
            $currentWeight = floatval($child->birth_weight ?? 3.0);
            $currentHeight = floatval($child->birth_height ?? 50.0);
            
            while ($currentDate->lte($now)) {
                $ageInMonths = $birthDate->diffInMonths($currentDate);
                
                // Realistic growth logic (approximate)
                // Weight gain: fast in first year, slows down
                if ($ageInMonths <= 12) {
                    $weightGain = $faker->randomFloat(2, 0.4, 0.8);
                    $heightGain = $faker->randomFloat(2, 1.5, 2.5);
                } else {
                    $weightGain = $faker->randomFloat(2, 0.1, 0.3);
                    $heightGain = $faker->randomFloat(2, 0.5, 1.0);
                }

                $currentWeight += $weightGain;
                $currentHeight += $heightGain;

                // Random variations for arm and head
                // Arm: ~11cm increases slowly to ~16cm by age 5
                $armCirc = 10 + ($ageInMonths * 0.1) + $faker->randomFloat(1, -0.5, 0.5);
                
                // Head: ~34cm increases to ~50cm by age 5
                $headCirc = 34 + ($ageInMonths * 0.3) + $faker->randomFloat(1, -0.5, 0.5);

                // Z-Score simulation (mostly normal -2 to +2)
                $zScore = $faker->randomFloat(2, -2.5, 2.5);
                
                $status = 'Gizi Baik';
                if ($zScore < -3) $status = 'Gizi Buruk';
                elseif ($zScore < -2) $status = 'Gizi Kurang';
                elseif ($zScore > 2) $status = 'Gizi Lebih';

                GrowthMonitoring::create([
                    'child_id' => $child->id,
                    'staff_id' => $staffs->random()->id,
                    'checkup_date' => $currentDate->copy(),
                    'weight' => round($currentWeight, 2),
                    'height' => round($currentHeight, 1),
                    'arm_circumference' => round($armCirc, 1),
                    'head_circumference' => round($headCirc, 1),
                    'z_score' => $zScore,
                    'status' => $status,
                    'status' => $status,
                    'note' => $faker->optional(0.2)->sentence(), // 20% chance of note
                    'created_at' => $currentDate->copy(),
                    'updated_at' => $currentDate->copy(),
                ]);

                $currentDate->addMonth();
            }
        }
    }
}
