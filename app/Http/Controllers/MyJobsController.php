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

}
