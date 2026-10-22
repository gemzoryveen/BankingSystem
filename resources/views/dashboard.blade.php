@extends('layouts.plain')
@section('content')
<style>
    .wrap { max-width: 900px; margin: 24px auto; padding: 0 16px; }
    .cards { display: flex; gap: 16px; }
    .card { flex: 1; padding: 16px; background: #fff; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
    .num { font-size: 1.6rem; font-weight: bold; }
    .small { margin-top: 12px; font-size: .85rem; color: #6b7280; }
</style>
<div class="wrap">
    <div class="cards">
        <div class="card"><div>Total Accounts</div><div id="totalAccounts" class="num">-</div></div>
        <div class="card"><div>Total Money in Bank</div><div id="totalBalance" class="num">-</div></div>
        <div class="card"><div>Transactions Today</div><div id="txToday" class="num">-</div></div>
    </div>
    <p class="small">Last updated: <span id="lastUpdated">-</span></p>
</div>

<script>
async function loadStats() {
    try {
        const r = await fetch('/api/dashboard/stats');
        const d = await r.json();
        document.getElementById('totalAccounts').textContent = d.total_accounts;
        document.getElementById('totalBalance').textContent = '₱' + Number(d.total_balance).toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('txToday').textContent = d.transactions_today;
        document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
    } catch (e) { console.error('Stats error', e); }
}
loadStats();
setInterval(loadStats, 30000);
</script>
@endsection