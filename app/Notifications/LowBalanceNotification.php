<?php

namespace App\Notifications;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowBalanceNotification extends Notification
{
    use Queueable;

    public function __construct(public Account $account)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'account_id' => $this->account->id,
            'account_number' => $this->account->account_number,
            'balance' => (float) $this->account->balance,
            'minimum_balance' => (float) $this->account->minimum_balance,
            'message' => "Low balance: account {$this->account->account_number} is at ₱"
                . number_format($this->account->balance, 2)
                . ' (minimum ₱' . number_format($this->account->minimum_balance, 2) . ').',
        ];
    }
}