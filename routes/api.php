<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    JobController,
    PaymentController,
    FeedController,
    JobViewController,
    MyJobsController,
    WorkerActionsController,
    ChatController,
    WithdrawalController,
    AdminController
};
use App\Http\Controllers\Admin\WithdrawalAdminController;

/*
|--------------------------------------------------------------------------
| API Routes (Base URL: /api)
| - Public endpoints: no auth
| - Protected endpoints: auth:sanctum (Bearer token)
|--------------------------------------------------------------------------
*/

/**
 * Public health checks
 */
Route::get('/health', function () {
    return response()->json([
        'status'    => 'ok',
        'timestamp' => now(),
        'version'   => '1.0.0',
    ]);
});

Route::get('/ping', fn () => response()->json([
    'ok' => true,
    'ts' => now(),
]));

/**
 * Authentication (public + protected)
 * - POST /api/auth/register  -> create account & get token
 * - POST /api/auth/login     -> issue Sanctum token
 * - GET  /api/auth/user      -> current user (protected)
 * - GET  /api/auth/profile   -> user profile with wallet (protected)
 * - POST /api/auth/logout    -> revoke current token (protected)
 */
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.auth.register');
    Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'user' => $request->user(),
            ]);
        })->name('api.auth.user');

        Route::get('/profile', [AuthController::class, 'apiUserProfile'])->name('api.auth.profile');
        
        Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.auth.logout');
    });
});

/**
 * Protected API (Sanctum)
 */
