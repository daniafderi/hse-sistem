<?php

namespace App\Http\Controllers;

use App\Http\Requests\DailySafetyPatrolRequest;
use App\Models\DailySafetyPatrol;
use App\Models\ImageSafetyPatrol;
use App\Models\ProjectSafety;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DailySafetyPatrolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DailySafetyPatrol::with('project');

        // ============================
        // 1. Filter Status
        // ============================
        if ($request->status && $request->status !== 'semua') {
            $query->where('status_validasi', $request->status);
        }

        // ============================
        // 2. Pencarian
        // ============================
        if ($request->search) {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('project', function ($q2) use ($keyword) {
                    $q2->where('nama', 'like', '%' . $keyword . '%'); // dari tabel project
                });
            });
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

        //dd($datas);

        return view('pages.safety_patrol.daily_report.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::allows('isHseLapangan')) {
            $projects = ProjectSafety::where('status', 'Berjalan')->get();
            $permits = ['Gabungan', 'Ketinggian', 'Penggalian', 'Crane', 'Listrik'];
            $users = User::all();
            $today = Carbon::today()->toDateString();

            return view('pages.safety_patrol.daily_report.create', compact(['projects', 'permits', 'today', 'users']));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DailySafetyPatrolRequest $request)
    {
        $dailySafety = $request->validated();

        $patrol = DailySafetyPatrol::create($dailySafety);

        /* ===============================
     * Unsafe Action
     * =============================== */
        if ($request->has('unsafe_action')) {
            foreach ($request->unsafe_action as $item) {

                // ✅ skip jika kosong
                if (
                    empty(trim($item['text'] ?? '')) &&
                    empty($item['images'][0] ?? null)
                ) {
                    continue;
                }

                $imagePath = null;
                if (!empty($item['images'][0])) {
                    $imagePath = $item['images'][0]->store('safety_patrol', 'public');
                }

                ImageSafetyPatrol::create([
                    'daily_safety_patrol_id' => $patrol->id,
                    'text' => $item['text'] ?? null,
                    'image_url' => $imagePath,
                    'label' => 'ua',
                    'status' => $item['tindakan_perbaikan'] ? 'Selesai' : '',
                    'tindakan_perbaikan' => $item['tindakan_perbaikan'] ?? '',
                ]);
            }
        }

        /* ===============================
     * Unsafe Condition
     * =============================== */
        if ($request->has('unsafe_condition')) {
            foreach ($request->unsafe_condition as $item) {

                if (
                    empty(trim($item['text'] ?? '')) &&
                    empty($item['images'][0] ?? null)
                ) {
                    continue;
                }

                $imagePath = null;
                if (!empty($item['images'][0])) {
                    $imagePath = $item['images'][0]->store('safety_patrol', 'public');
                }

                ImageSafetyPatrol::create([
                    'daily_safety_patrol_id' => $patrol->id,
                    'text' => $item['text'] ?? null,
                    'image_url' => $imagePath,
                    'label' => 'uc',
                    'status' => $item['tindakan_perbaikan'] ? 'Selesai' : '',
                    'tindakan_perbaikan' => $item['tindakan_perbaikan'] ?? '',
                ]);
            }
        }

        /* ===============================
     * Sync Users
     * =============================== */
        $userIds = array_unique(array_merge(
            [$request->user()->id],
            $dailySafety['users'] ?? []
        ));

        $patrol->users()->sync($userIds);

        $notif = Notification::create([
    'type' => 'report_created',
    'title' => 'Laporan Baru',
    'message' => 'Laporan baru telah dibuat',
    'notifiable_id' => $patrol->id,
    'notifiable_type' => DailySafetyPatrol::class,
    'created_by' => auth()->id()
]);

$users = User::whereIn('role', ['hselapangan', 'supervisor'])->pluck('id');

// kirim ke user tertentu
$notif->users()->attach($users);

        return redirect()
            ->route('daily-report.index')
            ->with('success', 'Berhasil menambah laporan');
    }


    /**
     * Display the specified resource.
     */
    public function show(DailySafetyPatrol $dailySafetyPatrol)
    {


        $dailyReport = $dailySafetyPatrol->load(['images', 'users']);

        //dd($dailyReport);

        return view('pages.safety_patrol.daily_report.show', compact('dailyReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailySafetyPatrol $dailySafetyPatrol)
    {
        if (Gate::allows('isHseLapangan')) {
            $projects = ProjectSafety::where('status', 'Berjalan')->get();
            $permits = ['Gabungan', 'Ketinggian', 'Penggalian', 'Crane', 'Listrik'];
            $users = User::all();
            $today = Carbon::today()->toDateString();
            $report = $dailySafetyPatrol->load('images');
            $unsafeActions = $report->images->where('label', 'ua')->values();
            $unsafeConditions = $report->images->where('label', 'uc')->values();
            //dd($report->toArray());

            return view('pages.safety_patrol.daily_report.edit', compact(['projects', 'permits', 'today', 'users', 'report', 'unsafeActions', 'unsafeConditions']));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DailySafetyPatrolRequest $request, DailySafetyPatrol $dailySafetyPatrol)
    {
        $dailySafety = $request->validated();

        // ===============================
        // 1. Update data utama
        // ===============================
        $dailySafetyPatrol->update($dailySafety);

        // ===============================
        // 2. Hapus data lama (UA & UC)
        // ===============================

        // ambil semua image lama
$existingImages = $dailySafetyPatrol->images->keyBy('id');

// reset dulu (opsional kalau mau full replace)
$dailySafetyPatrol->images()->delete();
if ($request->has('unsafe_action')) {

    foreach ($request->unsafe_action as $item) {
    
        if (
            empty(trim($item['text'] ?? '')) &&
            empty($item['images'][0] ?? null)
        ) {
            continue;
        }
    
        $imagePath = $item['old_image'] ?? null; // 🔥 pakai image lama
    
        // kalau ada upload baru → replace
        if (!empty($item['images'][0])) {
    
            // hapus file lama
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
    
            $imagePath = $item['images'][0]->store('safety_patrol', 'public');
        }
    
        ImageSafetyPatrol::create([
            'daily_safety_patrol_id' => $dailySafetyPatrol->id,
            'text' => $item['text'] ?? null,
            'image_url' => $imagePath,
            'label' => 'ua',
            'status' => !empty($item['tindakan_perbaikan']) ? 'Selesai' : '',
            'tindakan_perbaikan' => $item['tindakan_perbaikan'] ?? '',
        ]);
    }
}
if ($request->has('unsafe_condition')) {

    foreach ($request->unsafe_condition as $item) {
    
        if (
            empty(trim($item['text'] ?? '')) &&
            empty($item['images'][0] ?? null)
        ) {
            continue;
        }
    
        $imagePath = $item['old_image'] ?? null; // 🔥 pakai image lama
    
        // kalau ada upload baru → replace
        if (!empty($item['images'][0])) {
    
            // hapus file lama
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
    
            $imagePath = $item['images'][0]->store('safety_patrol', 'public');
        }
    
        ImageSafetyPatrol::create([
            'daily_safety_patrol_id' => $dailySafetyPatrol->id,
            'text' => $item['text'] ?? null,
            'image_url' => $imagePath,
            'label' => 'uc',
            'status' => !empty($item['tindakan_perbaikan']) ? 'Selesai' : '',
            'tindakan_perbaikan' => $item['tindakan_perbaikan'] ?? '',
        ]);
    }
}

        // ===============================
        // 5. Sync Users
        // ===============================
        $userIds = array_unique(array_merge(
            [$request->user()->id],
            $dailySafety['users'] ?? []
        ));

        $dailySafetyPatrol->users()->sync($userIds);

        return redirect()
            ->route('daily-report.index')
            ->with('success', 'Berhasil update laporan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailySafetyPatrol $dailySafetyPatrol)
    {
        //
    }

    public function validation(Request $request) {}
}
