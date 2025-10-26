<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, DashboardController, JobController, PaymentController,
    FeedController, JobViewController, AuthController,
    MyJobsController, WorkerActionsController, WithdrawalController, AdminController,
    ChatController
};
use App\Http\Controllers\Admin\WithdrawalAdminController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;

// Landing
Route::get('/', [HomeController::class,'index'])->name('home');

// Guest auth pages
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class,'showRegister'])->name('register');
    Route::post('/register', [AuthController::class,'register'])->name('register.post');
    Route::get('/login',    [AuthController::class,'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class,'login'])->name('login.post');
});

// Logout
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth')->name('logout');

// Protected pages
Route::middleware('auth')->group(function () {
    // Dashboard (HTML)
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    
    // Muhitaji: create/update jobs
    Route::get('/jobs/create',        [JobController::class,'create'])->name('jobs.create');
    Route::post('/jobs',              [JobController::class,'store'])->name('jobs.store');
    Route::get('/jobs/{job}/wait',    [JobController::class,'wait'])->name('jobs.pay.wait');
    Route::get('/jobs/{job}/edit',    [JobController::class,'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}',         [JobController::class,'update'])->name('jobs.update');

    // Mfanyakazi: create + pay (HTML)
    Route::get('/jobs/create-mfanyakazi', [JobController::class,'createMfanyakazi'])->name('jobs.create-mfanyakazi');
    Route::post('/jobs-mfanyakazi',       [JobController::class,'storeMfanyakazi'])->name('jobs.store-mfanyakazi');

    // Generic poll (controller)
    Route::get('/jobs/{job}/poll', [PaymentController::class,'poll'])->name('jobs.pay.poll');

    // Feed + job view + comments
    Route::get('/feed',        [FeedController::class,'index'])->name('feed');
    Route::get('/jobs/{job}',  [JobViewController::class,'show'])->name('jobs.show');
    Route::post('/jobs/{job}/comment', [JobViewController::class,'comment'])->name('jobs.comment');

    // Muhitaji: accept worker
    Route::post('/jobs/{job}/accept/{comment}', [JobViewController::class,'accept'])->name('jobs.accept');

    // My jobs (HTML list)
    Route::get('/my/jobs', [MyJobsController::class,'index'])->name('my.jobs');

    // Worker assigned + actions
    Route::get('/mfanyakazi/assigned',               [WorkerActionsController::class,'assigned'])->name('mfanyakazi.assigned');
    Route::post('/mfanyakazi/jobs/{job}/accept',     [WorkerActionsController::class,'accept'])->name('mfanyakazi.jobs.accept');
    Route::post('/mfanyakazi/jobs/{job}/decline',    [WorkerActionsController::class,'decline'])->name('mfanyakazi.jobs.decline');
    Route::post('/mfanyakazi/jobs/{job}/complete',   [WorkerActionsController::class,'complete'])->name('mfanyakazi.jobs.complete');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{job}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{job}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{job}/poll', [ChatController::class, 'poll'])->name('chat.poll');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread');

    // Withdrawals
    Route::get('/withdraw',          [WithdrawalController::class,'requestForm'])->name('withdraw.form');
    Route::post('/withdraw/submit',  [WithdrawalController::class,'submit'])->name('withdraw.submit');

    // Admin (HTML dashboard)
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class,'dashboard'])->name('admin.dashboard');

        // Users
        Route::get('/users',                     [AdminController::class,'users'])->name('admin.users');
        Route::get('/users/{user}',              [AdminController::class,'userDetails'])->name('admin.user.details');
        Route::get('/users/{user}/dashboard',    [AdminController::class,'viewUserDashboard'])->name('admin.user.dashboard');
        Route::get('/users/{user}/monitor',      [AdminController::class,'monitorUser'])->name('admin.user.monitor');
        Route::get('/users/{user}/edit',         [AdminController::class,'editUser'])->name('admin.user.edit');
        Route::put('/users/{user}',              [AdminController::class,'updateUser'])->name('admin.user.update');
        Route::delete('/users/{user}',           [AdminController::class,'deleteUser'])->name('admin.user.delete');
        Route::post('/users/{user}/toggle-status',[AdminController::class,'toggleUserStatus'])->name('admin.user.toggle-status');
        Route::post('/users/{user}/send-message',[AdminController::class,'sendMessageToUser'])->name('admin.user.send-message');

        // Jobs
        Route::get('/jobs',              [AdminController::class,'jobs'])->name('admin.jobs');
        Route::get('/jobs/{job}',        [AdminController::class,'jobDetails'])->name('admin.job.details');
        Route::post('/jobs/{job}/force-complete', [AdminController::class,'forceCompleteJob'])->name('admin.job.force-complete');
        Route::post('/jobs/{job}/force-cancel',   [AdminController::class,'forceCancelJob'])->name('admin.job.force-cancel');

        // Chats
        Route::get('/chats',        [AdminController::class,'allChats'])->name('admin.chats');
        Route::get('/chats/{job}',  [AdminController::class,'viewChat'])->name('admin.chat.view');

        // Analytics
        Route::get('/analytics',    [AdminController::class,'analytics'])->name('admin.analytics');

        // System
        Route::get('/system-logs',      [AdminController::class,'systemLogs'])->name('admin.system-logs');
        Route::get('/system-settings',  [AdminController::class,'systemSettings'])->name('admin.system-settings');
        Route::post('/system-settings', [AdminController::class,'updateSystemSettings'])->name('admin.system-settings.update');

        // Withdrawals (Admin)
        Route::get('/withdrawals',                     [WithdrawalAdminController::class,'index'])->name('admin.withdrawals');
        Route::post('/withdrawals/{withdrawal}/paid',  [WithdrawalAdminController::class,'markPaid'])->name('admin.withdrawals.paid');
        Route::post('/withdrawals/{withdrawal}/reject',[WithdrawalAdminController::class,'reject'])->name('admin.withdrawals.reject');

        // Legacy: Completed jobs leaderboard
        Route::get('/completed-jobs', function () {
            $workers = \App\Models\User::where('role','mfanyakazi')
                ->with(['assignedJobs' => function($q){ $q->where('status','completed'); }])
                ->withCount(['assignedJobs as completed_jobs' => function($q){ $q->where('status','completed'); }])
                ->orderBy('completed_jobs','desc')
                ->paginate(20);
            return view('admin.completed-jobs', compact('workers'));
        })->name('admin.completed-jobs');
    });
});

// ZenoPay Webhook (public, JSON response inside controller)
// Used by API layer via route('zeno.webhook')
Route::post('/payment/zeno/webhook', [PaymentController::class,'webhook'])->name('zeno.webhook');

// API-style dashboard updates (for SSE/AJAX from blade), protected
Route::get('/api/dashboard-updates', [ApiDashboardController::class,'updates'])->middleware('auth');

// Debug endpoint (protected) — helpful during development
Route::get('/debug/payment/{job}', function(\App\Models\Job $job) {
    $worker = $job->acceptedWorker;
    if (!$worker) return response()->json(['error'=>'No worker found']);

    $wallet = $worker->ensureWallet();
    $tx = \App\Models\WalletTransaction::where('user_id',$worker->id)->latest()->take(5)->get();

    return response()->json([
        'job_id'             => $job->id,
        'job_status'         => $job->status,
        'job_amount'         => $job->amount,
        'completion_code'    => $job->completion_code,
        'worker_id'          => $worker->id,
        'worker_name'        => $worker->name,
        'wallet_balance'     => $wallet->balance,
        'recent_transactions'=> $tx
    ]);
})->middleware('auth');