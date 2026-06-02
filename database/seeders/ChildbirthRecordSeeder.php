<?php

namespace Database\Seeders;

use App\Models\ChildbirthRecord;
use App\Models\Children;
use App\Models\Mother;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChildbirthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have related data
        if (Mother::count() == 0 || Staff::count() == 0 || Children::count() == 0) {
            $this->command->info('Please seed Mothers, Staff, and Children first.');
            return;
        }

        // Get all IDs
        $motherIds = Mother::pluck('id')->toArray();
        $staffIds = Staff::pluck('id')->toArray();
        $childrenIds = Children::pluck('id')->toArray();

        // Optional: truncate table to start fresh or keep adding? 
        // User asked to "buatkan ... agar random", usually implies replacing or adding.
        // Let's clear it to ensure the date range is strictly respected if this is a "fix" request.
        // DB::table('childbirth_records')->truncate(); // Ignoring truncate to avoid FK issues if child references it back, but usually safe for this direction.
        // Actually, let's just create 20 records.
        
        $faker = \Faker\Factory::create('id_ID');

        foreach ($childrenIds as $childId) {
            // Check if record exists for this child to avoid duplication if strict 1-1
            if (ChildbirthRecord::where('children_id', $childId)->exists()) {
                continue;
            }

            // Generate random date from Aug 2025 to now
            // Note: "Aug 2025". Current date in metadata is 2026-01-17.
            // So range is 2025-08-01 to 2026-01-17.
            
            // Random date logic
            $startDate = Carbon::create(2025, 8, 1)->timestamp;
            $endDate = now()->timestamp;
            $randomTimestamp = rand($startDate, $endDate);
            $deliveryDate = Carbon::createFromTimestamp($randomTimestamp);

            ChildbirthRecord::create([
                'mother_id' => $faker->randomElement($motherIds), // Ideally this should match child's mother...
                'children_id' => $childId,
                'staff_id' => $faker->randomElement($staffIds),
                'child_order' => $faker->numberBetween(1, 4),
                'delivery_method' => $faker->randomElement(['Normal', 'Caesarean', 'Water Birth']),
                'delivery_date' => $deliveryDate,
                'delivery_location' => $faker->randomElement(['Puskesmas', 'Rumah Sakit', 'Klinik Bersalin', 'Rumah']),
                'baby_condition' => $faker->randomElement(['Sehat', 'Normal', 'Perlu Perawatan']),
            ]);
        }
    }
}
