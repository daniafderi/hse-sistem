<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySafetyPatrol;
use App\Models\ProjectSafety;
use App\Models\SafetyBriefing;
use App\Models\Notification;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $patrolWeekNow = DailySafetyPatrol::whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $breafingWeekNow = SafetyBriefing::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $patrolWeekLast = DailySafetyPatrol::whereBetween('tanggal', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        $breafingWeekLast = SafetyBriefing::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        $tools = Tool::all();
        $toolsStock = Tool::sum('stock');

        $projectSelesai = ProjectSafety::where('status', 'Selesai')->count();

        if ($patrolWeekLast == 0) {
            $patrolPercent = $patrolWeekNow > 0 ? 100 : 0;
        } else {
            $patrolPercent = (($patrolWeekNow - $patrolWeekLast) / $patrolWeekLast) * 100;
        }

        if ($breafingWeekLast == 0) {
            $breafingPercent = $breafingWeekNow > 0 ? 100 : 0;
        } else {
            $breafingPercent = (($breafingWeekNow - $breafingWeekLast) / $breafingWeekLast) * 100;
        }

        $patrolPercent = round($patrolPercent, 2);

        $breafingPercent = round($breafingPercent, 2);

        $projectBerjalan = ProjectSafety::all()->count();

        if ($projectBerjalan > 0) {
            $projectPercentage = ($projectSelesai / $projectBerjalan) * 100;
        } else {
            $projectPercentage = 0;
        }


        $notifs = Notification::where('is_read', false)->latest()->get();

        // ambil tanggal 7 hari terakhir
    $dates = collect(range(0, 6))->map(function ($i) {
        return Carbon::now()->subDays($i)->format('Y-m-d');
    })->reverse()->values();

    // ambil data dari DB
    $data = DB::table('image_safety_patrols')
        ->selectRaw('DATE(created_at) as date, label, COUNT(*) as total')
        ->whereDate('created_at', '>=', Carbon::now()->subDays(6))
        ->groupBy('date', 'label')
        ->get();

    // mapping data
    $ua = [];
    $uc = [];

    foreach ($dates as $date) {
        $ua[] = $data->where('date', $date)->where('label', 'ua')->sum('total');
        $uc[] = $data->where('date', $date)->where('label', 'uc')->sum('total');
    }

        //dd($percent);

        return view('pages.dashboard', compact(['patrolWeekNow', 'patrolPercent', 'breafingWeekNow', 'breafingPercent', 'projectBerjalan', 'notifs', 'tools', 'toolsStock', 'projectPercentage', 'ua', 'uc', 'dates']));
    }
}
