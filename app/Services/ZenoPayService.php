<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZenoPayService
{
    public function startPayment(array $payload): array
    {
        $base = rtrim(config('services.zeno.base_url', env('ZENO_BASE_URL','https://zenoapi.com/api')), '/');
        $url  = $base.'/payments/mobile_money_tanzania';

        $resp = Http::withHeaders(['x-api-key'=>env('ZENO_API_KEY')])->post($url, $payload);

        return ['ok'=>$resp->successful(), 'json'=>$resp->json(), 'status'=>$resp->status()];
    }

    public function checkOrder(string $orderId): array
    {
        $base = rtrim(config('services.zeno.base_url', env('ZENO_BASE_URL','https://zenoapi.com/api')), '/');
        $resp = Http::withHeaders(['x-api-key'=>env('ZENO_API_KEY')])
            ->get($base.'/payments/order-status', ['order_id'=>$orderId]);

        return ['ok'=>$resp->successful(), 'json'=>$resp->json(), 'status'=>$resp->status()];
    }

    public function verifyWebhook(string $providedKey): bool
    {
        return hash_equals(env('ZENO_API_KEY'), $providedKey);
    }
}
