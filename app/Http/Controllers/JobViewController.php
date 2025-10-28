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

    // API Methods
    public function apiShow(Job $job)
    {
        $job->load('muhitaji','category','comments.user');
        
        return response()->json([
            'success' => true,
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'price' => $job->price,
                'status' => $job->status,
                'lat' => $job->lat,
                'lng' => $job->lng,
                'address_text' => $job->address_text,
                'created_at' => $job->created_at,
                'published_at' => $job->published_at,
                'muhitaji' => [
                    'id' => $job->muhitaji->id,
                    'name' => $job->muhitaji->name,
                    'phone' => $job->muhitaji->phone,
                ],
                'category' => [
                    'id' => $job->category->id,
                    'name' => $job->category->name,
                    'slug' => $job->category->slug,
                ],
                'comments' => $job->comments->map(function($comment) {
                    return [
                        'id' => $comment->id,
                        'message' => $comment->message,
                        'bid_amount' => $comment->bid_amount,
                        'is_application' => $comment->is_application,
                        'created_at' => $comment->created_at,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'role' => $comment->user->role,
                        ]
                    ];
                }),
                'accepted_worker' => $job->acceptedWorker ? [
                    'id' => $job->acceptedWorker->id,
                    'name' => $job->acceptedWorker->name,
                    'phone' => $job->acceptedWorker->phone,
                ] : null,
            ],
            'status' => 'success'
        ]);
    }

    public function apiComment(Job $job, Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (mfanyakazi/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        $r->validate([
            'message'    => ['required','max:1000'],
            'bid_amount' => ['nullable','integer','min:0'],
        ]);

        $comment = JobComment::create([
            'work_order_id' => $job->id,
            'user_id'       => Auth::id(),
            'message'       => $r->input('message'),
            'is_application'=> $r->boolean('is_application'),
            'bid_amount'    => $r->input('bid_amount'),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment imetumwa kwa mafanikio!',
            'comment' => [
                'id' => $comment->id,
                'message' => $comment->message,
                'bid_amount' => $comment->bid_amount,
                'is_application' => $comment->is_application,
                'created_at' => $comment->created_at,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'role' => $comment->user->role,
                ]
            ],
            'status' => 'success'
        ]);
    }

    public function apiAccept(Job $job, JobComment $comment)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['muhitaji','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (muhitaji/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        Gate::authorize('update', $job);

        $job->update([
            'accepted_worker_id' => $comment->user_id,
            'status'             => 'assigned',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Umemchagua mfanyakazi.',
            'job' => [
                'id' => $job->id,
                'status' => $job->status,
                'accepted_worker_id' => $job->accepted_worker_id,
            ],
            'status' => 'success'
        ]);
    }

    public function apiApply(Job $job, Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (mfanyakazi/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        if ($job->status !== 'posted') {
            return response()->json([
                'error' => 'Kazi haipo wazi kwa maombi kwa sasa.',
                'status' => 'invalid_state'
            ], 422);
        }

        $r->validate([
            'message'    => ['required','max:1000'],
            'bid_amount' => ['nullable','integer','min:0'],
        ]);

        $comment = JobComment::create([
            'work_order_id' => $job->id,
            'user_id'       => Auth::id(),
            'message'       => $r->input('message'),
            'is_application'=> true,
            'bid_amount'    => $r->input('bid_amount'),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Ombi lako limewasilishwa.',
            'comment' => [
                'id' => $comment->id,
                'message' => $comment->message,
                'bid_amount' => $comment->bid_amount,
                'is_application' => $comment->is_application,
                'created_at' => $comment->created_at,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'role' => $comment->user->role,
                ]
            ],
            'status' => 'success'
        ]);
    }

    public function apiOffer(Job $job, Request $r)
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['mfanyakazi','admin'], true)) {
            return response()->json([
                'error' => 'Huna ruhusa (mfanyakazi/admin tu).',
                'status' => 'forbidden'
            ], 403);
        }

        if (!in_array($job->status, ['posted','offered'], true)) {
            return response()->json([
                'error' => 'Hali ya kazi hairuhusu kutuma offer kwa sasa.',
                'status' => 'invalid_state'
            ], 422);
        }

        $r->validate([
            'message'    => ['required','max:1000'],
            'bid_amount' => ['required','integer','min:0'],
        ]);

        $comment = JobComment::create([
            'work_order_id' => $job->id,
            'user_id'       => Auth::id(),
            'message'       => $r->input('message'),
            'is_application'=> false,
            'bid_amount'    => $r->input('bid_amount'),
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Offer yako imetumwa.',
            'comment' => [
                'id' => $comment->id,
                'message' => $comment->message,
                'bid_amount' => $comment->bid_amount,
                'is_application' => $comment->is_application,
                'created_at' => $comment->created_at,
                'user' => [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'role' => $comment->user->role,
                ]
            ],
            'status' => 'success'
        ]);
    }
}
