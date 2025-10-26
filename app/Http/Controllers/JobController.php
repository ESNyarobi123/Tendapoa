<?php

namespace App\Http\Controllers;

use App\Models\{Job, Category, Wallet, WalletTransaction};
use App\Services\ZenoPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobController extends Controller
{
    private function ensureMuhitajiOrAdmin(): void
    {
        $role = Auth::user()->role ?? null;
        if (!in_array($role, ['muhitaji','admin'], true)) {
            abort(403, 'Huna ruhusa (muhitaji/admin tu).');
        }
    }

    public function create()
    {
        $this->ensureMuhitajiOrAdmin();
        return view('jobs.create', ['categories'=>Category::all()]);
    }

    public function store(Request $r, ZenoPayService $zeno)
    {
        $this->ensureMuhitajiOrAdmin();

        $r->validate([
            'title'       => ['required','max:120'],
            'category_id' => ['required','exists:categories,id'],
            'price'       => ['required','integer','min:500'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
            'phone'       => ['required','regex:/^(0[6-7]\d{8}|255[6-7]\d{8})$/'],
        ],[
            'phone.regex' => 'Weka 06/07xxxxxxxx au 2556/2557xxxxxxxx.',
            'lat.required' => 'Eneo la kazi ni lazima. Tafadhali weka pini eneo la kazi kwenye ramani.',
            'lng.required' => 'Eneo la kazi ni lazima. Tafadhali weka pini eneo la kazi kwenye ramani.',
            'lat.between' => 'Latitude si sahihi. Tafadhali weka eneo sahihi kwenye ramani.',
            'lng.between' => 'Longitude si sahihi. Tafadhali weka eneo sahihi kwenye ramani.',
        ]);

        $job = Job::create([
            'user_id'     => Auth::id(),
            'category_id' => (int) $r->input('category_id'),
            'title'       => $r->input('title'),
            'description' => $r->input('description'),
            'price'       => (int) $r->input('price'),
            'lat'         => (float) $r->input('lat'),
            'lng'         => (float) $r->input('lng'),
            'address_text'=> $r->input('address_text'),
            'status'      => 'posted',
            'published_at'=> now(),
        ]);

        $orderId = (string) Str::ulid();
        $job->payment()->create([
            'order_id' => $orderId,
            'amount'   => $job->price,
            'status'   => 'PENDING',
        ]);

        $buyer = Auth::user();
        $payload = [
            'order_id'    => $orderId,
            'buyer_email' => $buyer?->email ?? 'client@tendapoa.local',
            'buyer_name'  => $buyer?->name  ?? 'Client',
            'buyer_phone' => $r->input('phone'),
            'amount'      => $job->price,
            'webhook_url' => route('zeno.webhook'),
        ];

        $res = $zeno->startPayment($payload);
        if (!$res['ok']) {
            return back()->withErrors(['pay'=>'Imeshindikana kuanzisha malipo. Jaribu tena.']);
        }

        return redirect()->route('jobs.pay.wait', $job);
    }

    public function edit(Job $job)
    {
        $this->ensureMuhitajiOrAdmin();
        
        // Only allow editing if job is posted or assigned (not in progress or completed)
        if (!in_array($job->status, ['posted', 'assigned'])) {
            return back()->withErrors(['edit' => 'Huwezi kubadilisha kazi ambayo imeanza au imekamilika.']);
        }
        
        // Only job owner can edit
        if ($job->user_id !== Auth::id()) {
            abort(403, 'Huna ruhusa ya kubadilisha kazi hii.');
        }
        
        return view('jobs.edit', [
            'job' => $job,
            'categories' => Category::all()
        ]);
    }

    public function update(Request $r, Job $job, ZenoPayService $zeno)
    {
        $this->ensureMuhitajiOrAdmin();
        
        // Only allow editing if job is posted or assigned
        if (!in_array($job->status, ['posted', 'assigned'])) {
            return back()->withErrors(['edit' => 'Huwezi kubadilisha kazi ambayo imeanza au imekamilika.']);
        }
        
        // Only job owner can edit
        if ($job->user_id !== Auth::id()) {
            abort(403, 'Huna ruhusa ya kubadilisha kazi hii.');
        }

        $r->validate([
            'title'       => ['required','max:120'],
            'category_id' => ['required','exists:categories,id'],
            'price'       => ['required','integer','min:500'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
        ],[
            'lat.required' => 'Eneo la kazi ni lazima. Tafadhali weka pini eneo la kazi kwenye ramani.',
            'lng.required' => 'Eneo la kazi ni lazima. Tafadhali weka pini eneo la kazi kwenye ramani.',
            'lat.between' => 'Latitude si sahihi. Tafadhali weka eneo sahihi kwenye ramani.',
            'lng.between' => 'Longitude si sahihi. Tafadhali weka eneo sahihi kwenye ramani.',
        ]);

        $newPrice = (int) $r->input('price');
        $oldPrice = $job->price;
        $priceDifference = $newPrice - $oldPrice;

        // Validate price increase only
        if ($priceDifference < 0) {
            return back()->withErrors(['price' => 'Huwezi kupunguza bei ya kazi. Unaweza kuongeza tu.']);
        }

        // Update job details
        $job->update([
            'title'       => $r->input('title'),
            'category_id' => (int) $r->input('category_id'),
            'description' => $r->input('description'),
            'price'       => $newPrice,
            'lat'         => (float) $r->input('lat'),
            'lng'         => (float) $r->input('lng'),
            'address_text'=> $r->input('address_text'),
        ]);

        // If price increased, process additional payment
        if ($priceDifference > 0) {
            $orderId = (string) Str::ulid();
            $job->payment()->create([
                'order_id' => $orderId,
                'amount'   => $priceDifference,
                'status'   => 'PENDING',
            ]);

            $buyer = Auth::user();
            $payload = [
                'order_id'    => $orderId,
                'buyer_email' => $buyer?->email ?? 'client@tendapoa.local',
                'buyer_name'  => $buyer?->name  ?? 'Client',
                'buyer_phone' => $buyer?->phone ?? '000000000',
                'amount'      => $priceDifference,
                'webhook_url' => route('zeno.webhook'),
            ];

            $res = $zeno->startPayment($payload);
            if (!$res['ok']) {
                return back()->withErrors(['pay'=>'Imeshindikana kuanzisha malipo ya ziada. Jaribu tena.']);
            }

            return redirect()->route('jobs.pay.wait', $job)
                ->with('success', 'Kazi imebadilishwa! Malipo ya ziada ya TZS ' . number_format($priceDifference) . ' yanahitajika.');
        }

        return redirect()->route('my.jobs')
            ->with('success', 'Kazi imebadilishwa kwa mafanikio!');
    }

    // API Methods for Job Editing
    public function apiEdit(Job $job)
    {
        $this->ensureMuhitajiOrAdmin();
        
        // Only allow editing if job is posted or assigned
        if (!in_array($job->status, ['posted', 'assigned'])) {
            return response()->json([
                'error' => 'Huwezi kubadilisha kazi ambayo imeanza au imekamilika.',
                'status' => 'error'
            ], 400);
        }
        
        // Only job owner can edit
        if ($job->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Huna ruhusa ya kubadilisha kazi hii.',
                'status' => 'forbidden'
            ], 403);
        }
        
        return response()->json([
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'price' => $job->price,
                'category_id' => $job->category_id,
                'lat' => $job->lat,
                'lng' => $job->lng,
                'address_text' => $job->address_text,
                'status' => $job->status,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
            ],
            'categories' => \App\Models\Category::all(),
            'can_edit' => true,
            'status' => 'success'
        ]);
    }

    public function apiUpdate(Request $r, Job $job, ZenoPayService $zeno)
    {
        $this->ensureMuhitajiOrAdmin();
        
        // Only allow editing if job is posted or assigned
        if (!in_array($job->status, ['posted', 'assigned'])) {
            return response()->json([
                'error' => 'Huwezi kubadilisha kazi ambayo imeanza au imekamilika.',
                'status' => 'error'
            ], 400);
        }
        
        // Only job owner can edit
        if ($job->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Huna ruhusa ya kubadilisha kazi hii.',
                'status' => 'forbidden'
            ], 403);
        }

        $r->validate([
            'title'       => ['required','max:120'],
            'category_id' => ['required','exists:categories,id'],
            'price'       => ['required','integer','min:500'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
        ],[
            'lat.required' => 'Eneo la kazi ni lazima.',
            'lng.required' => 'Eneo la kazi ni lazima.',
            'lat.between' => 'Latitude si sahihi.',
            'lng.between' => 'Longitude si sahihi.',
        ]);

        $newPrice = (int) $r->input('price');
        $oldPrice = $job->price;
        $priceDifference = $newPrice - $oldPrice;

        // Validate price increase only
        if ($priceDifference < 0) {
            return response()->json([
                'error' => 'Huwezi kupunguza bei ya kazi. Unaweza kuongeza tu.',
                'status' => 'validation_error',
                'field' => 'price'
            ], 422);
        }

        // Update job details
        $job->update([
            'title'       => $r->input('title'),
            'category_id' => (int) $r->input('category_id'),
            'description' => $r->input('description'),
            'price'       => $newPrice,
            'lat'         => (float) $r->input('lat'),
            'lng'         => (float) $r->input('lng'),
            'address_text'=> $r->input('address_text'),
        ]);

        $response = [
            'message' => 'Kazi imebadilishwa kwa mafanikio!',
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'price' => $job->price,
                'status' => $job->status,
                'updated_at' => $job->updated_at,
            ],
            'status' => 'success'
        ];

        // If price increased, process additional payment
        if ($priceDifference > 0) {
            $orderId = (string) Str::ulid();
            $job->payment()->create([
                'order_id' => $orderId,
                'amount'   => $priceDifference,
                'status'   => 'PENDING',
            ]);

            $buyer = Auth::user();
            $payload = [
                'order_id'    => $orderId,
                'buyer_email' => $buyer?->email ?? 'client@tendapoa.local',
                'buyer_name'  => $buyer?->name  ?? 'Client',
                'buyer_phone' => $buyer?->phone ?? '000000000',
                'amount'      => $priceDifference,
                'webhook_url' => route('zeno.webhook'),
            ];

            $res = $zeno->startPayment($payload);
            if (!$res['ok']) {
                return response()->json([
                    'error' => 'Imeshindikana kuanzisha malipo ya ziada. Jaribu tena.',
                    'status' => 'payment_error'
                ], 500);
            }

            $response['payment_required'] = true;
            $response['payment_amount'] = $priceDifference;
            $response['payment_url'] = route('jobs.pay.wait', $job);
            $response['message'] = 'Kazi imebadilishwa! Malipo ya ziada ya TZS ' . number_format($priceDifference) . ' yanahitajika.';
        }

        return response()->json($response);
    }

    public function apiStore(Request $r, ZenoPayService $zeno)
    {
        $this->ensureMuhitajiOrAdmin();

        $r->validate([
            'title'       => ['required','max:120'],
            'category_id' => ['required','exists:categories,id'],
            'price'       => ['required','integer','min:500'],
            'lat'         => ['required','numeric','between:-90,90'],
            'lng'         => ['required','numeric','between:-180,180'],
            'phone'       => ['required','regex:/^(0[6-7]\d{8}|255[6-7]\d{8})$/'],
        ],[
            'phone.regex' => 'Weka 06/07xxxxxxxx au 2556/2557xxxxxxxx.',
            'lat.required' => 'Eneo la kazi ni lazima.',
            'lng.required' => 'Eneo la kazi ni lazima.',
            'lat.between' => 'Latitude si sahihi.',
            'lng.between' => 'Longitude si sahihi.',
        ]);

        $job = Job::create([
            'user_id'     => Auth::id(),
            'category_id' => (int) $r->input('category_id'),
            'title'       => $r->input('title'),
            'description' => $r->input('description'),
            'price'       => (int) $r->input('price'),
            'lat'         => (float) $r->input('lat'),
            'lng'         => (float) $r->input('lng'),
            'address_text'=> $r->input('address_text'),
            'status'      => 'posted',
            'published_at'=> now(),
        ]);

        $orderId = (string) Str::ulid();
        $job->payment()->create([
            'order_id' => $orderId,
            'amount'   => $job->price,
            'status'   => 'PENDING',
        ]);

        $buyer = Auth::user();
        $payload = [
            'order_id'    => $orderId,
            'buyer_email' => $buyer?->email ?? 'client@tendapoa.local',
            'buyer_name'  => $buyer?->name  ?? 'Client',
            'buyer_phone' => $r->input('phone'),
            'amount'      => $job->price,
            'webhook_url' => route('zeno.webhook'),
        ];

        $res = $zeno->startPayment($payload);
        if (!$res['ok']) {
            return response()->json([
                'error' => 'Imeshindikana kuanzisha malipo. Jaribu tena.',
                'status' => 'payment_error'
            ], 500);
        }

        return response()->json([
            'message' => 'Kazi imechapishwa! Malipo yanahitajika.',
            'job' => [
                'id' => $job->id,
                'title' => $job->title,
                'price' => $job->price,
                'status' => $job->status,
            ],
            'payment_url' => route('jobs.pay.wait', $job),
            'status' => 'success'
        ]);
    }

    public function wait(Job $job)
    {
        $this->ensureMuhitajiOrAdmin();
        $job->load('payment');
        return view('jobs.wait', ['job'=>$job]);
    }

    // Mfanyakazi job posting methods
    public function createMfanyakazi()
    {
        $user = Auth::user();
        if ($user->role !== 'mfanyakazi') {
            abort(403, 'Huna ruhusa. Mfanyakazi tu.');
        }
        
        return view('jobs.create-mfanyakazi', ['categories' => Category::all()]);
    }

    public function storeMfanyakazi(Request $request, ZenoPayService $zeno)
    {
        $user = Auth::user();
        if ($user->role !== 'mfanyakazi') {
            abort(403, 'Huna ruhusa. Mfanyakazi tu.');
        }

        $request->validate([
            'title'       => ['required', 'max:120'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'min:20'],
            'price'       => ['required', 'integer', 'min:1000'],
            'lat'         => ['required', 'numeric', 'between:-90,90'],
            'lng'         => ['required', 'numeric', 'between:-180,180'],
            'phone'       => ['required', 'regex:/^(0[6-7]\d{8}|255[6-7]\d{8})$/'],
        ], [
            'phone.regex' => 'Weka 06/07xxxxxxxx au 2556/2557xxxxxxxx.',
            'description.min' => 'Maelezo lazima yawe angalau herufi 20.',
        ]);

        $postingFee = 2000; // TZS 2,000 posting fee
        $userWallet = $user->ensureWallet();

        // Check if user has enough balance
        if ($userWallet->balance >= $postingFee) {
            // Deduct from wallet
            return $this->processWalletPayment($request, $postingFee, $userWallet);
        } else {
            // Use ZenoPay for payment
            return $this->processZenoPayment($request, $postingFee, $zeno);
        }
    }

    private function processWalletPayment(Request $request, $postingFee, $userWallet)
    {
        return DB::transaction(function () use ($request, $postingFee, $userWallet) {
            // Create the job
            $job = Job::create([
                'user_id'      => Auth::id(),
                'category_id'  => (int) $request->input('category_id'),
                'title'        => $request->input('title'),
                'description'  => $request->input('description'),
                'price'        => (int) $request->input('price'),
                'lat'          => (float) $request->input('lat'),
                'lng'          => (float) $request->input('lng'),
                'address_text' => $request->input('address_text'),
                'status'       => 'posted',
                'published_at' => now(),
                'poster_type'  => 'mfanyakazi',
                'posting_fee'  => $postingFee,
            ]);

            // Deduct posting fee from wallet
            $userWallet->decrement('balance', $postingFee);

            // Record transaction
            WalletTransaction::create([
                'user_id' => Auth::id(),
                'type'    => 'debit',
                'amount'  => $postingFee,
                'description' => "Job posting fee for: {$job->title}",
                'reference'   => "JOB_POST_{$job->id}",
            ]);

            return redirect()->route('dashboard')->with('success', 'Kazi imechapishwa kwa mafanikio! Ada ya TZS ' . number_format($postingFee) . ' imekatwa kutoka kwenye salio lako.');
        });
    }

    private function processZenoPayment(Request $request, $postingFee, ZenoPayService $zeno)
    {
        // Create job with pending payment
        $job = Job::create([
            'user_id'      => Auth::id(),
            'category_id'  => (int) $request->input('category_id'),
            'title'        => $request->input('title'),
            'description'  => $request->input('description'),
            'price'        => (int) $request->input('price'),
            'lat'          => (float) $request->input('lat'),
            'lng'          => (float) $request->input('lng'),
            'address_text' => $request->input('address_text'),
            'status'       => 'pending_payment',
            'poster_type'  => 'mfanyakazi',
            'posting_fee'  => $postingFee,
        ]);

        $orderId = (string) Str::ulid();
        $job->payment()->create([
            'order_id' => $orderId,
            'amount'   => $postingFee,
            'status'   => 'PENDING',
        ]);

        $user = Auth::user();
        $payload = [
            'order_id'    => $orderId,
            'buyer_email' => $user->email ?? 'worker@tendapoa.local',
            'buyer_name'  => $user->name ?? 'Worker',
            'buyer_phone' => $request->input('phone'),
            'amount'      => $postingFee,
            'webhook_url' => route('zeno.webhook'),
        ];

        $res = $zeno->startPayment($payload);
        if (!$res['ok']) {
            return back()->withErrors(['pay' => 'Imeshindikana kuanzisha malipo. Jaribu tena.']);
        }

        return redirect()->route('jobs.pay.wait', $job);
    }
}
