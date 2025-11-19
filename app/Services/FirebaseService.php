<?php

namespace App\Services;

use App\Models\FcmToken;
use App\Models\PushNotification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Check if Firebase package is installed
        if (!class_exists(\Kreait\Firebase\Factory::class)) {
            $error = 'Firebase package (kreait/firebase-php) is not installed. Run: composer require kreait/firebase-php --ignore-platform-req=ext-sodium';
            Log::error($error);
            $this->messaging = null;
            return;
        }

        try {
            $firebaseCredentialsPath = base_path('tendapoa-eb234-firebase-adminsdk-fbsvc-c3b97c7be3.json');
            
            if (!file_exists($firebaseCredentialsPath)) {
                $error = 'Firebase credentials file not found at: ' . $firebaseCredentialsPath;
                Log::error($error);
                throw new \Exception($error);
            }

            if (!is_readable($firebaseCredentialsPath)) {
                $error = 'Firebase credentials file is not readable. Check file permissions.';
                Log::error($error . ' Path: ' . $firebaseCredentialsPath);
                throw new \Exception($error);
            }

            // Validate JSON file
            $jsonContent = file_get_contents($firebaseCredentialsPath);
            $credentials = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $error = 'Invalid JSON in Firebase credentials file: ' . json_last_error_msg();
                Log::error($error);
                throw new \Exception($error);
            }

            if (empty($credentials['project_id']) || empty($credentials['private_key'])) {
                $error = 'Firebase credentials file is missing required fields (project_id or private_key)';
                Log::error($error);
                throw new \Exception($error);
            }

            $factory = (new \Kreait\Firebase\Factory)->withServiceAccount($firebaseCredentialsPath);
            $this->messaging = $factory->createMessaging();
            
            Log::info('Firebase initialized successfully', ['project_id' => $credentials['project_id']]);
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->messaging = null;
        }
    }

    /**
     * Send notification to single FCM token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging) {
            $error = 'Firebase messaging not initialized. Check if credentials file exists and is readable.';
            Log::error($error);
            return ['success' => false, 'error' => $error];
        }

        try {
            // Validate token format
            if (empty($token) || strlen($token) < 10) {
                $error = 'Invalid FCM token format';
                Log::error($error . ': ' . substr($token, 0, 20));
                return ['success' => false, 'error' => $error];
            }

            $notification = \Kreait\Firebase\Messaging\Notification::create($title, $body);
            
            $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            $this->messaging->send($message);
            Log::info('FCM notification sent successfully', ['token' => substr($token, 0, 20) . '...']);
            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            // Check if it's a Firebase-specific exception
            $exceptionClass = get_class($e);
            if (strpos($exceptionClass, 'Kreait\\Firebase') !== false) {
                if (strpos($exceptionClass, 'InvalidArgument') !== false) {
                    $error = 'Invalid FCM token: ' . $e->getMessage();
                } elseif (strpos($exceptionClass, 'NotFound') !== false) {
                    $error = 'FCM token not found or unregistered: ' . $e->getMessage();
                } elseif (strpos($exceptionClass, 'Messaging') !== false) {
                    $error = 'Firebase messaging error: ' . $e->getMessage();
                } else {
                    $error = 'Firebase error: ' . $e->getMessage();
                }
            } else {
                $error = 'Failed to send FCM notification: ' . $e->getMessage() . ' (Type: ' . get_class($e) . ')';
            }
            Log::error($error);
            return ['success' => false, 'error' => $error];
        }
    }

    /**
     * Send notification to multiple FCM tokens
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging) {
            $error = 'Firebase messaging not initialized. Check credentials file.';
            Log::error($error);
            return [
                'success' => 0, 
                'failed' => count($tokens), 
                'errors' => [$error]
            ];
        }

        if (empty($tokens)) {
            return ['success' => 0, 'failed' => 0, 'errors' => ['No tokens provided']];
        }

        $results = ['success' => 0, 'failed' => 0, 'errors' => []];

        foreach ($tokens as $index => $token) {
            $result = $this->sendToToken($token, $title, $body, $data);
            
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['failed']++;
                $errorMsg = $result['error'] ?? 'Unknown error';
                $results['errors'][] = "Token " . ($index + 1) . " (" . substr($token, 0, 20) . "...): " . $errorMsg;
            }
        }

        Log::info('FCM batch send completed', [
            'total' => count($tokens),
            'success' => $results['success'],
            'failed' => $results['failed']
        ]);

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
