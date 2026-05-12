<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Client · Ship2Aruba' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0a1832">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body client-body">
<div class="admin-shell">
    <aside class="admin-side client-side">
        <a href="{{ route('client.dashboard') }}" class="admin-brand">
            @include('partials.logo')
            <span>Ship2Aruba</span>
        </a>

        <nav class="admin-nav">
            <span class="admin-nav-label">My Account</span>
            <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                Dashboard
            </a>

            <span class="admin-nav-label">My Shipping</span>
            <a href="{{ route('client.packages') }}" class="{{ request()->routeIs('client.packages*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7L12 12l8.7-5"/><path d="M12 22V12"/></svg>
                My Packages
            </a>
            <a href="{{ route('client.shipments') }}" class="{{ request()->routeIs('client.shipments*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                Shipment Status
            </a>

            <span class="admin-nav-label">Settings</span>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                My Account
            </a>
        </nav>

        <div class="admin-side-foot">
            <div class="admin-user">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>Suite {{ auth()->user()->suite_number ?? '—' }}</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn btn-light btn-block" type="submit">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <h1>@yield('page_title', 'Dashboard')</h1>
                <p class="admin-sub">@yield('page_subtitle', 'Track and manage your packages.')</p>
            </div>
            <div class="admin-topbar-actions">
                <span class="admin-pill">{{ now()->format('M d, Y') }}</span>
                <a href="{{ route('landing') }}" class="btn btn-light">View Site</a>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert error">
                    @if($errors->count() === 1)
                        {{ $errors->first() }}
                    @else
                        <strong>Please fix the following:</strong>
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
