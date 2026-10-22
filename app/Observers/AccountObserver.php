<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Transaction;
use App\Notifications\LowBalanceNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AccountObserver
{
    // deposit adds, withdrawal and transfer subtract
    private function delta(Transaction $t): float
    {
        $amount = (float) $t->amount;

        return $t->type === 'deposit' ? $amount : -$amount;
    }

    // BEFORE the row is saved: block anything that breaks the minimum balance
    public function creating(Transaction $transaction): void
    {
        $account = Account::lockForUpdate()->findOrFail($transaction->account_id);
        $delta = $this->delta($transaction);
        $newBalance = round((float) $account->balance + $delta, 2);

        if ($delta < 0 && $newBalance < (float) $account->minimum_balance) {
            throw ValidationException::withMessages([
                'amount' => 'Insufficient funds. Balance: ₱' . number_format($account->balance, 2)
                    . ', minimum balance: ₱' . number_format($account->minimum_balance, 2) . '.',
            ]);
        }
    }

    // AFTER the row is saved: update the balance, then alert if it reached the minimum
    public function created(Transaction $transaction): void
    {
        [$account, $previous] = DB::transaction(function () use ($transaction) {
            $account = Account::lockForUpdate()->findOrFail($transaction->account_id);
            $previous = (float) $account->balance;

            $account->balance = round($previous + $this->delta($transaction), 2);
            $account->save();

            return [$account, $previous];
        });

        // alert only when the balance CROSSES down to the minimum (no duplicate alerts)
        $minimum = (float) $account->minimum_balance;

        if ($this->delta($transaction) < 0 && $previous > $minimum && (float) $account->balance <= $minimum) {
            $account->user->notify(new LowBalanceNotification($account));
        }
    }
}