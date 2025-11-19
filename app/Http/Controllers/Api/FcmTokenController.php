<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FcmTokenController extends Controller
{
    /**
     * Store or update FCM token for authenticated user
     * POST /api/fcm-token
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:500',
            'device_id' => 'nullable|string|max:255',
            'device_type' => 'nullable|string|in:android,ios',
            'app_version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if token already exists for this user
        $existingToken = FcmToken::where('token', $request->token)
            ->where('user_id', $user->id)
            ->first();

        if ($existingToken) {
            // Update existing token
            $existingToken->update([
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'app_version' => $request->app_version,
                'is_active' => true,
                'last_used_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully',
                'fcm_token' => $existingToken
            ]);
        }

        // Check if token exists for another user (shouldn't happen, but handle it)
        $tokenExists = FcmToken::where('token', $request->token)->first();
        if ($tokenExists && $tokenExists->user_id !== $user->id) {
            // Deactivate old token and create new one
            $tokenExists->update(['is_active' => false]);
        }

        // Create new token
        $fcmToken = FcmToken::create([
            'user_id' => $user->id,
            'token' => $request->token,
            'device_id' => $request->device_id,
            'device_type' => $request->device_type,
            'app_version' => $request->app_version,
            'is_active' => true,
            'last_used_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token registered successfully',
            'fcm_token' => $fcmToken
        ], 201);
    }

    /**
     * Remove FCM token (when user logs out or uninstalls app)
     * DELETE /api/fcm-token/{token}
     */
    public function destroy(Request $request, $token = null)
    {
        $tokenToDelete = $token ?? $request->input('token');

        if (!$tokenToDelete) {
            return response()->json([
                'success' => false,
                'message' => 'Token is required'
            ], 422);
        }

        $user = Auth::user();

        $fcmToken = FcmToken::where('token', $tokenToDelete)
            ->where('user_id', $user->id)
            ->first();

        if ($fcmToken) {
            $fcmToken->update(['is_active' => false]);
            // Or delete: $fcmToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'FCM token removed successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'FCM token not found'
        ], 404);
    }
}
