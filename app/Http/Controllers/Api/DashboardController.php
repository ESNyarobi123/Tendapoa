<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class DashboardController extends Controller
{
    public function updates()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get new jobs assigned to user in the last 30 seconds
        $newJobs = Job::where('accepted_worker_id', $user->id)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->get();

        // Get jobs that were completed in the last 30 seconds
        $completedJobs = Job::where('accepted_worker_id', $user->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subSeconds(30))
            ->get();

        return response()->json([
            'newJobs' => $newJobs->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'amount' => $job->amount,
                    'status' => $job->status
                ];
            }),
            'completedJobs' => $completedJobs->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'amount' => $job->amount,
                    'completed_at' => $job->completed_at
                ];
            }),
            'timestamp' => now()->toISOString()
        ]);
    }
}
