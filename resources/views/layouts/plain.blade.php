<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Banking System</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f3f4f6; color: #111827; }
        nav { background: #1e3a8a; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; height: 56px; }
        nav .links a { color: #fff; text-decoration: none; margin-right: 20px; padding: 6px 0; }
        nav .links a.active { border-bottom: 2px solid #fff; }
        nav .brand { color: #fff; font-weight: bold; margin-right: 28px; }
        nav .user { color: #fff; display: flex; align-items: center; gap: 12px; }
        nav .user button { background: #fff; color: #1e3a8a; border: 0; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <nav>
        <div class="links">
            <span class="brand">Simple Bank</span>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">Transactions</a>
        </div>
        <div class="user">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>

    @yield('content')
</body>
</html>