<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class MyJobsController extends Controller
{
    public function index()
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['muhitaji','admin'])) abort(403);

        $jobs = Job::withCount('comments')
            ->with('acceptedWorker')
            ->where('user_id', $u->id)
            ->latest()->paginate(12);

        return view('muhitaji.my_jobs', compact('jobs'));
    }

    public function apiIndex()
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['muhitaji','admin'])) {
            return response()->json([
                'error' => 'Huna ruhusa ya kupata kazi hizi.',
                'status' => 'forbidden'
            ], 403);
        }

        $jobs = Job::withCount('comments')
            ->with(['acceptedWorker', 'category', 'payment'])
            ->where('user_id', $u->id)
            ->latest()
            ->paginate(12);

        return response()->json([
            'jobs' => $jobs->items(),
            'pagination' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
                'has_more' => $jobs->hasMorePages()
            ],
            'status' => 'success'
        ]);
    }

}
