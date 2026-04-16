<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySafetyPatrol;
use App\Models\ProjectSafety;
use App\Models\SafetyBriefing;
use App\Models\Notification;
use App\Models\Tool;

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

        //dd($percent);

        return view('pages.dashboard', compact(['patrolWeekNow', 'patrolPercent', 'breafingWeekNow', 'breafingPercent', 'projectBerjalan', 'notifs', 'tools', 'toolsStock', 'projectPercentage']));
    }
}
