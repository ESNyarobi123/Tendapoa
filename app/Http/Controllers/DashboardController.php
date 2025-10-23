<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\{Job, Payment, Withdrawal, User};

class DashboardController extends Controller
{
    public function index()
    {
        $u = Auth::user();

        if ($u && $u->role === 'admin') {
            $jobsCount    = Job::count();
            $paidTotal    = Payment::where('status', 'COMPLETED')->sum('amount');
            $usersWorkers = User::where('role', 'mfanyakazi')->count();
            $usersClients = User::where('role', 'muhitaji')->count();

            return view('admin.dashboard', compact('jobsCount', 'paidTotal', 'usersWorkers', 'usersClients'));
        }

        if ($u && $u->role === 'muhitaji') {
            $posted    = Job::where('user_id', $u->id)->count();
            $completed = Job::where('user_id', $u->id)->where('status', 'completed')->count();
            $totalPaid = Payment::whereHas('job', fn($q) => $q->where('user_id', $u->id))
                                ->where('status', 'COMPLETED')
                                ->sum('amount');

            // Get payment history for muhitaji
            $paymentHistory = Payment::whereHas('job', fn($q) => $q->where('user_id', $u->id))
                ->with('job')
                ->latest()
                ->limit(10)
                ->get();

            // Get all jobs with their status and amounts
            $allJobs = Job::where('user_id', $u->id)
                ->with('acceptedWorker', 'category', 'payment')
                ->latest()
                ->limit(10)
                ->get();

            return view('muhitaji.dashboard', compact('posted', 'completed', 'totalPaid', 'paymentHistory', 'allJobs'));
        }

        // default: mfanyakazi
        $done      = Job::where('accepted_worker_id', $u->id)->where('status', 'completed')->count();
        
        // Get earnings from wallet transactions instead of just completed jobs
        $earnTotal = \App\Models\WalletTransaction::where('user_id', $u->id)
            ->where('type', 'EARN')
            ->where('amount', '>', 0)
            ->sum('amount');
            
        $withdrawn = Withdrawal::where('user_id', $u->id)->whereIn('status', ['PAID', 'PROCESSING'])->sum('amount');
        
        // Get current wallet balance
        $wallet = $u->ensureWallet();
        $available = $wallet->balance;

        // Get current jobs for dashboard
        $currentJobs = Job::with('muhitaji', 'category')
            ->where('accepted_worker_id', $u->id)
            ->whereIn('status', ['assigned', 'in_progress', 'ready_for_confirmation'])
            ->latest()
            ->limit(5)
            ->get();

        // Calculate earnings this month
        $thisMonthEarnings = Job::where('accepted_worker_id', $u->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->startOfMonth())
            ->sum('price');

        // Calculate average job completion time (in days)
        $avgCompletionTime = Job::where('accepted_worker_id', $u->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(completed_at, created_at)) as avg_days')
            ->value('avg_days') ?? 0;

        // Get earnings history (wallet transactions)
        $earningsHistory = \App\Models\WalletTransaction::where('user_id', $u->id)
            ->where('type', 'EARN')
            ->where('amount', '>', 0)
            ->latest()
            ->limit(10)
            ->get();

        // Get withdrawals history
        $withdrawalsHistory = Withdrawal::where('user_id', $u->id)
            ->latest()
            ->limit(10)
            ->get();

        // Get completed jobs for earnings breakdown
        $completedJobs = Job::where('accepted_worker_id', $u->id)
            ->where('status', 'completed')
            ->with('muhitaji', 'category')
            ->latest()
            ->limit(10)
            ->get();

        return view('mfanyakazi.dashboard', compact(
            'done', 
            'earnTotal', 
            'withdrawn', 
            'available',
            'currentJobs', 
            'thisMonthEarnings', 
            'avgCompletionTime',
            'earningsHistory',
            'withdrawalsHistory',
            'completedJobs'
        ));
    }
}
