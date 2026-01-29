<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProjectSafety;
use App\Models\DailySafetyPatrol;
use App\Models\User;
use App\Notifications\LaporanBelumDibuatNotification;

class CheckSafetyPatrolReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-safety-patrol-report';

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
        if (now()->format('H:i') < '16:00') return;

        $today = now()->toDateString();

        $projects = ProjectSafety::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        foreach ($projects as $project) {
            $exists = DailySafetyPatrol::where('project_safety_id', $project->id)
                ->whereDate('tanggal', $today)
                ->exists();

            if (!$exists) {
                $users = User::where('role', 'supervisor')->get();
                foreach ($users as $user) {
                    $user->notify(new LaporanBelumDibuatNotification($project));
                }
            }
        }
    }
}
