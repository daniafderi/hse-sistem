<?php

namespace App\Http\Controllers;

use App\Models\LoanItem;
use App\Models\Tool;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\StockApdTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tool::query();

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ============================
        // 3. Sorting (Urutkan)
        // terbaru = desc, terlama = asc
        // ============================
        if ($request->sort === 'aman') {
            $query->whereRaw('stock > stock_minimum * 0.1');
        } else if ($request->sort === 'menipis') {
            $query->whereRaw('stock <= stock_minimum * 0.1'); // default terbaru
        }

        if (Gate::allows('isSupervisor') || Gate::allows('isHseKantor')) {
            $tools = $query->paginate(10)->withQueryString();
        } else {
            $tools = $query->where('validation', 'valid')->paginate(10)->withQueryString();
        }

        $datas = $query->where('validation', 'valid')->paginate(10)->withQueryString();

        //dd($datas);

        return view('pages.tool.index', compact('tools', 'datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.tool.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('tools', 'public');
        }
        $tool = Tool::create($data + [
            'validation' => 'menunggu'
        ]);

        // 💾 SIMPAN TRANSAKSI
        StockApdTransaction::create([
            'tool_id' => $tool->id,
            'type' => 'in',
            'quantity' => $request->stock,
            'note' => 'APD Baru',
            'user_id' => auth()->id(),
            'stock_before' => 0
        ]);

        $notif = Notification::create([
            'type' => 'apd_baru',
            'title' => 'APD Baru ditambahkan',
            'message' => 'APD baru telah ditambahkan ke sistem',
            'notifiable_id' => $tool->id,
            'notifiable_type' => Tool::class,
            'created_by' => auth()->id()
        ]);

        $users = User::whereIn('role', ['HSE Kantor', 'Supervisor'])->pluck('id');

        // kirim ke user tertentu
        $notif->users()->attach($users);
        return redirect()->route('tools.index')->with('success', 'Alat
           berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        $tool->load(['loanItems', 'stockTransaction']);

        return view('pages.tool.show', compact('tool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        return view('pages.tool.edit', compact('tool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tool $tool)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
        if ($request->hasFile('image_path')) {

            // hapus gambar lama (opsional tapi bagus)
            if ($tool->image_path && Storage::disk('public')->exists($tool->image_path)) {
                Storage::disk('public')->delete($tool->image_path);
            }

            $data['image_path'] = $request->file('image_path')->store('tools', 'public');
        }
        $tool->update($data);


        return redirect()->route('tools.index')->with('success', 'Alat
           diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool)
    {
        $tool->delete();

        LoanItem::where('tool_id', $tool->id)->delete();

        return redirect()->route('tools.index')->with('success', 'Berhasil menghapus APD');
    }

    public function validation(Request $request, Tool $tool)
    {
        $request->validate([
            'status' => 'required|in:valid,ditolak,revisi',
            'komentar' => 'nullable|string',
        ]);

        //dd($request->status);

        // Update status laporan
        $tool->update([
            'validation' => $request->status,
        ]);

        $notif = Notification::create([
            'type' => 'apd_validate',
            'title' => 'APD divalidasi',
            'message' => 'APD baru telah divalidasi',
            'notifiable_id' => $tool->id,
            'notifiable_type' => Tool::class,
            'created_by' => auth()->id()
        ]);

        $users = User::whereIn('role', ['HSE Kantor', 'Supervisor'])->pluck('id');

        // kirim ke user tertentu
        $notif->users()->attach($users);

        return back()->with('toast_success', 'Validasi berhasil disimpan.');
    }

    public function export()
    {
        return view('pages.tool.export');
    }

    public function download(Request $request)
    {
        $templatePath = storage_path('app/public/templates/apd.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalAkhir = $tanggalMulai->copy()->addDays(5);

        $rowExcel = 9;

        $data = Tool::with(['stockTransaction' => function ($query) use ($tanggalMulai, $tanggalAkhir) {
            $query->whereBetween('created_at', [
                $tanggalMulai->toDateString(),
                $tanggalAkhir->toDateString()
            ]);
        }])->get();

        //dd($data);

        $startColumnIndex = 5; // D

        foreach ($data as $index => $tool) {

            // 1️⃣ Tulis nama APD
            $sheet->setCellValue('A' . $rowExcel, $index + 1);
            $sheet->setCellValue('B' . $rowExcel, $tool->name);
            $sheet->setCellValue('D' . $rowExcel, $tool->stockTransaction->first()?->stock_before ?? 0);
            $sheet->setCellValue('Q' . $rowExcel, $tool->stock);

            // 2️⃣ Init data harian
            $days = [];

            for ($day = 1; $day <= 6; $day++) {
                $days[$day] = [
                    'masuk' => 0,
                    'keluar' => 0,
                ];
            }

            // 3️⃣ Isi dari transaksi
            foreach ($tool->stockTransaction as $trx) {

                $day = $tanggalMulai
                    ->diffInDays(Carbon::parse($trx->created_at), false) + 1;

                if ($day >= 1 && $day <= 6) {

                    if ($trx->type === 'in') {
                        $days[$day]['masuk'] += $trx->quantity;
                    } else {
                        $days[$day]['keluar'] += $trx->quantity;
                    }
                }
            }

            // 4️⃣ Masukkan ke Excel (Masuk & Keluar)
            foreach ($days as $day => $val) {

                $baseCol = $startColumnIndex + (($day - 1) * 2);

                $masukCol  = Coordinate::stringFromColumnIndex($baseCol);
                $keluarCol = Coordinate::stringFromColumnIndex($baseCol + 1);

                $sheet->setCellValue($masukCol . $rowExcel, $val['masuk']);
                $sheet->setCellValue($keluarCol . $rowExcel, $val['keluar']);
            }

            $rowExcel++;
        }

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="apd-report-' . now()->format('d M Y') . '.xlsx"',
        ]);
    }
}
