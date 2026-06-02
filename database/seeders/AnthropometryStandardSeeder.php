<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\AnthropometryStandard;

class AnthropometryStandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for faster truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AnthropometryStandard::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $files = [
            'male' => database_path('seeders/data/boys_0-5_years_zscore.xlsx'),
            'female' => database_path('seeders/data/girls_0-5_years_zscore.xlsx'),
        ];

        foreach ($files as $gender => $filePath) {
            if (!file_exists($filePath)) {
                $this->command->error("File not found: {$filePath}");
                continue;
            }

            $this->command->info("Seeding data for {$gender} from {$filePath}...");

            try {
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Assuming first row is header
                // Month, L, M, S, SD3neg, SD2neg, SD1neg, SD0, SD1, SD2, SD3
                // Index: 0, 1, 2, 3, 4, 5, 6, 7 (SD0 is Median), 8, 9, 10
                
                $dataToInsert = [];
                $headerSkipped = false;

                foreach ($rows as $index => $row) {
                    // Skip header if it looks like a header (e.g. contains 'Month')
                    if (!$headerSkipped && (strtolower($row[0]) === 'month' || !is_numeric($row[0]))) {
                        $headerSkipped = true;
                        continue;
                    }

                    // Ensure row has enough columns
                    if (count($row) < 11) continue;

                    $dataToInsert[] = [
                        'gender' => $gender,
                        'age_in_months' => (int)$row[0],
                        'l_value' => (float)$row[1],
                        'm_value' => (float)$row[2],
                        's_value' => (float)$row[3],
                        'sd_3_neg' => (float)$row[4],
                        'sd_2_neg' => (float)$row[5],
                        'sd_1_neg' => (float)$row[6],
                        'median' => (float)$row[7], // SD0
                        'sd_1_pos' => (float)$row[8],
                        'sd_2_pos' => (float)$row[9],
                        'sd_3_pos' => (float)$row[10],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Insert in chunks to avoid memory issues
                    if (count($dataToInsert) >= 200) {
                        AnthropometryStandard::insert($dataToInsert);
                        $dataToInsert = [];
                    }
                }

                // Insert remaining
                if (!empty($dataToInsert)) {
                    AnthropometryStandard::insert($dataToInsert);
                }

            } catch (\Exception $e) {
                $this->command->error("Error processing {$filePath}: " . $e->getMessage());
            }
        }
    }
}
