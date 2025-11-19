<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use App\Models\PushNotification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Show push notification form
     * GET /admin/push-notifications
     */
    public function index()
    {
        $totalTokens = FcmToken::where('is_active', true)->count();
        $recentNotifications = PushNotification::with('sender')
            ->latest()
            ->limit(10)
            ->get();

        // Check Firebase status
        $firebaseError = $this->firebaseService->getInitializationError();
        $firebaseReady = $firebaseError === null;

        return view('admin.push-notifications.index', compact('totalTokens', 'recentNotifications', 'firebaseReady', 'firebaseError'));
    }

    /**
     * Get all active FCM tokens (for API)
     * GET /api/admin/push-notifications/tokens
     */
    public function getTokens(Request $request)
    {
        $tokens = FcmToken::where('is_active', true)
            ->with('user:id,name,email,role')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'tokens' => $tokens,
            'total' => $tokens->count()
        ]);
    }

    /**
     * Send push notification
     * POST /admin/push-notifications/send
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'token_ids' => 'nullable|array',
            'token_ids.*' => 'exists:fcm_tokens,id',
            'send_to_all' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $sendToAll = $request->boolean('send_to_all', false);
        $tokenIds = $request->input('token_ids', []);

        try {
            if ($sendToAll) {
                $pushNotification = $this->firebaseService->sendToAll(
                    $request->title,
                    $request->body,
                    Auth::id()
                );
            } else {
                if (empty($tokenIds)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Please select at least one token or enable "Send to All"'
                        ], 422);
                    }
                    return back()->withErrors(['token_ids' => 'Please select at least one token or enable "Send to All"'])->withInput();
                }

                $pushNotification = $this->firebaseService->sendToSelected(
                    $tokenIds,
                    $request->title,
                    $request->body,
                    Auth::id()
                );
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Push notification sent successfully',
                    'notification' => $pushNotification
                ]);
            }

            return redirect()->route('admin.push-notifications.index')
                ->with('success', 'Push notification sent successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to send notification: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Get notification history
     * GET /admin/push-notifications/history
     */
    public function history(Request $request)
    {
        $notifications = PushNotification::with('sender:id,name,email')
            ->latest()
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);
        }

        return view('admin.push-notifications.history', compact('notifications'));
    }

    /**
     * Get notification details
     * GET /admin/push-notifications/{id}
     */
    public function show($id)
    {
        $notification = PushNotification::with('sender:id,name,email')
            ->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'notification' => $notification
            ]);
        }

        return view('admin.push-notifications.show', compact('notification'));
    }
}
