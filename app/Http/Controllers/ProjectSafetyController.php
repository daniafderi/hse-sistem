<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectSafetyRequest;
use App\Models\ProjectSafety;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Gate;

class ProjectSafetyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = ProjectSafety::query();

        // ============================
        // 1. Filter Status
        // ============================
        if ($request->status && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // ============================
        // 3. Sorting (Urutkan)
        // terbaru = desc, terlama = asc
        // ============================
        if ($request->sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // default terbaru
        }

        $datas = $query->paginate(10)->withQueryString();

        return view('pages.safety_patrol.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Gate::allows('tambah-project')) {
            $statuses = ['Berjalan', 'Selesai', 'Dihentikan'];
            return view('pages.safety_patrol.create', compact('statuses'));

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ProjectSafetyRequest $projectRequest)
    {
        $projectData = $projectRequest->validated();

        ProjectSafety::create($projectData);

        return redirect()->route('project.index')->with('success', 'Berhasil menambahkan project baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectSafety $project)
    {
        $project->load(['dailySafetyPatrol']);
        $statuses = ['Berjalan', 'Selesai', 'Dibatalkan', 'Dihentikan'];

        $month = Carbon::parse($project->tanggal_mulai)->format('Y-m');
        $startMonth = Carbon::parse($month . '-01');
        $endMonth = $startMonth->copy()->endOfMonth();
        $start = Carbon::parse($project->tanggal_mulai);
        $end = Carbon::parse($project->tanggal_selesai);

        $period = $start->diffInDays($end) + 1;
        //dd($period);

        // Ambil semua tanggal laporan
        $reports = $project->dailySafetyPatrol()
            ->pluck('tanggal')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        $laporans = $project->dailySafetyPatrol()->orderBy('tanggal', 'desc')->limit(3)->get();

        return view('pages.safety_patrol.show', compact(['project', 'startMonth', 'endMonth', 'reports', 'laporans', 'period', 'statuses']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectSafety $projectSafety)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectSafety $project)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'lokasi' => 'required',
            'status' => 'required|in:Berjalan,Selesai,Dibatalkan,Dihentikan',
            'deskripsi' => 'nullable',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'nullable'
        ]);

        $project->update($validated);

        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectSafety $project)
    {
        $project->delete();

        return redirect()->route('project.index')->with('success', 'Berhasil menghapus project');
    }

    public function exportCsv($id)
    {
        $project = \App\Models\ProjectSafety::findOrFail($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // ===========================================================
        // 1. HEADER MERGE
        // ===========================================================
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'DATA PROJECT');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Background color
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E2E8F0'); // abu tailwind

        // ===========================================================
        // 2. DATA FIELD
        // ===========================================================
        $data = [
            ['ID', $project->id],
            ['Nama', $project->nama],
            ['Lokasi', $project->lokasi],
            ['Status', $project->status],
            ['Tanggal Mulai', $project->tanggal_mulai],
            ['Tanggal Selesai', $project->tanggal_selesai],
        ];

        $row = 3;

        foreach ($data as $item) {
            $sheet->setCellValue("A{$row}", $item[0]);
            $sheet->setCellValue("B{$row}", $item[1]);
            $row++;
        }

        // ===========================================================
        // 3. BORDER STYLE
        // ===========================================================
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        $sheet->getStyle("A3:B" . ($row - 1))->applyFromArray($styleArray);

        // ===========================================================
        // 4. AUTO WIDTH
        // ===========================================================
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);

        // ===========================================================
        // 5. DOWNLOAD FILE
        // ===========================================================
        $filename = 'project_' . $project->id . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        // header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $writer->save("php://output");
        exit();
    }
}
