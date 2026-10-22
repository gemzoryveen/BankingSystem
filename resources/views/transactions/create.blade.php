@extends('layouts.plain')
@section('content')
<style>
    .box { max-width: 520px; margin: 24px auto; padding: 20px; background: #fff; border-radius: 6px; }
    .box input, .box select, .box textarea { width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .error { background: #fde2e2; color: #b00020; padding: 10px; margin-bottom: 12px; border-radius: 4px; }
    .btn { padding: 8px 16px; background: #2563eb; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
</style>
<div class="box">
    <h2>New Transaction</h2>
    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('transactions.store') }}">
        @csrf
        <select name="account_id">
            @foreach ($accounts as $a)
                <option value="{{ $a->id }}">{{ $a->account_number }} (₱{{ number_format($a->balance, 2) }})</option>
            @endforeach
        </select>
        <select name="type">
            <option value="deposit">Deposit</option>
            <option value="withdrawal">Withdrawal</option>
        </select>
        <input type="number" step="0.01" min="1" name="amount" placeholder="Amount" value="{{ old('amount') }}">
        <input name="reference_number" placeholder="Reference number (optional)" value="{{ old('reference_number') }}">
        <textarea name="notes" placeholder="Notes (optional)">{{ old('notes') }}</textarea>
        <button class="btn">Submit</button>
    </form>
</div>
@endsection