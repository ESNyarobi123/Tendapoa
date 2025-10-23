<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Job;

class WorkerActionsController extends Controller
{
    protected function responseColumn(): ?string
    {
        if (Schema::hasColumn('work_orders', 'mfanyakazi_response')) return 'mfanyakazi_response';
        if (Schema::hasColumn('work_orders', 'worker_response'))     return 'worker_response';
        if (Schema::hasColumn('work_orders', 'assignee_response'))   return 'assignee_response';
        return null;
    }

    public function assigned()
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role, ['mfanyakazi','admin'])) abort(403);

        $jobs = Job::with('muhitaji','category')
            ->where('accepted_worker_id', $u->id)
            ->whereIn('status', ['assigned','in_progress','ready_for_confirmation'])
            ->latest()->paginate(12);

        return view('mfanyakazi.assigned', compact('jobs'));
    }

    public function accept(Job $job)
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['mfanyakazi','admin'])) abort(403);
        if ($job->accepted_worker_id !== $u->id) abort(403);

        if (!$job->completion_code) {
            $job->completion_code = (string) random_int(100000, 999999);
        }

        $job->status = 'in_progress';
        if ($col = $this->responseColumn()) {
            $job->{$col} = 'ok'; // Use shorter value
        }
        $job->save();

        return redirect()->route('mfanyakazi.assigned')->with('status','Umeikubali kazi.');
    }

    public function decline(Job $job)
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['mfanyakazi','admin'])) abort(403);
        if ($job->accepted_worker_id !== $u->id) abort(403);

        if ($col = $this->responseColumn()) {
            $job->{$col} = 'no'; // Use shorter value
        }
        $job->status = 'offered';
        $job->accepted_worker_id = null;
        $job->save();

        return redirect()->route('mfanyakazi.assigned')->with('status','Umeikataa kazi.');
    }

    public function complete(Job $job)
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['mfanyakazi','admin'])) abort(403);
        if ($job->accepted_worker_id !== $u->id) abort(403);

        // Check if job is in the right status
        if ($job->status !== 'in_progress') {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Kazi hii haijaendelea au imekamilika tayari.']);
            }
            return back()->withErrors(['code'=>'Kazi hii haijaendelea au imekamilika tayari.']);
        }

        // Validate the completion code from muhitaji
        request()->validate(['code' => 'required|string|size:6']);
        $providedCode = request('code');
        $actualCode = $job->completion_code;

        \Log::info("Code validation - Provided: {$providedCode}, Actual: {$actualCode}, Job ID: {$job->id}");

        if ($providedCode !== $actualCode) {
            \Log::warning("Code mismatch for job {$job->id}: provided '{$providedCode}' vs actual '{$actualCode}'");
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Code si sahihi. Angalia code uliyopewa na mteja.']);
            }
            return back()->withErrors(['code'=>'Code si sahihi. Angalia code uliyopewa na mteja.']);
        }

        // Mark job as completed and process payment
        $job->status = 'completed';
        $job->completed_at = now();
        if ($col = $this->responseColumn()) {
            $job->{$col} = 'done'; // Use shorter value
        }
        $job->save();

        // Process payment to worker
        $this->processPaymentToWorker($job);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Kazi imethibitishwa! Utapokea malipo yako.']);
        }

        return redirect()->route('mfanyakazi.assigned')->with('status','Kazi imethibitishwa! Utapokea malipo yako.');
    }

    private function processPaymentToWorker(Job $job)
    {
        try {
            $worker = $job->acceptedWorker;
            if (!$worker) {
                \Log::error('No worker found for job: ' . $job->id);
                return;
            }

            $amount = $job->amount; // This uses the getAmountAttribute() method
            \Log::info("Processing payment: TZS {$amount} to worker {$worker->id} for job {$job->id}");
            
            // Check if worker has wallet
            $wallet = $worker->ensureWallet();
            \Log::info("Worker wallet before payment: TZS {$wallet->balance}");
            
            // Add money to worker's wallet
            $walletService = app(\App\Services\WalletService::class);
            $updatedWallet = $walletService->credit($worker, $amount, 'EARN', 'Job completion payment');

            \Log::info("Payment processed successfully: TZS {$amount} to worker {$worker->id} for job {$job->id}");
            \Log::info("Worker wallet after payment: TZS {$updatedWallet->balance}");
            
        } catch (\Exception $e) {
            \Log::error('Payment processing failed for job ' . $job->id . ': ' . $e->getMessage());
            \Log::error('Error details: ' . $e->getTraceAsString());
        }
    }
}
