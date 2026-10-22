<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('account')
            ->whereHas('account', fn ($q) => $q->where('user_id', auth()->id()))
            ->latest()->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        return view('transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => ['required', Rule::exists('accounts', 'id')->where('user_id', auth()->id())],
            'type' => ['required', 'in:deposit,withdrawal'],
            'amount' => ['required', 'numeric', 'min:1'],
            'reference_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Custom validation: sufficient funds check
        $validator->after(function ($v) use ($request) {
            if ($v->errors()->any()) return;
            $account = Account::find($request->account_id);
            if ($request->type === 'withdrawal'
                && $account->balance - $request->amount < $account->minimum_balance) {
                $v->errors()->add('amount', 'Insufficient funds. Balance must stay at or above ₱'
                    . number_format($account->minimum_balance, 2) . '.');
            }
        });

        $data = $validator->validate();

        DB::transaction(fn () => Transaction::create($data));

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded.');
    }
}