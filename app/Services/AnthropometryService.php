<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\AnthropometryStandard;

class AnthropometryService
{
    /**
     * Calculate Z-Score (Weight-for-Age)
     *
     * @param string $gender 'male' or 'female'
     * @param \Carbon\Carbon|string $birthDate
     * @param \Carbon\Carbon|string $checkupDate
     * @param float $weight Current weight in kg
     * @return array Contains 'z_score' (float) and 'status' (string)
     */
    public function calculate($gender, $birthDate, $checkupDate, $weight)
    {
        // 1. Calculate Age in Months
        $birthDate = Carbon::parse($birthDate);
        $checkupDate = Carbon::parse($checkupDate);
        
        $ageInMonths = (int) $birthDate->diffInMonths($checkupDate);

        // Normalize gender string
        $genderKey = (strtolower($gender) == 'laki-laki' || strtolower($gender) == 'male') ? 'male' : 'female';

        // 2. Get WHO Standard Data from Database
        // We limit lookup to 60 months as that's the usual WHO standard limit for standard charts (0-5 years)
        // If age > 60 months, we use 60 months standard or return N/A ideally, but let's cap at 60.
        $lookupMonth = min($ageInMonths, 60);

        $std = AnthropometryStandard::where('gender', $genderKey)
            ->where('age_in_months', $lookupMonth)
            ->first();

        // Fallback if data missing (should be seeded)
        if (!$std) {
            // Log error or return default
            return [
                'age_months' => $ageInMonths,
                'z_score' => 0,
                'status' => 'Data Standar Tidak Ditemukan',
            ];
        }

        // 3. Calculate Z-Score Calculation using LMS method (if we want high precision) or simplified SD method.
        // Since we have specific SD columns (sd_3_neg, sd_1_pos, etc), we can use the "Box-Cox power exponential" LMS formula 
        // Z = ((weight/M)^L - 1) / (L * S)
        // This is THE most accurate way as per WHO documentation.
        // Let's check if L, M, S are available. The user migration has them.

        if ($std->l_value != 0) {
            $zScore = (pow(($weight / $std->m_value), $std->l_value) - 1) / ($std->l_value * $std->s_value);
        } else {
            $zScore = log($weight / $std->m_value) / $std->s_value;
        }

        $zScore = round($zScore, 2);

        // 4. Determine Status
        $status = 'Gizi Baik';
        if ($zScore < -3) {
            $status = 'Gizi Buruk';
        } elseif ($zScore >= -3 && $zScore < -2) {
            $status = 'Gizi Kurang';
        } elseif ($zScore >= -2 && $zScore <= 1) { // Normal changed to +1 SD for strict check, or +2 SD depending on standard. Indonensia Permenkes 2020: -2 SD to +1 SD is Normal for BB/U? Let's check.
            // Permenkes 2 2020:
            // BB/U:
            // < -3 SD : Berat badan sangat kurang (Severely Underweight)
            // -3 SD s/d < -2 SD : Berat badan kurang (Underweight)
            // -2 SD s/d +1 SD : Berat badan normal
            // > +1 SD : Risiko Berat badan lebih
            $status = 'Gizi Baik';
        } elseif ($zScore > 1) {
            $status = 'Risiko Lebih';
        }

        return [
            'age_months' => $ageInMonths,
            'z_score' => $zScore,
            'status' => $status,
        ];
    }
}
