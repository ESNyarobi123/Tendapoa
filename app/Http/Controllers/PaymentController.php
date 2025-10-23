<?php

namespace App\Http\Controllers;

use App\Models\{Job, Payment, Wallet, WalletTransaction};
use App\Services\ZenoPayService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function poll(Job $job, ZenoPayService $zeno)
    {
        $payment = $job->payment;
        if (!$payment) abort(404);

        if ($payment->status === 'COMPLETED') {
            return response()->json(['done'=>true]);
        }

        $resp = $zeno->checkOrder($payment->order_id);

        if ($resp['ok'] && (($resp['json']['result'] ?? null) === 'SUCCESS' || ($resp['json']['payment_status'] ?? null) === 'COMPLETED')) {
            $payment->update([
                'status'     => 'COMPLETED',
                'resultcode' => $resp['json']['resultcode'] ?? null,
                'reference'  => $resp['json']['reference'] ?? null,
                'channel'    => data_get($resp,'json.data.0.channel'),
                'msisdn'     => data_get($resp,'json.data.0.msisdn'),
                'transid'    => data_get($resp,'json.data.0.transid'),
                'meta'       => $resp['json'],
            ]);
        }

        return response()->json([
            'done'=>$payment->status==='COMPLETED',
            'status'=>$payment->status
        ]);
    }

    public function webhook(ZenoPayService $zeno)
    {
        $key = request()->header('x-api-key','');
        if (!$zeno->verifyWebhook($key)) abort(401);

        $payload = request()->all();
        $payment = Payment::where('order_id', $payload['order_id'] ?? '')->first();

        if ($payment && (($payload['payment_status'] ?? '') === 'COMPLETED' || ($payload['result'] ?? '') === 'SUCCESS')) {
            $payment->update([
                'status'    => 'COMPLETED',
                'reference' => $payload['reference'] ?? null,
                'meta'      => $payload,
            ]);

            // Handle job posting payment completion
            $job = $payment->job;
            if ($job && $job->poster_type === 'mfanyakazi' && $job->status === 'pending_payment') {
                DB::transaction(function () use ($job) {
                    // Update job status to posted
                    $job->update([
                        'status' => 'posted',
                        'published_at' => now(),
                    ]);

                    // Deduct posting fee from mfanyakazi's wallet
                    $userWallet = $job->muhitaji->ensureWallet();
                    $userWallet->decrement('balance', $job->posting_fee);

                    // Record transaction
                    WalletTransaction::create([
                        'user_id' => $job->muhitaji->id,
                        'type'    => 'debit',
                        'amount'  => $job->posting_fee,
                        'description' => "Job posting fee for: {$job->title}",
                        'reference'   => "JOB_POST_{$job->id}",
                    ]);
                });
            }
        }
        return response()->json(['ok'=>true]);
    }
}
