<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\DailySafetyPatrol;
use App\Models\ImageSafetyPatrol;
use App\Models\ProjectSafety;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanExportController extends Controller
{
    public function index()
    {
        return view('pages.export.index');
    }

    public function export(Request $request)
    {
        $templatePath = storage_path('app/templates/weekly-report.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheetUac = $spreadsheet->getSheetByName("2. Laporan UAC");
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalAkhir = $tanggalMulai->copy()->addDays(6);

        $startRow = 10;
        $data = DailySafetyPatrol::with('project')
            ->withCount('users')
            ->whereBetween('tanggal', [
                $tanggalMulai->toDateString(),
                $tanggalAkhir->toDateString()
            ])->get();

        $grouped = $data->groupBy('project_safety_id');
        $reportTypes = [
            [
                'label' => 'Man Power',
                'type'  => 'numeric',
                'value' => fn($item) => (int) $item->jumlah_pekerja,
            ],
            [
                'label' => 'Man Hour',
                'type'  => 'calculated',
                'value' => fn($item) => ((int) $item->jumlah_pekerja + $item->users_count) * (int) $item->jam_kerja,
            ],
            [
                'label' => 'Nearmiss',
                'type'  => 'boolean_text',
                'value' => fn($item) =>
                !empty($item->nearmiss) ? 1 : 0,
            ],
            [
                'label' => 'Punishment',
                'type'  => 'boolean_text',
                'value' => fn($item) =>
                !empty($item->punishment) ? 1 : 0,
            ],
            [
                'label' => 'Reward',
                'type'  => 'boolean_text',
                'value' => fn($item) =>
                !empty($item->reward) ? 1 : 0,
            ],
            [
                'label' => 'Kecelakaan',
                'type'  => 'boolean_text',
                'value' => fn($item) =>
                !empty($item->kecelakaan) ? 1 : 0,
            ],
        ];
        $reportMap = [
            'Man Power'   => 38,
            'Man Hour'    => 55,
            'Nearmiss'    => 89,
            'Kecelakaan'  => 106,
            'Reward'      => 123,
            'Punishment'  => 140,
        ];
        $exportData = [];

        foreach ($grouped as $projectId => $rows) {

            $projectName = $rows->first()->project->nama;

            foreach ($reportTypes as $report) {

                $row = [
                    'daily_report' => $report['label'],
                    'project'      => $projectName,
                    'total'        => 0,
                ];

                // init kolom 1–7
                for ($i = 1; $i <= 7; $i++) {
                    $row[$i] = 0;
                }

                foreach ($rows as $item) {

                    $dayIndex = $tanggalMulai
                        ->diffInDays(
                            Carbon::parse($item->tanggal)->startOfDay(),
                            false
                        ) + 1;

                    if ($dayIndex >= 1 && $dayIndex <= 7) {

                        $value = $report['value']($item);

                        $row[$dayIndex] += $value;
                        $row['total']   += $value;
                    }
                }

                $exportData[] = $row;
            }
        }
        $sheet->setCellValue('K1', now()->format('d M Y'));
        // Permit
        $gabungan = DailySafetyPatrol::where('permit', 'Gabungan')->count();
        $ketinggian = DailySafetyPatrol::where('permit', 'Ketinggian')->count();
        $listrik = DailySafetyPatrol::where('permit', 'listrik')->count();
        $penggalian = DailySafetyPatrol::where('permit', 'Penggalian')->count();

        $sheet->setCellValue('E29', $gabungan);
        $sheet->setCellValue('E30', $ketinggian);
        $sheet->setCellValue('E31', $listrik);
        $sheet->setCellValue('E32', $penggalian);

        foreach ($reportMap as $reportName => $startRow) {

            $rows = collect($exportData)
                ->where('daily_report', $reportName)
                ->values();

            $rowExcel = $startRow;
            $dataStartRow = $startRow;
            $dataEndRow   = $startRow + $rows->count() - 1;
            $totalRow     = $startRow + 16;

            // 1️⃣ isi data project
            foreach ($rows as $row) {

                $sheet->setCellValue('C' . $rowExcel, $row['project']);
                $sheet->setCellValue('E' . $rowExcel, $row['total']);

                foreach (range(1, 7) as $day) {
                    $column = chr(ord('F') + ($day - 1));
                    $sheet->setCellValue($column . $rowExcel, $row[$day]);
                }

                $rowExcel++;
            }

            // 2️⃣ isi TOTAL (SUM per kolom)
            foreach (['E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'] as $col) {
                $sheet->setCellValue(
                    $col . $totalRow,
                    "=SUM({$col}{$dataStartRow}:{$col}{$dataEndRow})"
                );
            }
        }

        $uac = ImageSafetyPatrol::with('safetyPatrol')
        ->whereBetween('created_at', [
                $tanggalMulai->toDateString(),
                $tanggalAkhir->toDateString()
            ])->get();

        $unsafeAction = collect($uac)
                ->where('label', 'ua')
                ->values();
        $unsafeCondition = collect($uac)
                ->where('label', 'uc')
                ->values();

        //dd($unsafeAction->count());

        $sheet->setCellValue('E20', "=MAX(E38:E53)");
        $sheet->setCellValue('E21', "=SUM(E55:E70)");
        $sheet->setCellValue('E23', "=SUM(E89:E104)");
        $sheet->setCellValue('E24', "=SUM(E106:E121)");
        $sheet->setCellValue('E25', $unsafeAction->count());
        $sheet->setCellValue('E26', $unsafeCondition->count());
        $sheet->setCellValue('E27', "=SUM(E123:E138)");
        $sheet->setCellValue('E28', "=SUM(E140:E155)");

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="weekly-report-' . now()->format('d M Y') . '.xlsx"',
        ]);
    }
}
