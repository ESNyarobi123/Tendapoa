<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * API: Get all notifications for authenticated user
     * GET /api/notifications
     */
    public function apiIndex(Request $request)
    {
        $user = Auth::user();
        
        $perPage = $request->query('per_page', 20);
        $type = $request->query('type'); // Filter by type
        $unreadOnly = $request->query('unread_only', false);
        
        $query = Notification::where('user_id', $user->id);
        
        // Filter by type if provided
        if ($type) {
            $query->where('type', $type);
        }
        
        // Filter unread only
        if ($unreadOnly) {
            $query->unread();
        }
        
        $notifications = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ],
            'unread_count' => $this->notificationService->getUnreadCount($user->id),
            'status' => 'success'
        ]);
    }

    /**
     * API: Get unread notifications count
     * GET /api/notifications/unread-count
     */
    public function apiUnreadCount()
    {
        $user = Auth::user();
        $count = $this->notificationService->getUnreadCount($user->id);
        
        return response()->json([
            'success' => true,
            'unread_count' => $count,
            'status' => 'success'
        ]);
    }

    /**
     * API: Get unread notifications only
     * GET /api/notifications/unread
     */
    public function apiUnread(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->query('per_page', 20);
        
        $notifications = Notification::where('user_id', $user->id)
            ->unread()
            ->latest()
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ],
            'status' => 'success'
        ]);
    }

    /**
     * API: Mark a notification as read
     * POST /api/notifications/{notification}/read
     */
    public function apiMarkAsRead(Notification $notification)
    {
        $user = Auth::user();
        
        // Check if notification belongs to user
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Notification haipo kwako.',
                'status' => 'forbidden'
            ], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification imesomwa.',
            'notification' => $notification,
            'unread_count' => $this->notificationService->getUnreadCount($user->id),
            'status' => 'success'
        ]);
    }

    /**
     * API: Mark all notifications as read
     * POST /api/notifications/mark-all-read
     */
    public function apiMarkAllAsRead()
    {
        $user = Auth::user();
        $count = $this->notificationService->markAllAsRead($user->id);
        
        return response()->json([
            'success' => true,
            'message' => "Notifications {$count} zimesomwa.",
            'marked_count' => $count,
            'unread_count' => 0,
            'status' => 'success'
        ]);
    }

    /**
     * API: Delete a notification
     * DELETE /api/notifications/{notification}
     */
    public function apiDelete(Notification $notification)
    {
        $user = Auth::user();
        
        // Check if notification belongs to user
        if ($notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'Notification haipo kwako.',
                'status' => 'forbidden'
            ], 403);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification imefutwa.',
            'unread_count' => $this->notificationService->getUnreadCount($user->id),
            'status' => 'success'
        ]);
    }

    /**
     * API: Delete all read notifications
     * DELETE /api/notifications/clear-read
     */
    public function apiClearRead()
    {
        $user = Auth::user();
        
        $count = Notification::where('user_id', $user->id)
            ->where('is_read', true)
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Notifications {$count} zimefutwa.",
            'deleted_count' => $count,
            'status' => 'success'
        ]);
    }

    /**
     * API: Get notifications by type
     * GET /api/notifications/type/{type}
     */
    public function apiByType(Request $request, string $type)
    {
        $user = Auth::user();
        $perPage = $request->query('per_page', 20);
        
        $notifications = Notification::where('user_id', $user->id)
            ->where('type', $type)
            ->latest()
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'type' => $type,
            'notifications' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ],
            'status' => 'success'
        ]);
    }

    /**
     * API: Get notification types summary
     * GET /api/notifications/summary
     */
    public function apiSummary()
    {
        $user = Auth::user();
        
        $summary = Notification::where('user_id', $user->id)
            ->selectRaw('type, COUNT(*) as count, SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread_count')
            ->groupBy('type')
            ->get();
        
        return response()->json([
            'success' => true,
            'summary' => $summary,
            'total_unread' => $this->notificationService->getUnreadCount($user->id),
            'status' => 'success'
        ]);
    }
}

