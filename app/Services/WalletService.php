<?php

namespace App\Services;

use App\Models\{Wallet, WalletTransaction, User};
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WalletService
{
    public function credit(User $user, int $amount, string $type='EARN', ?string $desc=null, array $meta=[]): Wallet
    {
        if ($amount <= 0) throw new InvalidArgumentException('Amount must be > 0');

        return DB::transaction(function () use ($user,$amount,$type,$desc,$meta) {
            $wallet = $user->ensureWallet();
            $wallet->balance += $amount;
            $wallet->save();

            WalletTransaction::create([
                'user_id'=>$user->id,
                'amount'=>$amount,
                'type'=>$type,
                'description'=>$desc,
                'meta'=>$meta,
            ]);

            return $wallet;
        });
    }

    public function debit(User $user, int $amount, string $type='WITHDRAW', ?string $desc=null, array $meta=[]): Wallet
    {
        if ($amount <= 0) throw new InvalidArgumentException('Amount must be > 0');

        return DB::transaction(function () use ($user,$amount,$type,$desc,$meta) {
            $wallet = $user->ensureWallet();
            if ($wallet->balance < $amount) throw new InvalidArgumentException('Insufficient balance');
            $wallet->balance -= $amount;
            $wallet->save();

            WalletTransaction::create([
                'user_id'=>$user->id,
                'amount'=>-1 * $amount,
                'type'=>$type,
                'description'=>$desc,
                'meta'=>$meta,
            ]);

            return $wallet;
        });
    }
}
