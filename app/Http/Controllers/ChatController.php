<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\PrivateMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display chat interface for a specific job
     */
    public function show(Job $job, Request $request)
    {
        $user = Auth::user();

        // Check if user is muhitaji (job owner)
        $isMuhitaji = $job->user_id === $user->id;
        
        // Check if user is mfanyakazi who has commented on this job
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        
        // Check if user is accepted worker
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        // Allow access if: muhitaji, commented mfanyakazi, or accepted worker
        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            abort(403, 'Huna ruhusa ya kuona mazungumzo haya. Tuma comment kwanza.');
        }

        // Determine the other user for the conversation
        if ($isMuhitaji) {
            // If muhitaji, check if there's a specific worker_id in request
            $workerId = $request->get('worker_id');
            if ($workerId) {
                $otherUser = User::find($workerId);
            } else {
                // Default to accepted worker if exists
                $otherUser = $job->acceptedWorker;
            }
        } else {
            // If mfanyakazi, other user is muhitaji
            $otherUser = $job->muhitaji;
        }

        if (!$otherUser) {
            abort(404, 'Mtumiaji mwingine hajapatikana.');
        }

        // Get messages for this job between these two users
        $messages = PrivateMessage::forJob($job->id)
            ->betweenUsers($user->id, $otherUser->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        PrivateMessage::forJob($job->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Get unread count for other conversations
        $unreadCount = PrivateMessage::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('chat.show', compact('job', 'messages', 'otherUser', 'unreadCount'));
    }

    /**
     * Send a message
     */
    public function send(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if user is muhitaji (job owner)
        $isMuhitaji = $job->user_id === $user->id;
        
        // Check if user is mfanyakazi who has commented on this job
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        
        // Check if user is accepted worker
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        // Allow sending if: muhitaji, commented mfanyakazi, or accepted worker
        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            abort(403, 'Huna ruhusa ya kutuma ujumbe. Tuma comment kwanza.');
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'receiver_id' => 'nullable|exists:users,id', // Allow specifying receiver
        ]);

        // Determine receiver
        if ($isMuhitaji) {
            // Muhitaji can specify which mfanyakazi to send to
            $receiverId = $request->input('receiver_id') ?? $job->accepted_worker_id;
        } else {
            // Mfanyakazi always sends to muhitaji
            $receiverId = $job->user_id;
        }

        if (!$receiverId) {
            return back()->with('error', 'Mpokeaji hajapatikana.');
        }

        $message = PrivateMessage::create([
            'work_order_id' => $job->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return redirect()->route('chat.show', $job)->with('success', 'Ujumbe umetumwa.');
    }

    /**
     * List all conversations for current user
     */
    public function index()
    {
        $user = Auth::user();

        // Get unique conversations grouped by job
        $conversations = DB::table('private_messages')
            ->select(
                'work_order_id',
                DB::raw('MAX(created_at) as last_message_at'),
                DB::raw("COUNT(CASE WHEN receiver_id = {$user->id} AND is_read = 0 THEN 1 END) as unread_count")
            )
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->groupBy('work_order_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Load job details
        $jobIds = $conversations->pluck('work_order_id');
        $jobs = Job::with(['muhitaji', 'acceptedWorker', 'category'])
            ->whereIn('id', $jobIds)
            ->get()
            ->keyBy('id');

        // Merge conversation data with jobs
        $conversations = $conversations->map(function($conv) use ($jobs, $user) {
            $job = $jobs->get($conv->work_order_id);
            if (!$job) return null;

            // Determine the other user
            $otherUser = $user->id === $job->user_id 
                ? $job->acceptedWorker 
                : $job->muhitaji;

            return (object)[
                'job' => $job,
                'other_user' => $otherUser,
                'last_message_at' => $conv->last_message_at,
                'unread_count' => $conv->unread_count,
            ];
        })->filter();

        return view('chat.index', compact('conversations'));
    }

    /**
     * Get new messages (AJAX polling)
     */
    public function poll(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if user is muhitaji or has commented
        $isMuhitaji = $job->user_id === $user->id;
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            abort(403);
        }

        $lastId = $request->get('last_id', 0);
        $otherUserId = $request->get('other_user_id');
        
        if (!$otherUserId) {
            $otherUserId = $user->id === $job->user_id 
                ? $job->accepted_worker_id 
                : $job->user_id;
        }

        $newMessages = PrivateMessage::forJob($job->id)
            ->betweenUsers($user->id, $otherUserId)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        PrivateMessage::forJob($job->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $newMessages,
            'count' => $newMessages->count(),
        ]);
    }

    /**
     * Get unread count for current user
     */
    public function unreadCount()
    {
        $count = PrivateMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    // API Methods
    public function apiIndex()
    {
        $user = Auth::user();

        // Get unique conversations grouped by job
        $conversations = DB::table('private_messages')
            ->select(
                'work_order_id',
                DB::raw('MAX(created_at) as last_message_at'),
                DB::raw("COUNT(CASE WHEN receiver_id = {$user->id} AND is_read = 0 THEN 1 END) as unread_count")
            )
            ->where(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->groupBy('work_order_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Load job details
        $jobIds = $conversations->pluck('work_order_id');
        $jobs = Job::with(['muhitaji', 'acceptedWorker', 'category'])
            ->whereIn('id', $jobIds)
            ->get()
            ->keyBy('id');

        // Merge conversation data with jobs
        $conversations = $conversations->map(function($conv) use ($jobs, $user) {
            $job = $jobs->get($conv->work_order_id);
            if (!$job) return null;

            // Determine the other user
            $otherUser = $user->id === $job->user_id 
                ? $job->acceptedWorker 
                : $job->muhitaji;

            return [
                'job' => [
                    'id' => $job->id,
                    'title' => $job->title,
                    'status' => $job->status,
                    'price' => $job->price,
                    'category' => [
                        'id' => $job->category->id,
                        'name' => $job->category->name,
                    ],
                ],
                'other_user' => $otherUser ? [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'role' => $otherUser->role,
                ] : null,
                'last_message_at' => $conv->last_message_at,
                'unread_count' => $conv->unread_count,
            ];
        })->filter();

        return response()->json([
            'conversations' => $conversations->values(),
            'status' => 'success'
        ]);
    }

    public function apiShow(Job $job, Request $request)
    {
        $user = Auth::user();

        // Check if user is muhitaji (job owner)
        $isMuhitaji = $job->user_id === $user->id;
        
        // Check if user is mfanyakazi who has commented on this job
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        
        // Check if user is accepted worker
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        // Allow access if: muhitaji, commented mfanyakazi, or accepted worker
        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            return response()->json([
                'error' => 'Huna ruhusa ya kuona mazungumzo haya. Tuma comment kwanza.',
                'status' => 'forbidden'
            ], 403);
        }

        // Determine the other user for the conversation
        if ($isMuhitaji) {
            // If muhitaji, check if there's a specific worker_id in request
            $workerId = $request->get('worker_id');
            if ($workerId) {
                $otherUser = User::find($workerId);
            } else {
                // Default to accepted worker if exists
                $otherUser = $job->acceptedWorker;
            }
        } else {
            // If mfanyakazi, other user is muhitaji
            $otherUser = $job->muhitaji;
        }

        if (!$otherUser) {
            return response()->json([
                'error' => 'Mtumiaji mwingine hajapatikana.',
                'status' => 'not_found'
            ], 404);
        }

        // Get messages for this job between these two users
        $messages = PrivateMessage::forJob($job->id)
            ->betweenUsers($user->id, $otherUser->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        PrivateMessage::forJob($job->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'status' => $job->status,
                'price' => $job->price,
            ],
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'role' => $otherUser->role,
            ],
            'messages' => $messages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'role' => $message->sender->role,
                    ],
                ];
            }),
            'status' => 'success'
        ]);
    }

    public function apiSend(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if user is muhitaji (job owner)
        $isMuhitaji = $job->user_id === $user->id;
        
        // Check if user is mfanyakazi who has commented on this job
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        
        // Check if user is accepted worker
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        // Allow sending if: muhitaji, commented mfanyakazi, or accepted worker
        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            return response()->json([
                'error' => 'Huna ruhusa ya kutuma ujumbe. Tuma comment kwanza.',
                'status' => 'forbidden'
            ], 403);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
            'receiver_id' => 'nullable|exists:users,id',
        ]);

        // Determine receiver
        if ($isMuhitaji) {
            // Muhitaji can specify which mfanyakazi to send to
            $receiverId = $request->input('receiver_id') ?? $job->accepted_worker_id;
        } else {
            // Mfanyakazi always sends to muhitaji
            $receiverId = $job->user_id;
        }

        if (!$receiverId) {
            return response()->json([
                'error' => 'Mpokeaji hajapatikana.',
                'status' => 'not_found'
            ], 404);
        }

        $message = PrivateMessage::create([
            'work_order_id' => $job->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                    'role' => $message->sender->role,
                ],
            ],
            'status' => 'success'
        ]);
    }

    public function apiPoll(Request $request, Job $job)
    {
        $user = Auth::user();

        // Check if user is muhitaji or has commented
        $isMuhitaji = $job->user_id === $user->id;
        $hasCommented = $job->comments()->where('user_id', $user->id)->exists();
        $isAcceptedWorker = $job->accepted_worker_id === $user->id;

        if (!$isMuhitaji && !$hasCommented && !$isAcceptedWorker) {
            return response()->json([
                'error' => 'Huna ruhusa.',
                'status' => 'forbidden'
            ], 403);
        }

        $lastId = $request->get('last_id', 0);
        $otherUserId = $request->get('other_user_id');
        
        if (!$otherUserId) {
            $otherUserId = $user->id === $job->user_id 
                ? $job->accepted_worker_id 
                : $job->user_id;
        }

        $newMessages = PrivateMessage::forJob($job->id)
            ->betweenUsers($user->id, $otherUserId)
            ->where('id', '>', $lastId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        PrivateMessage::forJob($job->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => $newMessages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'role' => $message->sender->role,
                    ],
                ];
            }),
            'count' => $newMessages->count(),
            'status' => 'success'
        ]);
    }

    public function apiUnreadCount()
    {
        $count = PrivateMessage::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'count' => $count,
            'status' => 'success'
        ]);
    }
}

