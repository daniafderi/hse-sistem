<?php

namespace App\Exports;

use App\Models\DailySafetyPatrol;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LaporanExport implements WithEvents
{
    public function registerEvents(): array
    {
        return [

            // LOAD TEMPLATE
            BeforeExport::class => function (BeforeExport $event) {
                $template = storage_path('app/templates/weekly-report.xlsx');
                $spreadsheet = IOFactory::load($template);

                $event->writer->setSpreadsheet($spreadsheet);
            },

            // ISI DATA
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Mulai isi data dari baris (sesuai template kamu)
                $startRow = 10;

                $data = DailySafetyPatrol::all();

                foreach ($data as $index => $row) {
                    $sheet->setCellValue('A' . ($startRow + $index), $index + 1);
                    $sheet->setCellValue('B' . ($startRow + $index), $row->tanggal);
                    $sheet->setCellValue('C' . ($startRow + $index), $row->nama_karyawan);
                    $sheet->setCellValue('D' . ($startRow + $index), $row->aktivitas);
                    $sheet->setCellValue('E' . ($startRow + $index), $row->keterangan);
                }
            }
        ];
    }
}