Route::middleware('auth:sanctum')->group(function () {

    // ---------------------------------------------------------------------
    // Jobs (Muhitaji)
    // ---------------------------------------------------------------------
    Route::prefix('jobs')->group(function () {
        // List my jobs
        Route::get('/my',         [MyJobsController::class,  'apiIndex'])->name('api.jobs.my');

        // Show job details
        Route::get('/{job}',      [JobViewController::class, 'apiShow'])->name('api.jobs.show');

        // Create job
        Route::post('/',          [JobController::class,     'apiStore'])->name('api.jobs.store');

        // Update job
        Route::put('/{job}',      [JobController::class,     'apiUpdate'])->name('api.jobs.update');

        // Edit form data (if needed by client)
        Route::get('/{job}/edit', [JobController::class,     'apiEdit'])->name('api.jobs.edit');

        // Poll payment status for job
        Route::get('/{job}/payment-status', [PaymentController::class, 'apiPoll'])->name('api.jobs.payment-status');

        // Job comments
        Route::post('/{job}/comment', [JobViewController::class, 'apiComment'])->name('api.jobs.comment');

        // Accept worker
        Route::post('/{job}/accept/{comment}', [JobViewController::class, 'apiAccept'])->name('api.jobs.accept');
    });

    // ---------------------------------------------------------------------
    // Feed (Mfanyakazi browse)
    // ---------------------------------------------------------------------
    Route::prefix('feed')->group(function () {
        Route::get('/',    [FeedController::class, 'apiIndex'])->name('api.feed.index');
        Route::get('/map', [FeedController::class, 'apiMap'])->name('api.feed.map');
    });

    // ---------------------------------------------------------------------
    // Worker actions
    // ---------------------------------------------------------------------
    Route::prefix('worker')->group(function () {
        Route::get('/assigned',                 [WorkerActionsController::class, 'apiAssigned'])->name('api.worker.assigned');
        Route::post('/jobs/{job}/accept',       [WorkerActionsController::class, 'apiAccept'])->name('api.worker.accept');
        Route::post('/jobs/{job}/decline',      [WorkerActionsController::class, 'apiDecline'])->name('api.worker.decline');
        Route::post('/jobs/{job}/complete',     [WorkerActionsController::class, 'apiComplete'])->name('api.worker.complete');
    });

    // ---------------------------------------------------------------------
    // Chat
    // ---------------------------------------------------------------------
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'apiIndex'])->name('api.chat.index');
        Route::get('/{job}', [ChatController::class, 'apiShow'])->name('api.chat.show');
        Route::post('/{job}/send', [ChatController::class, 'apiSend'])->name('api.chat.send');
        Route::get('/{job}/poll', [ChatController::class, 'apiPoll'])->name('api.chat.poll');
        Route::get('/unread-count', [ChatController::class, 'apiUnreadCount'])->name('api.chat.unread');
    });

    // ---------------------------------------------------------------------
    // Withdrawals
    // ---------------------------------------------------------------------
    Route::prefix('withdraw')->group(function () {
        Route::get('/form', [WithdrawalController::class, 'apiRequestForm'])->name('api.withdraw.form');
        Route::post('/submit', [WithdrawalController::class, 'apiSubmit'])->name('api.withdraw.submit');
    });

    // ---------------------------------------------------------------------
    // Profile quick info
    // ---------------------------------------------------------------------
    Route::get('/profile', function (Request $request) {
        $u = $request->user();

        return response()->json([
            'user'         => $u,
            'role'         => $u->role ?? null,
            'has_location' => method_exists($u, 'hasLocation') ? $u->hasLocation() : null,
            'location'     => method_exists($u, 'getLocationString') ? $u->getLocationString() : null,
        ]);
    })->name('api.profile');

    // ---------------------------------------------------------------------
    // Dashboard (role-based snapshot)
    // ---------------------------------------------------------------------
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();

        if ($user->role === 'muhitaji') {
            $posted    = \App\Models\Job::where('user_id', $user->id)->count();
            $completed = \App\Models\Job::where('user_id', $user->id)
                ->where('status', 'completed')->count();
            $totalPaid = \App\Models\Payment::whereHas('job', fn ($q) => $q->where('user_id', $user->id))
                ->where('status', 'COMPLETED')
                ->sum('amount');

            return response()->json([
                'role'  => 'muhitaji',
                'stats' => [
                    'posted_jobs'    => $posted,
                    'completed_jobs' => $completed,
                    'total_paid'     => $totalPaid,
                ],
            ]);
        }

        if ($user->role === 'mfanyakazi') {
            $done        = \App\Models\Job::where('accepted_worker_id', $user->id)
                ->where('status', 'completed')->count();
            $earnTotal   = \App\Models\WalletTransaction::where('user_id', $user->id)
                ->where('type', 'credit')->sum('amount');
            $currentJobs = \App\Models\Job::where('accepted_worker_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress'])->count();

            return response()->json([
                'role'  => 'mfanyakazi',
                'stats' => [
                    'completed_jobs' => $done,
                    'total_earnings' => $earnTotal,
                    'current_jobs'   => $currentJobs,
                ],
            ]);
        }

        return response()->json(['role' => $user->role]);
    })->name('api.dashboard');

    // ---------------------------------------------------------------------
    // Admin API Routes (admin middleware)
    // ---------------------------------------------------------------------
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'apiDashboard'])->name('api.admin.dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'apiUsers'])->name('api.admin.users');
        Route::get('/users/{user}', [AdminController::class, 'apiUserDetails'])->name('api.admin.user.details');
        Route::get('/users/{user}/dashboard', [AdminController::class, 'apiViewUserDashboard'])->name('api.admin.user.dashboard');
        Route::get('/users/{user}/monitor', [AdminController::class, 'apiMonitorUser'])->name('api.admin.user.monitor');
        Route::get('/users/{user}/edit', [AdminController::class, 'apiEditUser'])->name('api.admin.user.edit');
        Route::put('/users/{user}', [AdminController::class, 'apiUpdateUser'])->name('api.admin.user.update');
        Route::delete('/users/{user}', [AdminController::class, 'apiDeleteUser'])->name('api.admin.user.delete');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'apiToggleUserStatus'])->name('api.admin.user.toggle-status');
        Route::post('/users/{user}/send-message', [AdminController::class, 'apiSendMessageToUser'])->name('api.admin.user.send-message');

        // Jobs
        Route::get('/jobs', [AdminController::class, 'apiJobs'])->name('api.admin.jobs');
        Route::get('/jobs/{job}', [AdminController::class, 'apiJobDetails'])->name('api.admin.job.details');
        Route::post('/jobs/{job}/force-complete', [AdminController::class, 'apiForceCompleteJob'])->name('api.admin.job.force-complete');
        Route::post('/jobs/{job}/force-cancel', [AdminController::class, 'apiForceCancelJob'])->name('api.admin.job.force-cancel');

        // Chats
        Route::get('/chats', [AdminController::class, 'apiAllChats'])->name('api.admin.chats');
        Route::get('/chats/{job}', [AdminController::class, 'apiViewChat'])->name('api.admin.chat.view');

        // Analytics
        Route::get('/analytics', [AdminController::class, 'apiAnalytics'])->name('api.admin.analytics');

        // System
        Route::get('/system-logs', [AdminController::class, 'apiSystemLogs'])->name('api.admin.system-logs');
        Route::get('/system-settings', [AdminController::class, 'apiSystemSettings'])->name('api.admin.system-settings');
        Route::post('/system-settings', [AdminController::class, 'apiUpdateSystemSettings'])->name('api.admin.system-settings.update');

        // Withdrawals (Admin)
        Route::get('/withdrawals', [WithdrawalAdminController::class, 'apiIndex'])->name('api.admin.withdrawals');
        Route::post('/withdrawals/{withdrawal}/paid', [WithdrawalAdminController::class, 'apiMarkPaid'])->name('api.admin.withdrawals.paid');
        Route::post('/withdrawals/{withdrawal}/reject', [WithdrawalAdminController::class, 'apiReject'])->name('api.admin.withdrawals.reject');

        // Completed jobs leaderboard
        Route::get('/completed-jobs', function () {
            $workers = \App\Models\User::where('role','mfanyakazi')
                ->with(['assignedJobs' => function($q){ $q->where('status','completed'); }])
                ->withCount(['assignedJobs as completed_jobs' => function($q){ $q->where('status','completed'); }])
                ->orderBy('completed_jobs','desc')
                ->paginate(20);
            
            return response()->json([
                'success' => true,
                'data' => $workers
            ]);
        })->name('api.admin.completed-jobs');
    });
});

/**
 * Public API Routes (no auth required)
 */
// ZenoPay Webhook (public, JSON response)
Route::post('/payment/zeno/webhook', [PaymentController::class, 'webhook'])->name('api.zeno.webhook');

/**
 * Debug endpoint (protected) — helpful during development
 */
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
})->middleware('auth:sanctum')->name('api.debug.payment');

/**
 * Laravel-owned API 404
 * (Inazuia proxy ku-return JSON yake ya custom)
 */
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'error'   => 'API endpoint not found',
        'hint'    => 'Check HTTP method & path. All API routes are under /api/*',
    ], 404);
});