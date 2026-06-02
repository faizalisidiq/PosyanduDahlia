<?php

namespace App\Services;

use App\Models\Mother;
use App\Models\ChildbirthRecord;
use App\Models\Children;
use App\Models\IlpScreening;
use App\Models\HealthPost;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Eloquent\Builder;

class ExcelExportService
{
    /**
     * Apply common filters (Health Post, Address, Age) to a query
     */
    private function applyCommonFilters(Builder $query, array $filters, string $relationForStaff = null)
    {
        // Filter by Health Post
        if (!empty($filters['health_post_id'])) {
            if ($relationForStaff) {
                $query->whereHas($relationForStaff, function ($q) use ($filters) {
                    $q->whereHas('staff', function ($sq) use ($filters) {
                        $sq->where('health_post_id', $filters['health_post_id']);
                    });
                });
            } else {
                // If direct staff_id exists on the model itself (like ChildbirthRecord or IlpScreening)
                $query->whereHas('staff', function ($q) use ($filters) {
                    $q->where('health_post_id', $filters['health_post_id']);
                });
            }
        }

        // Filter by Address
        if (!empty($filters['address'])) {
            $query->where('address', 'like', '%' . $filters['address'] . '%');
        }

        // Filter by Age Range (in months)
        if (isset($filters['age_min']) || isset($filters['age_max'])) {
            $query->where(function ($q) use ($filters) {
                $now = Carbon::now();
                if (isset($filters['age_min'])) {
                    $q->where('birth_date', '<=', $now->copy()->subMonths((int)$filters['age_min'])->endOfMonth());
                }
                if (isset($filters['age_max'])) {
                    $q->where('birth_date', '>=', $now->copy()->subMonths((int)$filters['age_max'])->startOfMonth());
                }
            });
        }

        return $query;
    }

    /**
     * Export Mother data to Excel
     */
    public function exportMothers($startDate = null, $endDate = null, $status = 'hamil', array $filters = [])
    {
        $query = Mother::with(['pregnancyRecords' => function ($query) {
            $query->latest('visit_date');
        }])->where('status', $status);

        if ($startDate && $endDate) {
            $query->whereHas('pregnancyRecords', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('visit_date', [$startDate, $endDate]);
            });
        }

        $this->applyCommonFilters($query, $filters, 'pregnancyRecords');

        $mothers = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $title = $status == 'hamil' ? 'DATA IBU HAMIL' : 'DATA IBU MENYUSUI';
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header
        $headers = [
            'NO', 'NAMA', 'NIK', 'TTL', 'NAMA SUAMI', 'HAMIL KE-', 'BB', 'TB', 'LILA', 'GOLONGAN DARAH', 'TENSI', 'NO. BPJS', 'FASKES'
        ];

        $sheet->fromArray($headers, null, 'A2');

