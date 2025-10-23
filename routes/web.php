<?php

use App\Http\Controllers\{ MyJobsController, WorkerActionsController, WithdrawalController };
use App\Http\Controllers\Admin\WithdrawalAdminController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, DashboardController,
    JobController, PaymentController, FeedController, JobViewController,
    AuthController
};

// HOME / LANDING
Route::get('/', [HomeController::class,'index'])->name('home');

// GUEST: auth pages
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class,'showRegister'])->name('register');
    Route::post('/register', [AuthController::class,'register'])->name('register.post');
    Route::get('/login',    [AuthController::class,'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class,'login'])->name('login.post');
});

// AUTH: logout
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth')->name('logout');

// AUTH-protected (🚫 hakuna 'role' middleware tena)
Route::middleware(['auth'])->group(function () {
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // MUHITAJI: create + pay (role check tutafanya ndani ya controller)
    Route::get('/jobs/create', [JobController::class,'create'])->name('jobs.create');
    Route::post('/jobs', [JobController::class,'store'])->name('jobs.store');
    Route::get('/jobs/{job}/wait', [JobController::class,'wait'])->name('jobs.pay.wait');
    
    // MFANYAKAZI: create + pay jobs (role check tutafanya ndani ya controller)
    Route::get('/jobs/create-mfanyakazi', [JobController::class,'createMfanyakazi'])->name('jobs.create-mfanyakazi');
    Route::post('/jobs-mfanyakazi', [JobController::class,'storeMfanyakazi'])->name('jobs.store-mfanyakazi');

    // Poll status (generic)
    Route::get('/jobs/{job}/poll', [PaymentController::class,'poll'])->name('jobs.pay.poll');

    // MFANYAKAZI feed + view + comment (role check ndani ya controller)
    Route::get('/feed', [FeedController::class,'index'])->name('feed');
    Route::get('/jobs/{job}', [JobViewController::class,'show'])->name('jobs.show');
    Route::post('/jobs/{job}/comment', [JobViewController::class,'comment'])->name('jobs.comment');

    // MUHITAJI accept (role check ndani ya controller)
    Route::post('/jobs/{job}/accept/{comment}', [JobViewController::class,'accept'])->name('jobs.accept');

    // PRIVATE CHAT/MESSAGING
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{job}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{job}/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{job}/poll', [\App\Http\Controllers\ChatController::class, 'poll'])->name('chat.poll');
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'unreadCount'])->name('chat.unread');

    
// MUHITAJI — kazi zangu
Route::get('/my/jobs', [MyJobsController::class,'index'])->name('my.jobs');

// MFANYAKAZI — assigned list + actions
Route::get('/mfanyakazi/assigned', [WorkerActionsController::class,'assigned'])->name('mfanyakazi.assigned');
Route::post('/mfanyakazi/jobs/{job}/accept',  [WorkerActionsController::class,'accept'])->name('mfanyakazi.jobs.accept');
Route::post('/mfanyakazi/jobs/{job}/decline', [WorkerActionsController::class,'decline'])->name('mfanyakazi.jobs.decline');
Route::post('/mfanyakazi/jobs/{job}/complete',[WorkerActionsController::class,'complete'])->name('mfanyakazi.jobs.complete');

