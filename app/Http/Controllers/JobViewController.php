<?php

namespace App\Http\Controllers;

use App\Models\{Job, JobComment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobViewController extends Controller
{
    public function show(Job $job)
    {
        $job->load('muhitaji','category','comments.user');
        return view('jobs.show', compact('job'));
    }

    public function comment(Job $job, Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            abort(403, 'Huna ruhusa (mfanyakazi/admin tu).');
        }

        $r->validate([
            'message'    => ['required','max:1000'],
            'bid_amount' => ['nullable','integer','min:0'],
        ]);

        JobComment::create([
            'work_order_id' => $job->id,
            'user_id'       => Auth::id(),
            'message'       => $r->input('message'),
            'is_application'=> $r->boolean('is_application'),
            'bid_amount'    => $r->input('bid_amount'),
        ]);

        return back();
    }

    public function accept(Job $job, JobComment $comment)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['muhitaji','admin'], true)) {
            abort(403, 'Huna ruhusa (muhitaji/admin tu).');
        }

        Gate::authorize('update', $job);

        $job->update([
            'accepted_worker_id' => $comment->user_id,
            'status'             => 'assigned',
        ]);

        return back()->with('status', 'Umemchagua mfanyakazi.');
    }
}
