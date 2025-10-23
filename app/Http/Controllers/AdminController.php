<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\PrivateMessage;
use App\Models\Payment;
use App\Models\Withdrawal;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Admin Dashboard - Overview
     * Note: Admin middleware is applied at route level
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'muhitaji_count' => User::where('role', 'muhitaji')->count(),
            'mfanyakazi_count' => User::where('role', 'mfanyakazi')->count(),
            'total_jobs' => Job::count(),
            'active_jobs' => Job::whereIn('status', ['posted', 'assigned', 'in_progress'])->count(),
            'completed_jobs' => Job::where('status', 'completed')->count(),
            'total_messages' => PrivateMessage::count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
        ];

        // Recent activities
        $recentJobs = Job::with(['muhitaji', 'acceptedWorker'])
            ->latest()
            ->limit(10)
            ->get();

        $recentUsers = User::latest()->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentJobs', 'recentUsers'));
    }

    /**
     * View all users
     */
    public function users(Request $request)
    {
        $query = User::with('wallet');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * View specific user details with FULL ACCESS and ALL ACTIVITIES
     */
    public function userDetails(User $user)
    {
        try {
            // Debug: Log the user ID being passed
            \Log::info('Admin userDetails - Requested User ID: ' . $user->id);
            \Log::info('Admin userDetails - Requested User Name: ' . $user->name);
            \Log::info('Admin userDetails - Requested User Email: ' . $user->email);
            
            // Force refresh the user from database
            $user = User::find($user->id);
            if (!$user) {
                abort(404, 'User not found');
            }
            
            // Load all user relationships
            $user->load([
                'jobs' => fn($q) => $q->with(['acceptedWorker', 'category'])->latest(),
                'assignedJobs' => fn($q) => $q->with(['muhitaji', 'category'])->latest(),
                'wallet',
                'withdrawals' => fn($q) => $q->latest(),
                'sentMessages' => fn($q) => $q->with(['receiver', 'job'])->latest()->limit(50),
                'receivedMessages' => fn($q) => $q->with(['sender', 'job'])->latest()->limit(50),
            ]);

            // Debug: Log user data
            \Log::info('Admin userDetails - User ID: ' . $user->id);
            \Log::info('Admin userDetails - User loaded: ' . $user->name);
            \Log::info('Admin userDetails - User email: ' . $user->email);
            \Log::info('Admin userDetails - Jobs count: ' . $user->jobs->count());
            \Log::info('Admin userDetails - Assigned jobs count: ' . $user->assignedJobs->count());

        // Get comprehensive user statistics
        $stats = [
            'jobs_posted' => $user->jobs()->count(),
            'jobs_assigned' => $user->assignedJobs()->count(),
            'jobs_completed' => $user->assignedJobs()->where('status', 'completed')->count(),
            'jobs_in_progress' => $user->assignedJobs()->where('status', 'in_progress')->count(),
            'jobs_cancelled' => $user->jobs()->where('status', 'cancelled')->count(),
            'wallet_balance' => $user->wallet->balance ?? 0,
            'total_earned' => WalletTransaction::where('user_id', $user->id)
                ->where('type', 'credit')
                ->sum('amount'),
            'total_spent' => WalletTransaction::where('user_id', $user->id)
                ->where('type', 'debit')
                ->sum('amount'),
            'total_withdrawn' => Withdrawal::where('user_id', $user->id)
                ->where('status', 'paid')
                ->sum('amount'),
            'pending_withdrawals' => Withdrawal::where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount'),
            'messages_sent' => PrivateMessage::where('sender_id', $user->id)->count(),
            'messages_received' => PrivateMessage::where('receiver_id', $user->id)->count(),
            'total_conversations' => PrivateMessage::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->distinct('work_order_id')
                ->count('work_order_id'),
        ];

        // Get ALL activities timeline
        $activities = collect();

        // Jobs posted
        foreach ($user->jobs as $job) {
            $activities->push([
                'type' => 'job_posted',
                'icon' => '📝',
                'color' => 'blue',
                'title' => 'Posted Job',
                'description' => $job->title,
                'details' => "Budget: Tsh " . number_format($job->budget ?? $job->amount) . " | Status: " . ucfirst($job->status),
                'timestamp' => $job->created_at,
                'link' => route('admin.job.details', $job),
                'data' => $job,
            ]);
        }

        // Jobs assigned (as worker)
        foreach ($user->assignedJobs as $job) {
            $activities->push([
                'type' => 'job_assigned',
                'icon' => '✅',
                'color' => 'green',
                'title' => 'Assigned to Job',
                'description' => $job->title,
                'details' => "By: " . $job->muhitaji->name . " | Amount: Tsh " . number_format($job->amount),
                'timestamp' => $job->accepted_at ?? $job->updated_at,
                'link' => route('admin.job.details', $job),
                'data' => $job,
            ]);
        }

        // Messages sent
        foreach ($user->sentMessages as $message) {
            $activities->push([
                'type' => 'message_sent',
                'icon' => '💬',
                'color' => 'purple',
                'title' => 'Sent Message',
                'description' => "To: " . $message->receiver->name,
                'details' => substr($message->message, 0, 100) . (strlen($message->message) > 100 ? '...' : ''),
                'timestamp' => $message->created_at,
                'link' => route('admin.chat.view', $message->work_order_id),
                'data' => $message,
            ]);
        }

        // Messages received
        foreach ($user->receivedMessages as $message) {
            $activities->push([
                'type' => 'message_received',
                'icon' => '📨',
                'color' => 'indigo',
                'title' => 'Received Message',
                'description' => "From: " . $message->sender->name,
                'details' => substr($message->message, 0, 100) . (strlen($message->message) > 100 ? '...' : ''),
                'timestamp' => $message->created_at,
                'link' => route('admin.chat.view', $message->work_order_id),
                'data' => $message,
            ]);
        }

        // Withdrawals
        foreach ($user->withdrawals as $withdrawal) {
            $activities->push([
                'type' => 'withdrawal',
                'icon' => '💰',
                'color' => 'orange',
                'title' => 'Withdrawal Request',
                'description' => "Amount: Tsh " . number_format($withdrawal->amount),
                'details' => "Status: " . ucfirst($withdrawal->status) . " | Method: " . ($withdrawal->method ?? 'N/A'),
                'timestamp' => $withdrawal->created_at,
                'link' => null,
                'data' => $withdrawal,
            ]);
        }

        // Sort all activities by timestamp (newest first)
        $activities = $activities->sortByDesc('timestamp')->values();

        // Recent wallet transactions
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get();

        // Get user's active conversations
        $conversations = PrivateMessage::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->select('work_order_id')
            ->distinct()
            ->with(['job' => fn($q) => $q->with(['muhitaji', 'acceptedWorker'])])
            ->get();

            return view('admin.user-details', compact('user', 'stats', 'transactions', 'activities', 'conversations'));
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Admin userDetails error: ' . $e->getMessage());
            
            // Return basic user data if there's an error
            $user->load(['wallet']);
            $stats = [
                'jobs_posted' => 0,
                'jobs_assigned' => 0,
                'jobs_completed' => 0,
                'jobs_in_progress' => 0,
                'jobs_cancelled' => 0,
                'wallet_balance' => $user->wallet->balance ?? 0,
                'total_earned' => 0,
                'total_spent' => 0,
                'total_withdrawn' => 0,
                'pending_withdrawals' => 0,
                'messages_sent' => 0,
                'messages_received' => 0,
                'total_conversations' => 0,
            ];
            $activities = collect();
            $conversations = collect();
            $transactions = collect();
            
            return view('admin.user-details', compact('user', 'stats', 'transactions', 'activities', 'conversations'))
                ->with('error', 'Error loading user details: ' . $e->getMessage());
        }
    }

    /**
     * View all jobs
     */
    public function jobs(Request $request)
    {
        $query = Job::with(['muhitaji', 'acceptedWorker', 'category']);

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $jobs = $query->latest()->paginate(20);

        return view('admin.jobs', compact('jobs'));
    }

    /**
     * View specific job details
     */
    public function jobDetails(Job $job)
    {
        $job->load([
            'muhitaji',
            'acceptedWorker',
            'category',
            'comments.user',
            'privateMessages.sender',
            'privateMessages.receiver',
            'payment',
        ]);

        return view('admin.job-details', compact('job'));
    }

    /**
     * View all private messages/chats
     */
    public function allChats(Request $request)
    {
        // Get all conversations
        $conversations = DB::table('private_messages')
            ->select(
                'work_order_id',
                DB::raw('MAX(created_at) as last_message_at'),
                DB::raw('COUNT(*) as message_count')
            )
            ->groupBy('work_order_id')
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        // Load job details
        $jobIds = $conversations->pluck('work_order_id');
        $jobs = Job::with(['muhitaji', 'acceptedWorker'])
            ->whereIn('id', $jobIds)
            ->get()
            ->keyBy('id');

        // Merge data
        $conversations->getCollection()->transform(function($conv) use ($jobs) {
            $job = $jobs->get($conv->work_order_id);
            $conv->job = $job;
            return $conv;
        });

        return view('admin.chats', compact('conversations'));
    }

    /**
     * View specific chat/conversation
     */
    public function viewChat(Job $job)
    {
        $messages = PrivateMessage::forJob($job->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        $job->load(['muhitaji', 'acceptedWorker']);

        return view('admin.chat-details', compact('job', 'messages'));
    }

    /**
     * View user's dashboard as admin (impersonate view)
     */
    public function viewUserDashboard(User $user)
    {
        // Get user's dashboard data
        $role = $user->role;

        if ($role === 'muhitaji') {
            $jobs = Job::where('user_id', $user->id)
                ->with(['acceptedWorker', 'category'])
                ->latest()
                ->paginate(10);

            $stats = [
                'total_jobs' => Job::where('user_id', $user->id)->count(),
                'active_jobs' => Job::where('user_id', $user->id)
                    ->whereIn('status', ['posted', 'assigned', 'in_progress'])
                    ->count(),
                'completed_jobs' => Job::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
            ];

            return view('admin.user-dashboard-muhitaji', compact('user', 'jobs', 'stats'));
        }

        if ($role === 'mfanyakazi') {
            $assignedJobs = Job::where('accepted_worker_id', $user->id)
                ->with(['muhitaji', 'category'])
                ->latest()
                ->paginate(10);

            $wallet = $user->wallet;
            $balance = $wallet ? $wallet->balance : 0;

            $stats = [
                'total_jobs' => Job::where('accepted_worker_id', $user->id)->count(),
                'completed_jobs' => Job::where('accepted_worker_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
                'wallet_balance' => $balance,
            ];

            return view('admin.user-dashboard-mfanyakazi', compact('user', 'assignedJobs', 'stats', 'balance'));
        }

        abort(404, 'Dashboard haijulikani kwa role hii.');
    }

    /**
     * Monitor user activity
     */
    public function monitorUser(User $user)
    {
        // Get recent activities
        $recentJobs = Job::where('user_id', $user->id)
            ->orWhere('accepted_worker_id', $user->id)
            ->with(['muhitaji', 'acceptedWorker'])
            ->latest()
            ->limit(20)
            ->get();

        $recentMessages = PrivateMessage::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'job'])
            ->latest()
            ->limit(50)
            ->get();

        $recentTransactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        // Activity timeline
        $activities = collect();

        // Add jobs
        foreach ($recentJobs as $job) {
            $activities->push([
                'type' => 'job',
                'data' => $job,
                'timestamp' => $job->created_at,
            ]);
        }

        // Add messages
        foreach ($recentMessages as $message) {
            $activities->push([
                'type' => 'message',
                'data' => $message,
                'timestamp' => $message->created_at,
            ]);
        }

        // Add transactions
        foreach ($recentTransactions as $transaction) {
            $activities->push([
                'type' => 'transaction',
                'data' => $transaction,
                'timestamp' => $transaction->created_at,
            ]);
        }

        // Sort by timestamp
        $activities = $activities->sortByDesc('timestamp')->values();

        return view('admin.user-monitor', compact('user', 'activities'));
    }

    /**
     * System analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // User growth
        $userGrowth = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Job statistics
        $jobStats = Job::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue
        $revenue = Payment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top workers
        $topWorkers = User::where('role', 'mfanyakazi')
            ->withCount(['assignedJobs as completed_jobs' => function($q) {
                $q->where('status', 'completed');
            }])
            ->orderBy('completed_jobs', 'desc')
            ->limit(10)
            ->get();

        return view('admin.analytics', compact(
            'userGrowth',
            'jobStats',
            'revenue',
            'topWorkers',
            'period'
        ));
    }

    /**
     * ADMIN IMPERSONATION - Login as any user
     */
    public function impersonate(User $user)
    {
        // Store original admin ID
        Session::put('admin_id', Auth::id());
        
        // Login as the target user
        Auth::login($user);
        
        return redirect()->route('dashboard')->with('success', 
            "Umeingia kama {$user->name}. <a href='" . route('admin.stop-impersonate') . "' class='text-red-600 font-bold'>Rudi kwa Admin</a>"
        );
    }

    /**
     * Stop impersonation - Return to admin
     */
    public function stopImpersonate()
    {
        $adminId = Session::get('admin_id');
        
        if ($adminId) {
            $admin = User::find($adminId);
            Auth::login($admin);
            Session::forget('admin_id');
            
            return redirect()->route('admin.dashboard')->with('success', 'Umerudi kwa Admin Dashboard');
        }
        
        return redirect()->route('admin.dashboard')->with('error', 'Hakuna admin session');
    }

    /**
     * ADMIN FULL CONTROL - Edit any user
     */
    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    /**
     * ADMIN FULL CONTROL - Update any user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:muhitaji,mfanyakazi,admin',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.user.details', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * ADMIN FULL CONTROL - Delete any user
     */
    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Huwezi kujifuta!');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "User {$userName} deleted successfully!");
    }

    /**
     * ADMIN FULL CONTROL - Suspend/Activate user
     */
    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'suspended';
        
        return back()->with('success', "User {$user->name} has been {$status}!");
    }

    /**
     * ADMIN FULL CONTROL - View all system logs
     */
    public function systemLogs()
    {
        $logs = [];
        
        // Get recent activities
        $activities = collect();
        
        // Recent jobs
        $recentJobs = Job::with(['muhitaji', 'acceptedWorker'])
            ->latest()
            ->limit(50)
            ->get();
            
        foreach ($recentJobs as $job) {
            $activities->push([
                'type' => 'job_created',
                'user' => $job->muhitaji,
                'description' => "Created job: {$job->title}",
                'timestamp' => $job->created_at,
                'data' => $job
            ]);
        }

        // Recent messages
        $recentMessages = PrivateMessage::with(['sender', 'receiver', 'job'])
            ->latest()
            ->limit(50)
            ->get();
            
        foreach ($recentMessages as $message) {
            $activities->push([
                'type' => 'message_sent',
                'user' => $message->sender,
                'description' => "Sent message to {$message->receiver->name}",
                'timestamp' => $message->created_at,
                'data' => $message
            ]);
        }

        // Recent payments
        $recentPayments = Payment::with(['job.muhitaji'])
            ->latest()
            ->limit(50)
            ->get();
            
        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment_made',
                'user' => $payment->job->muhitaji ?? null,
                'description' => "Made payment: Tsh " . number_format($payment->amount) . " for job: " . ($payment->job->title ?? 'Unknown'),
                'timestamp' => $payment->created_at,
                'data' => $payment
            ]);
        }

        // Sort by timestamp
        $activities = $activities->sortByDesc('timestamp')->values();

        return view('admin.system-logs', compact('activities'));
    }

    /**
     * ADMIN FULL CONTROL - System settings
     */
    public function systemSettings()
    {
        return view('admin.system-settings');
    }

    /**
     * ADMIN FULL CONTROL - Update system settings
     */
    public function updateSystemSettings(Request $request)
    {
        // Here you can add system-wide settings
        // For now, just return success
        return back()->with('success', 'System settings updated!');
    }

    /**
     * ADMIN FULL CONTROL - Force complete any job
     */
    public function forceCompleteJob(Job $job)
    {
        $job->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', "Job '{$job->title}' has been force completed!");
    }

    /**
     * ADMIN FULL CONTROL - Force cancel any job
     */
    public function forceCancelJob(Job $job)
    {
        $job->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', "Job '{$job->title}' has been force cancelled!");
    }

    /**
     * ADMIN FULL CONTROL - Send message to any user
     */
    public function sendMessageToUser(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Create a system message (you can create a system_messages table)
        // For now, we'll just return success
        return back()->with('success', "Message sent to {$user->name}!");
    }
}

