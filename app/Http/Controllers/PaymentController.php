<?php

namespace App\Http\Controllers;

use App\Models\{Job, Payment, Wallet, WalletTransaction};
use App\Services\ZenoPayService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function poll(Job $job, ZenoPayService $zeno)
    {
        $payment = $job->payment;
        if (!$payment) abort(404);

        if ($payment->status === 'COMPLETED') {
            return response()->json(['done'=>true]);
        }

        $resp = $zeno->checkOrder($payment->order_id);

        if ($resp['ok'] && data_get($resp, 'json.data.0.payment_status') === 'COMPLETED') {
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

        if ($payment && ($payload['payment_status'] ?? '') === 'COMPLETED') {
            $payment->update([
                'status'    => 'COMPLETED',
                'reference' => $payload['reference'] ?? null,
                'meta'      => $payload,
            ]);

            // Handle job posting payment completion
            $job = $payment->job;
            if ($job) {
                // Handle mfanyakazi job posting payment
                if ($job->poster_type === 'mfanyakazi' && $job->status === 'pending_payment') {
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

                    // Send notifications after transaction
                    if ($job->muhitaji) {
                        $this->notificationService->notifyMuhitajiJobPosted($job, $job->muhitaji);
                    }
                    // Notify all workers about new job
                    $this->notificationService->notifyNewJobPosted($job);
                }
                
                // Handle muhitaji job posting payment
                elseif ($job->poster_type !== 'mfanyakazi' && $job->status === 'posted') {
                    // Notify muhitaji about their posted job
                    if ($job->muhitaji) {
                        $this->notificationService->notifyMuhitajiJobPosted($job, $job->muhitaji);
                    }
                    // Notify all workers about new job
                    $this->notificationService->notifyNewJobPosted($job);
                }
            }
        }
        return response()->json(['ok'=>true]);
    }

    public function apiPoll(Job $job, ZenoPayService $zeno)
    {
        $payment = $job->payment;
        if (!$payment) {
            return response()->json([
                'error' => 'Payment not found',
                'status' => 'not_found'
            ], 404);
        }

        if ($payment->status === 'COMPLETED') {
            return response()->json([
                'done' => true,
                'status' => 'completed',
                'payment' => [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'reference' => $payment->reference,
                    'completed_at' => $payment->updated_at
                ]
            ]);
        }

        $resp = $zeno->checkOrder($payment->order_id);

        if ($resp['ok'] && data_get($resp, 'json.data.0.payment_status') === 'COMPLETED') {
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
            'done' => $payment->status === 'COMPLETED',
            'status' => $payment->status,
            'payment' => [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'order_id' => $payment->order_id
            ]
        ]);
    }
}
