<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\PushNotification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $firebaseCredentialsPath = base_path('tendapoa-eb234-firebase-adminsdk-fbsvc-c3b97c7be3.json');
            
            if (!file_exists($firebaseCredentialsPath)) {
                throw new \Exception('Firebase credentials file not found');
            }

            $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send notification to single FCM token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        if (!$this->messaging) {
            Log::error('Firebase messaging not initialized');
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send FCM notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to multiple FCM tokens
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging) {
            Log::error('Firebase messaging not initialized');
            return ['success' => 0, 'failed' => count($tokens), 'errors' => ['Firebase not initialized']];
        }

        $results = ['success' => 0, 'failed' => 0, 'errors' => []];

        foreach ($tokens as $token) {
            try {
                if ($this->sendToToken($token, $title, $body, $data)) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Failed to send to token: " . substr($token, 0, 20) . "...";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Send notification to all active FCM tokens
     */
    public function sendToAll(string $title, string $body, ?int $sentBy = null): PushNotification
    {
        $tokens = FcmToken::where('is_active', true)
            ->pluck('token')
            ->toArray();

        $pushNotification = PushNotification::create([
            'sent_by' => $sentBy,
            'title' => $title,
            'body' => $body,
            'total_recipients' => count($tokens),
            'status' => 'sending',
            'fcm_tokens' => FcmToken::where('is_active', true)->pluck('id')->toArray(),
        ]);

        $results = $this->sendToTokens($tokens, $title, $body);

        $pushNotification->update([
            'successful_sends' => $results['success'],
            'failed_sends' => $results['failed'],
            'errors' => $results['errors'],
            'status' => $results['failed'] > 0 && $results['success'] == 0 ? 'failed' : 'completed',
            'sent_at' => now(),
        ]);

        return $pushNotification;
    }

    /**
     * Send notification to selected FCM tokens
     */
    public function sendToSelected(array $tokenIds, string $title, string $body, ?int $sentBy = null): PushNotification
    {
        $tokens = FcmToken::whereIn('id', $tokenIds)
            ->where('is_active', true)
            ->pluck('token')
            ->toArray();

        $pushNotification = PushNotification::create([
            'sent_by' => $sentBy,
            'title' => $title,
            'body' => $body,
            'total_recipients' => count($tokens),
            'status' => 'sending',
            'fcm_tokens' => $tokenIds,
        ]);

        $results = $this->sendToTokens($tokens, $title, $body);

        $pushNotification->update([
            'successful_sends' => $results['success'],
            'failed_sends' => $results['failed'],
            'errors' => $results['errors'],
            'status' => $results['failed'] > 0 && $results['success'] == 0 ? 'failed' : 'completed',
            'sent_at' => now(),
        ]);

        return $pushNotification;
    }
}

