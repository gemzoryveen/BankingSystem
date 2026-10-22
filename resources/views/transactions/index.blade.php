@extends('layouts.plain')
@section('content')
<style>
    .wrap { max-width: 900px; margin: 24px auto; padding: 0 16px; }
    .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .btn { padding: 6px 14px; background: #2563eb; color: #fff; border-radius: 4px; text-decoration: none; }
    .ok { background: #dcfce7; color: #166534; padding: 10px; margin-bottom: 12px; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; background: #fff; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
</style>
<div class="wrap">
    <div class="top">
        <h2>Transactions</h2>
        <a class="btn" href="{{ route('transactions.create') }}">New</a>
    </div>
    @if (session('success')) <div class="ok">{{ session('success') }}</div> @endif
    <table>
        <tr><th>Date</th><th>Account</th><th>Type</th><th>Amount</th><th>Reference</th></tr>
        @foreach ($transactions as $t)
        <tr>
            <td>{{ $t->created_at->format('M d, Y h:i A') }}</td>
            <td>{{ $t->account->account_number }}</td>
            <td>{{ ucfirst($t->type) }}</td>
            <td>₱{{ number_format($t->amount, 2) }}</td>
            <td>{{ $t->reference_number ?? '-' }}</td>
        </tr>
        @endforeach
    </table>
    <div style="margin-top:12px">{{ $transactions->links() }}</div>
</div>
@endsection