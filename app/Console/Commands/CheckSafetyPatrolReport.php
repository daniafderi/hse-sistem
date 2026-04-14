<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\ProjectSafety;
use App\Models\Notification;

class CheckSafetyPatrolReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:check-safety';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // hanya jalan setelah jam 16
        if ($now->format('H:i') >= '16:00') {

            $projects = ProjectSafety::whereDate('tanggal_mulai', '<=', $now)
                ->whereDate('tanggal_selesai', '>=', $now)
                ->whereDoesntHave('dailySafetyPatrol', function ($q) use ($now) {
                    $q->whereDate('tanggal', $now);
                })
                ->get();

            foreach ($projects as $project) {

                Notification::create([
                    'project_id' => $project->id,
                    'message' => 'Laporan safety patrol hari ini belum dibuat'
                ]);
            }
        }
    }
}