        // Style header
        $sheet->getStyle('A2:M2')->getFont()->setBold(true);
        $sheet->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Data
        $row = 3;
        foreach ($mothers as $index => $mother) {
            $latestRecord = $mother->pregnancyRecords->first();
            
            $data = [
                $index + 1,
                $mother->name,
                "'" . $mother->identity_number,
                ($mother->birth_place ?? '-') . ', ' . ($mother->birth_date ? $mother->birth_date->format('d-m-Y') : '-'),
                $mother->husband_name ?? '-',
                $latestRecord->pregnancy_order ?? '-',
                $latestRecord->weight ?? $mother->weight ?? '-',
                $mother->height ?? '-',
                $latestRecord->arm_circumference ?? '-',
                $mother->blood_type ?? '-',
                $latestRecord->blood_pressure ?? '-',
                "'" . ($mother->social_security_number ?? '-'),
                $mother->health_facility ?? '-'
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        // Auto dimension
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'data_ibu_hamil_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
        
        $writer->save($tempPath);

        return $fileName;
    }

    /**
     * Export Childbirth data to Excel
     */
    public function exportChildbirths($startDate = null, $endDate = null, array $filters = [])
    {
        $query = ChildbirthRecord::with(['mother', 'children']);

        if ($startDate && $endDate) {
            $query->whereBetween('delivery_date', [$startDate, $endDate]);
        }

        // ChildbirthRecord already has staff_id
        if (!empty($filters['health_post_id'])) {
            $query->whereHas('staff', function ($q) use ($filters) {
                $q->where('health_post_id', $filters['health_post_id']);
            });
        }

        if (!empty($filters['address'])) {
            $query->whereHas('mother', function ($q) use ($filters) {
                $q->where('address', 'like', '%' . $filters['address'] . '%');
            });
        }

        $records = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', 'DATA IBU MELAHIRKAN');
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header
        $headers = [
            'NO', 'NAMA IBU', 'NIK', 'TTL', 'NAMA AYAH', 'NO. TELEPON', 'NO. BPJS', 'NAMA ANAK', 'ANAK KE-', 'TANGGAL LAHIR ANAK', 'JENIS KELAMIN', 'BB ANAK', 'PB ANAK', 'NORMAL/CAESAR', 'RS', 'ALAMAT RUMAH'
        ];

        $sheet->fromArray($headers, null, 'A2');

        // Style header
        $sheet->getStyle('A2:P2')->getFont()->setBold(true);
        $sheet->getStyle('A2:P2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Data
        $row = 3;
        foreach ($records as $index => $record) {
            $mother = $record->mother;
            $child = $record->children;
            
            $data = [
                $index + 1,
                $mother->name ?? '-',
                "'" . ($mother->identity_number ?? '-'),
                ($mother->birth_place ?? '-') . ', ' . ($mother->birth_date ? $mother->birth_date->format('d-m-Y') : '-'),
                $mother->husband_name ?? '-',
                $mother->phone_number ?? '-',
                "'" . ($mother->social_security_number ?? '-'),
                $child->name ?? '-',
                $record->child_order ?? '-',
                $record->delivery_date ? $record->delivery_date->format('d-m-Y') : ($child->birth_date ? $child->birth_date->format('d-m-Y') : '-'),
                ($child->gender ?? '-') == 'male' ? 'Laki-laki' : 'Perempuan',
                $child->birth_weight ?? '-',
                $child->birth_height ?? '-',
                $record->delivery_method ?? '-',
                $record->delivery_location ?? '-',
                $mother->address ?? '-'
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        // Auto dimension
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'data_ibu_melahirkan_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
        
        $writer->save($tempPath);

        return $fileName;
    }

    /**
     * Export Children Growth Monitoring data to Excel for a range of years
     */
    public function exportChildrenGrowth($fromYear, $untilYear, array $filters = [])
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove default sheet

        $months = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL', 
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS', 
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        for ($year = $fromYear; $year <= $untilYear; $year++) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle((string)$year);

            $query = Children::with(['mother', 'growthMonitorings' => function ($query) use ($year) {
                $query->whereYear('checkup_date', $year);
            }]);

            // Apply filters to children
            if (!empty($filters['health_post_id'])) {
                $query->whereHas('growthMonitorings', function ($q) use ($filters) {
                    $q->whereHas('staff', function ($sq) use ($filters) {
                        $sq->where('health_post_id', $filters['health_post_id']);
                    });
                });
            }

            if (!empty($filters['address'])) {
                $query->whereHas('mother', function ($q) use ($filters) {
                    $q->where('address', 'like', '%' . $filters['address'] . '%');
                });
            }

            if (isset($filters['age_min']) || isset($filters['age_max'])) {
                $now = Carbon::now();
                if (isset($filters['age_min'])) {
                    $query->where('birth_date', '<=', $now->copy()->subMonths((int)$filters['age_min'])->endOfMonth());
                }
                if (isset($filters['age_max'])) {
                    $query->where('birth_date', '>=', $now->copy()->subMonths((int)$filters['age_max'])->startOfMonth());
                }
            }

            $children = $query->get();

            // Title
            $sheet->setCellValue('A1', 'HASIL PENIMBANGAN BALITA TAHUN ' . $year);
            $sheet->mergeCells('A1:BA1');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            // Header Row 1
            $sheet->setCellValue('A2', 'NO');
            $sheet->setCellValue('B2', 'NAMA BALITA');
            $sheet->setCellValue('C2', 'NIK');
            $sheet->setCellValue('D2', 'NAMA ORANG TUA');
            $sheet->setCellValue('E2', 'TTL');
            
            $sheet->mergeCells('A2:A3');
            $sheet->mergeCells('B2:B3');
            $sheet->mergeCells('C2:C3');
            $sheet->mergeCells('D2:D3');
            $sheet->mergeCells('E2:E3');

            $colIndex = 6;
            foreach ($months as $mNum => $mName) {
                $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 3);
                
                $sheet->setCellValue($startCol . '2', $mName);
                $sheet->mergeCells($startCol . '2:' . $endCol . '2');
                
                $sheet->setCellValue($startCol . '3', 'BB');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . '3', 'TB');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 2) . '3', 'LILA');
                $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 3) . '3', 'LINGKEP');
                
                $colIndex += 4;
            }

            // Style headers
            $sheet->getStyle('A2:BA3')->getFont()->setBold(true);
            $sheet->getStyle('A2:BA3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2:BA3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A2:BA3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            // Data
            $row = 4;
            foreach ($children as $index => $child) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $child->name);
                $sheet->setCellValue('C' . $row, "'" . ($child->identity_number ?? '-'));
                $sheet->setCellValue('D' . $row, $child->mother->name ?? '-');
                $sheet->setCellValue('E' . $row, ($child->birth_place ?? '-') . ', ' . ($child->birth_date ? $child->birth_date->format('d-m-Y') : '-'));

                $monthData = [];
                foreach ($child->growthMonitorings as $m) {
                    $month = (int)$m->checkup_date->format('n');
                    $monthData[$month] = $m;
                }

                $colIndex = 6;
                foreach ($months as $mNum => $mName) {
                    if (isset($monthData[$mNum])) {
                        $m = $monthData[$mNum];
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $row, $m->weight);
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . $row, $m->height);
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 2) . $row, $m->arm_circumference ?? '-');
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 3) . $row, $m->head_circumference ?? '-');
                    } else {
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $row, '-');
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1) . $row, '-');
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 2) . $row, '-');
                        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 3) . $row, '-');
                    }
                    $colIndex += 4;
                }
                $row++;
            }

            foreach (range('A', 'E') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'rekap_penimbangan_balita_' . $fromYear . '_' . $untilYear . '_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
        $writer->save($tempPath);

        return $fileName;
    }

    /**
     * Export ILP Screening data to Excel
     */
    public function exportIlp($startDate = null, $endDate = null, array $filters = [])
    {
        $query = IlpScreening::with(['subjectable']);

        if ($startDate && $endDate) {
            $query->whereBetween('checkup_date', [$startDate, $endDate]);
        }

        if (!empty($filters['health_post_id'])) {
            $query->whereHas('staff', function ($q) use ($filters) {
                $q->where('health_post_id', $filters['health_post_id']);
            });
        }

        // For subjectable address, we need to handle different types
        if (!empty($filters['address'])) {
            $query->where(function($q) use ($filters) {
                $q->whereHasMorph('subjectable', [Mother::class, Children::class], function($sq) use ($filters) {
                    $sq->where('address', 'like', '%' . $filters['address'] . '%');
                });
            });
        }

        $screenings = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', 'HASIL PEMERIKSAAN ILP');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Row 1
        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'NAMA');
        $sheet->setCellValue('C2', 'NIK');
        $sheet->setCellValue('D2', 'TTL');
        $sheet->setCellValue('E2', 'HASIL PEMERIKSAAN');

        $sheet->mergeCells('A2:A3');
        $sheet->mergeCells('B2:B3');
        $sheet->mergeCells('C2:C3');
        $sheet->mergeCells('D2:D3');
        $sheet->mergeCells('E2:M2');

        // Header Row 2
        $subHeaders = ['BB', 'TB', 'LINGKAR PERUT', 'TENSI', 'GULA DARAH', 'ASAM URAT', 'KOLESTEROL', 'MATA', 'TELINGA'];
        foreach ($subHeaders as $key => $title) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + $key);
            $sheet->setCellValue($col . '3', $title);
        }

        // Style headers
        $sheet->getStyle('A2:M3')->getFont()->setBold(true);
        $sheet->getStyle('A2:M3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:M3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A2:M3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Data
        $row = 4;
        foreach ($screenings as $index => $s) {
            $subject = $s->subjectable;
            $res = $s->results ?? [];

            $data = [
                $index + 1,
                $subject->name ?? '-',
                "'" . ($subject->identity_number ?? ($subject->mother->identity_number ?? '-')),
                ($subject->birth_place ?? '-') . ', ' . ($subject->birth_date ? $subject->birth_date->format('d-m-Y') : '-'),
                $res['weight'] ?? '-',
                $res['height'] ?? '-',
                $res['waist_circumference'] ?? '-',
                $res['blood_pressure'] ?? '-',
                $res['blood_sugar'] ?? '-',
                $res['uric_acid'] ?? '-',
                $res['cholesterol'] ?? '-',
                $res['eyes'] ?? '-',
                $res['ears'] ?? '-',
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        // Auto dimension
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'hasil_pemeriksaan_ilp_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
        $writer->save($tempPath);

        return $fileName;
    }

    /**
     * Export Specific Child Growth History to Excel
     */
    public function exportChildHistory(Children $child)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', 'RIWAYAT PEMERIKSAAN PERTUMBUHAN ANAK');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Child Info
        $sheet->setCellValue('A3', 'Nama Anak');
        $sheet->setCellValue('B3', ': ' . $child->name);

        $sheet->setCellValue('A4', 'TTL');
        $sheet->setCellValue('B4', ': ' . ($child->birth_place ?? '-') . ', ' . ($child->birth_date ? $child->birth_date->format('d F Y') : '-'));

        $sheet->setCellValue('A5', 'Nama Ibu');
        $sheet->setCellValue('B5', ': ' . ($child->mother->name ?? '-'));

        $sheet->getStyle('A3:A5')->getFont()->setBold(true);
        
        // Header
        $headers = [
            'NO', 'TANGGAL PERIKSA', 'USIA (BULAN)', 'BERAT BADAN (KG)', 'TINGGI BADAN (CM)', 
            'LINGKAR KEPALA (CM)', 'LINGKAR LENGAN (CM)', 'STATUS GIZI', 'CATATAN'
        ];

        $sheet->fromArray($headers, null, 'A7');

        // Style header
        $sheet->getStyle('A7:I7')->getFont()->setBold(true);
        $sheet->getStyle('A7:I7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A7:I7')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Data
        $records = $child->growthMonitorings()->orderBy('checkup_date', 'desc')->get();
        $row = 8;
        foreach ($records as $index => $record) {
            $ageInMonths = max(0, $child->birth_date->diffInMonths($record->checkup_date));
            
            $data = [
                $index + 1,
                $record->checkup_date->format('d-m-Y'),
                $ageInMonths . ' Bulan',
                $record->weight,
                $record->height,
                $record->head_circumference ?? '-',
                $record->arm_circumference ?? '-',
                $record->status ?? '-',
                $record->note ?? '-'
            ];

            $sheet->fromArray($data, null, 'A' . $row);
            $row++;
        }

        // Auto dimension
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        $safeName = preg_replace('/[^A-Za-z0-9\-]/', '_', $child->name);
        $fileName = 'riwayat_pertumbuhan_' . $safeName . '_' . date('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
        
        $writer->save($tempPath);

        return $fileName;
    }
}
