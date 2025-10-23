<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WithdrawalController extends Controller
{
    public function requestForm()
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['mfanyakazi','admin'])) abort(403);

        $wallet = $u->ensureWallet();
        return view('mfanyakazi.withdraw', compact('wallet'));
    }

    public function submit(Request $r, WalletService $wallets)
    {
        $u = Auth::user();
        if (!$u || !in_array($u->role,['mfanyakazi','admin'])) abort(403);

        $data = $r->validate([
            'amount'=>['required','integer','min:5000'],
            'phone_number'=>['required','string','min:10'],
            'registered_name'=>['required','string','min:2'],
            'network_type'=>['required','string','in:vodacom,tigo,airtel,halotel,ttcl'],
            'method'=>['required','string'],
        ]);

        $wallet = $u->ensureWallet();
        if ($wallet->balance < (int)$data['amount']) {
            throw ValidationException::withMessages(['amount'=>'Hauna salio la kutosha.']);
        }

        // debit immediately
        $wallets->debit($u, (int)$data['amount'], 'WITHDRAW', 'Withdrawal request');

        Withdrawal::create([
            'user_id'=>$u->id,
            'amount'=>(int)$data['amount'],
            'status'=>'PROCESSING',
            'method'=>$data['method'],
            'account'=>$data['phone_number'],
            'registered_name'=>$data['registered_name'],
            'network_type'=>$data['network_type'],
        ]);

        return redirect()->route('dashboard')->with('status','Withdrawal imewasilishwa. Subiri uthibitisho wa Admin.');
    }
}
