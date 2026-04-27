<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\DailySafetyPatrol;
use App\Models\ImageSafetyPatrol;
use App\Models\ProjectSafety;
use App\Models\SafetyBriefing;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
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
        if ($request->type === 'weekly') {
            return $this->exportWeekly($request);
        }

        return $this->exportMonthly($request);
    }

    private function exportWeekly($request)
    {
        $templatePath = storage_path('app/public/templates/weekly-report.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $sheetUac = $spreadsheet->getSheet(1);
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
        $crane = DailySafetyPatrol::where('permit', 'Crane')->count();

        $sheet->setCellValue('E29', $gabungan);
        $sheet->setCellValue('E30', $ketinggian);
        $sheet->setCellValue('E31', $listrik);
        $sheet->setCellValue('E32', $penggalian);
        $sheet->setCellValue('E33', $crane);
        $sheet->setCellValue('E34', '=SUM(E29:E33)');

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

        $uac = ImageSafetyPatrol::with('safetyPatrol.project')
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

        $safetyBriefing = SafetyBriefing::whereBetween('created_at', [
                $tanggalMulai->toDateString(),
                $tanggalAkhir->toDateString()
            ])->get();

        //dd($safetyBriefing->count());

        $sheet->setCellValue('E20', "=MAX(E38:E53)");
        $sheet->setCellValue('E21', "=SUM(E55:E70)");
        $sheet->setCellValue('E21', $safetyBriefing->count());
        $sheet->setCellValue('E23', "=SUM(E89:E104)");
        $sheet->setCellValue('E24', "=SUM(E106:E121)");
        $sheet->setCellValue('E25', $unsafeAction->count());
        $sheet->setCellValue('E26', $unsafeCondition->count());
        $sheet->setCellValue('E27', "=SUM(E123:E138)");
        $sheet->setCellValue('E28', "=SUM(E140:E155)");

        //dd($uac);

        $dataStartRowUac = 10;

        //dd($dataStartRowUac);

        foreach ($uac as $data) {
            $sheetUac->setCellValue('B' . $dataStartRowUac, $data['created_at']);
            $sheetUac->setCellValue('C' . $dataStartRowUac, $data->safetyPatrol->project->lokasi);
            $sheetUac->setCellValue('D' . $dataStartRowUac, $data['text']);
            $sheetUac->setCellValue('E' . $dataStartRowUac, $data['image_url']);
            $sheetUac->setCellValue('F' . $dataStartRowUac, $data['tindakan_perbaikan']);
            $sheetUac->setCellValue('G' . $dataStartRowUac, $data['label']);
            $sheetUac->setCellValue('H' . $dataStartRowUac, $data['status']);

            $dataStartRowUac++;
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="weekly-report-' . now()->format('d M Y') . '.xlsx"',
        ]);
    }

    private function exportMonthly($request)
    {

        if ($request->mode === 'month') {
            $start = Carbon::create($request->year, $request->month, 1)->startOfMonth();
            $end   = Carbon::create($request->year, $request->month, 1)->endOfMonth();
        } else {
            $start = Carbon::parse($request->start_date);
            $end   = Carbon::parse($request->end_date);
        }

        $templatePath = storage_path('app/public/templates/monthly-report.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheetSummary = $spreadsheet->getActiveSheet();
        $sheetSafetyPatrol = $spreadsheet->getSheet(1);
        $sheetUac = $spreadsheet->getSheet(2);
        $totalDays = $start->daysInMonth;

        $startRow = 11;
        $data = DailySafetyPatrol::with('project')
            ->withCount('users')
            ->whereBetween('created_at', [
                $start->toDateString(),
                $end->toDateString()
            ])->get();

        //dd(collect($data)->pluck('jumlah_pekerja')->sum());

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
            'Man Power'   => 11,
            'Man Hour'    => 15,
            'Nearmiss'    => 23,
            'Kecelakaan'  => 27,
            'Reward'      => 31,
            'Punishment'  => 35,
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
                for ($i = 1; $i <= $totalDays; $i++) {
                    $row[$i] = 0;
                }

                foreach ($rows as $item) {

                    $dayIndex = $start
                        ->diffInDays(
                            Carbon::parse($item->created_at)->startOfDay(),
                            false
                        ) + 1;

                    if ($dayIndex >= 1 && $dayIndex <= $totalDays) {

                        $value = $report['value']($item);

                        $row[$dayIndex] += $value;
                        $row['total']   += $value;
                    }
                }

                $exportData[] = $row;
            }
        }
        $sheetSummary->setCellValue('M1', now()->format('d M Y'));
        // Permit
        $gabungan = DailySafetyPatrol::where('permit', 'Gabungan')->count();
        $ketinggian = DailySafetyPatrol::where('permit', 'Ketinggian')->count();
        $listrik = DailySafetyPatrol::where('permit', 'listrik')->count();
        $penggalian = DailySafetyPatrol::where('permit', 'Penggalian')->count();
        $crane = DailySafetyPatrol::where('permit', 'Crane')->count();

        $sheetSummary->setCellValue('E28', $gabungan);
        $sheetSummary->setCellValue('E29', $ketinggian);
        $sheetSummary->setCellValue('E30', $listrik);
        $sheetSummary->setCellValue('E31', $penggalian);
        $sheetSummary->setCellValue('E32', $crane);
        $sheetSummary->setCellValue('E33', '=SUM(E28:E32)');

        foreach ($reportMap as $reportName => $startRow) {

            $rows = collect($exportData)
                ->where('daily_report', $reportName)
                ->values();

            $rowExcel = $startRow;
            $dataStartRow = $startRow;
            $dataEndRow   = $startRow + $rows->count() - 1;
            $totalRow     = $startRow + 2;

            // 1️⃣ isi data project
            foreach ($rows as $row) {

                $sheetSafetyPatrol->setCellValue('C' . $rowExcel, $row['project']);
                $sheetSafetyPatrol->setCellValue('D' . $rowExcel, $row['total']);

                foreach (range(1, $totalDays) as $day) {
                    $column = Coordinate::stringFromColumnIndex(4 + $day);
                    $sheetSafetyPatrol->setCellValue($column . $rowExcel, $row[$day]);
                }

                $rowExcel++;
            }

            // 2️⃣ isi TOTAL (SUM per kolom)
            for ($i = 0; $i <= $totalDays; $i++) {

                $column = Coordinate::stringFromColumnIndex(4 + $i);

                $sheetSafetyPatrol->setCellValue(
                    $column . $totalRow,
                    "=SUM({$column}{$dataStartRow}:{$column}{$dataEndRow})"
                );
            }
        }

        $uac = ImageSafetyPatrol::with('safetyPatrol.project')
            ->whereBetween('created_at', [
                $start->toDateString(),
                $end->toDateString()
            ])->get();

        $unsafeAction = collect($uac)
            ->where('label', 'ua')
            ->values();
        $unsafeCondition = collect($uac)
            ->where('label', 'uc')
            ->values();

        $safetyBriefing = SafetyBriefing::whereBetween('created_at', [
                $start->toDateString(),
                $end->toDateString()
            ])->get();

        //dd($unsafeAction->count());

        $sheetSummary->setCellValue('E19', collect($data)->pluck('jumlah_pekerja')->sum());
        $sheetSummary->setCellValue('E20', collect($data)->sum(function ($item) {
            return $item->jumlah_pekerja * $item->jam_kerja;
        }));
        $sheetSummary->setCellValue('E21', $safetyBriefing->count());
        $sheetSummary->setCellValue('E22', collect($data)->sum(function ($item) {
            return !empty($item->nearmiss) ? 1 : 0;
        }));
        $sheetSummary->setCellValue('E23', collect($data)->sum(function ($item) {
            return !empty($item->kecelakaan) ? 1 : 0;
        }));
        $sheetSummary->setCellValue('E24', $unsafeAction->count());
        $sheetSummary->setCellValue('E25', $unsafeCondition->count());
        $sheetSummary->setCellValue('E26', collect($data)->sum(function ($item) {
            return !empty($item->reward) ? 1 : 0;
        }));
        $sheetSummary->setCellValue('E27', collect($data)->sum(function ($item) {
            return !empty($item->punishment) ? 1 : 0;
        }));

        //dd($uac);

        $dataStartRowUac = 10;

        //dd($dataStartRowUac);

        foreach ($uac as $data) {
            $sheetUac->setCellValue('B' . $dataStartRowUac, $data['created_at']);
            $sheetUac->setCellValue('C' . $dataStartRowUac, $data->safetyPatrol->project->lokasi);
            $sheetUac->setCellValue('D' . $dataStartRowUac, $data['text']);
            $sheetUac->setCellValue('E' . $dataStartRowUac, $data['image_url']);
            $sheetUac->setCellValue('F' . $dataStartRowUac, $data['tindakan_perbaikan']);
            $sheetUac->setCellValue('G' . $dataStartRowUac, $data['label']);
            $sheetUac->setCellValue('H' . $dataStartRowUac, $data['status']);

            $dataStartRowUac++;
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="monthly-report-' . now()->format('d M Y') . '.xlsx"',
        ]);
    }
}
