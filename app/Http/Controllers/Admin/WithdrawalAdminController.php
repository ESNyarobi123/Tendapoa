<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WithdrawalAdminController extends Controller
{
    public function index()
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') abort(403);

        $items = Withdrawal::with('user')->latest()->paginate(20);
        return view('admin.withdrawals', compact('items'));
    }

    public function markPaid(Withdrawal $withdrawal)
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') abort(403);

        $withdrawal->update(['status'=>'PAID']);
        return back()->with('status','Withdrawal marked as PAID.');
    }

    public function reject(Withdrawal $withdrawal, WalletService $wallets)
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') abort(403);

        if ($withdrawal->status === 'PAID') {
            throw ValidationException::withMessages(['status'=>'Already paid; cannot reject.']);
        }

        // refund to worker wallet if we had debited
        $wallets->credit($withdrawal->user, (int)$withdrawal->amount, 'ADJUST', 'Withdrawal rejected refund');
        $withdrawal->update(['status'=>'REJECTED']);

        return back()->with('status','Withdrawal rejected & refunded.');
    }

    // API Methods
    public function apiIndex()
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') {
            return response()->json([
                'error' => 'Huna ruhusa. Admin pekee.',
                'status' => 'forbidden'
            ], 403);
        }

        $items = Withdrawal::with('user')->latest()->paginate(20);

        return response()->json([
            'withdrawals' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'has_more' => $items->hasMorePages()
            ],
            'status' => 'success'
        ]);
    }

    public function apiMarkPaid(Withdrawal $withdrawal)
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') {
            return response()->json([
                'error' => 'Huna ruhusa. Admin pekee.',
                'status' => 'forbidden'
            ], 403);
        }

        $withdrawal->update(['status'=>'PAID']);

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal marked as PAID.',
            'withdrawal' => [
                'id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'status' => $withdrawal->status,
                'user' => [
                    'id' => $withdrawal->user->id,
                    'name' => $withdrawal->user->name,
                ],
            ],
            'status' => 'success'
        ]);
    }

    public function apiReject(Withdrawal $withdrawal, WalletService $wallets)
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') {
            return response()->json([
                'error' => 'Huna ruhusa. Admin pekee.',
                'status' => 'forbidden'
            ], 403);
        }

        if ($withdrawal->status === 'PAID') {
            return response()->json([
                'error' => 'Already paid; cannot reject.',
                'status' => 'error'
            ], 400);
        }

        // refund to worker wallet if we had debited
        $wallets->credit($withdrawal->user, (int)$withdrawal->amount, 'ADJUST', 'Withdrawal rejected refund');
        $withdrawal->update(['status'=>'REJECTED']);

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal rejected & refunded.',
            'withdrawal' => [
                'id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'status' => $withdrawal->status,
                'user' => [
                    'id' => $withdrawal->user->id,
                    'name' => $withdrawal->user->name,
                ],
            ],
            'status' => 'success'
        ]);
    }
}