// WITHDRAWALS
Route::get('/withdraw', [WithdrawalController::class,'requestForm'])->name('withdraw.form');
Route::post('/withdraw/submit', [WithdrawalController::class,'submit'])->name('withdraw.submit');
// MFANYAKAZI — assigned list + actions
Route::get('/mfanyakazi/assigned', [WorkerActionsController::class,'assigned'])->name('mfanyakazi.assigned');
Route::post('/mfanyakazi/jobs/{job}/accept',  [WorkerActionsController::class,'accept'])->name('mfanyakazi.jobs.accept');
Route::post('/mfanyakazi/jobs/{job}/decline', [WorkerActionsController::class,'decline'])->name('mfanyakazi.jobs.decline');
Route::post('/mfanyakazi/jobs/{job}/complete',[WorkerActionsController::class,'complete'])->name('mfanyakazi.jobs.complete');
// ADMIN — Full Access Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [\App\Http\Controllers\AdminController::class, 'userDetails'])->name('admin.user.details');
    Route::get('/users/{user}/dashboard', [\App\Http\Controllers\AdminController::class, 'viewUserDashboard'])->name('admin.user.dashboard');
    Route::get('/users/{user}/monitor', [\App\Http\Controllers\AdminController::class, 'monitorUser'])->name('admin.user.monitor');
    
    // Job Management
    Route::get('/jobs', [\App\Http\Controllers\AdminController::class, 'jobs'])->name('admin.jobs');
    Route::get('/jobs/{job}', [\App\Http\Controllers\AdminController::class, 'jobDetails'])->name('admin.job.details');
    
    // Chat/Conversation Monitoring
    Route::get('/chats', [\App\Http\Controllers\AdminController::class, 'allChats'])->name('admin.chats');
    Route::get('/chats/{job}', [\App\Http\Controllers\AdminController::class, 'viewChat'])->name('admin.chat.view');
    
    // Analytics & Reports
    Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('admin.analytics');
    
    // ADMIN IMPERSONATION - Login as any user
    Route::get('/impersonate/{user}', [\App\Http\Controllers\AdminController::class, 'impersonate'])->name('admin.impersonate');
    Route::get('/stop-impersonate', [\App\Http\Controllers\AdminController::class, 'stopImpersonate'])->name('admin.stop-impersonate');
    
    // ADMIN FULL CONTROL - User Management
    Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('admin.user.toggle-status');
    
    // ADMIN FULL CONTROL - Job Management
    Route::post('/jobs/{job}/force-complete', [\App\Http\Controllers\AdminController::class, 'forceCompleteJob'])->name('admin.job.force-complete');
    Route::post('/jobs/{job}/force-cancel', [\App\Http\Controllers\AdminController::class, 'forceCancelJob'])->name('admin.job.force-cancel');
    
    // ADMIN FULL CONTROL - System Management
    Route::get('/system-logs', [\App\Http\Controllers\AdminController::class, 'systemLogs'])->name('admin.system-logs');
    Route::get('/system-settings', [\App\Http\Controllers\AdminController::class, 'systemSettings'])->name('admin.system-settings');
    Route::post('/system-settings', [\App\Http\Controllers\AdminController::class, 'updateSystemSettings'])->name('admin.system-settings.update');
    
    // ADMIN FULL CONTROL - Communication
    Route::post('/users/{user}/send-message', [\App\Http\Controllers\AdminController::class, 'sendMessageToUser'])->name('admin.user.send-message');
    
    // Withdrawals
    Route::get('/withdrawals', [WithdrawalAdminController::class,'index'])->name('admin.withdrawals');
    Route::post('/withdrawals/{withdrawal}/paid',   [WithdrawalAdminController::class,'markPaid'])->name('admin.withdrawals.paid');
    Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalAdminController::class,'reject'])->name('admin.withdrawals.reject');
    
    // Completed Jobs by Workers (keeping legacy route)
    Route::get('/completed-jobs', function() {
        $workers = \App\Models\User::where('role', 'mfanyakazi')
            ->with(['assignedJobs' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withCount(['assignedJobs as completed_jobs' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('completed_jobs', 'desc')
            ->paginate(20);
        return view('admin.completed-jobs', compact('workers'));
    })->name('admin.completed-jobs');
});
});

// Zeno webhook (no auth)
Route::post('/payment/zeno/webhook', [PaymentController::class,'webhook'])->name('zeno.webhook');

// API routes for dashboard updates
Route::get('/api/dashboard-updates', [\App\Http\Controllers\Api\DashboardController::class, 'updates'])->middleware('auth');

// Debug route for testing payment flow
Route::get('/debug/payment/{job}', function(\App\Models\Job $job) {
    $worker = $job->acceptedWorker;
    if (!$worker) {
        return response()->json(['error' => 'No worker found']);
    }
    
    $wallet = $worker->ensureWallet();
    $transactions = \App\Models\WalletTransaction::where('user_id', $worker->id)->latest()->take(5)->get();
    
    return response()->json([
        'job_id' => $job->id,
        'job_status' => $job->status,
        'job_amount' => $job->amount,
        'completion_code' => $job->completion_code,
        'worker_id' => $worker->id,
        'worker_name' => $worker->name,
        'wallet_balance' => $wallet->balance,
        'recent_transactions' => $transactions
    ]);
})->middleware('auth');
