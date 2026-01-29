<?php

namespace App\Http\Controllers;

use App\Http\Requests\DailySafetyPatrolRequest;
use App\Models\DailySafetyPatrol;
use App\Models\ImageSafetyPatrol;
use App\Models\ProjectSafety;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailySafetyPatrolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = DailySafetyPatrol::orderBy('created_at', 'desc')->paginate(5);

        //dd($datas);

        return view('pages.safety_patrol.daily_report.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = ProjectSafety::where('status', 'Berjalan')->get();
        $permits = ['Gabungan', 'Ketinggian', 'Penggalian', 'Crane', 'Listrik'];
        $users = User::all();
        $today = Carbon::today()->toDateString();

        return view('pages.safety_patrol.daily_report.create', compact(['projects', 'permits', 'today', 'users']));
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
                    'status' => null,
                    'tindakan_perbaikan' => null,
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
                    'status' => null,
                    'tindakan_perbaikan' => null,
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

        return redirect()
            ->route('daily-report.index')
            ->with('success', 'Berhasil menambah laporan');
    }


    /**
     * Display the specified resource.
     */
    public function show(DailySafetyPatrol $dailyReport)
    {


        $dailyReport->load(['images', 'users']);

        //dd($dailyReport);

        return view('pages.safety_patrol.daily_report.show', compact('dailyReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailySafetyPatrol $dailySafetyPatrol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailySafetyPatrol $dailySafetyPatrol)
    {
        //
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
