<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZenoPayService
{
    private function http()
    {
        $timeout = (int) config('services.zeno.timeout', 45);
        $retries = (int) config('services.zeno.retries', 2);
        $retryDelay = (int) config('services.zeno.retry_delay', 500); // milliseconds

        return Http::withHeaders(['x-api-key'=>env('ZENO_API_KEY')])
            ->timeout($timeout)
            ->retry($retries, $retryDelay);
    }

    public function startPayment(array $payload): array
    {
        $base = rtrim(config('services.zeno.base_url', env('ZENO_BASE_URL','https://zenoapi.com/api')), '/');
        $url  = $base.'/payments/mobile_money_tanzania';

        try {
            $resp = $this->http()->post($url, $payload);
            return ['ok'=>$resp->successful(), 'json'=>$resp->json(), 'status'=>$resp->status()];
        } catch (ConnectionException $e) {
            Log::warning('ZenoPay startPayment timeout', [
                'payload' => $payload,
                'message' => $e->getMessage(),
            ]);
            return [
                'ok' => false,
                'json' => ['error' => 'timeout', 'message' => 'Imeshindikana kuwasiliana na malipo (timeout).'],
                'status' => 0,
            ];
        }
    }

    public function checkOrder(string $orderId): array
    {
        $base = rtrim(config('services.zeno.base_url', env('ZENO_BASE_URL','https://zenoapi.com/api')), '/');

        try {
            $resp = $this->http()
                ->get($base.'/payments/order-status', ['order_id'=>$orderId]);

            return ['ok'=>$resp->successful(), 'json'=>$resp->json(), 'status'=>$resp->status()];
        } catch (ConnectionException $e) {
            Log::warning('ZenoPay checkOrder timeout', [
                'order_id' => $orderId,
                'message'  => $e->getMessage(),
            ]);

            return [
                'ok' => false,
                'json' => ['error' => 'timeout', 'message' => 'Hatukuweza kuthibitisha malipo kwa sasa.'],
                'status' => 0,
            ];
        }
    }

    public function verifyWebhook(string $providedKey): bool
    {
        return hash_equals(env('ZENO_API_KEY'), $providedKey);
    }
}
