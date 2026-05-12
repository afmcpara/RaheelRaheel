<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Ship2Aruba' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#0a1832">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header class="topbar">
    <div class="container nav">
        <a href="{{ route('landing') }}" class="brand">
            @include('partials.logo')
            <span>Ship2Aruba</span>
        </a>
        <div class="nav-links">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.packages') }}">Packages</a>
                    <a href="{{ route('admin.invoices') }}">Invoice Queue</a>
                    <a href="{{ route('admin.ship-requests') }}">Ship Requests</a>
                @else
                    <a href="{{ route('client.dashboard') }}">Dashboard</a>
                    <a href="{{ route('client.packages') }}">My Packages</a>
                    <a href="{{ route('client.shipments') }}">Shipment Status</a>
                @endif
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="btn btn-light" type="submit">Logout</button>
                </form>
            @else
                @if(request()->routeIs('landing'))
                    <a href="#features">Features</a>
                    <a href="#how-it-works">How It Works</a>
                    <a href="#pricing">Pricing</a>
                    <a href="#faq">FAQ</a>
                @endif
                <a href="{{ route('login') }}">Sign in</a>
                <a class="btn btn-primary" href="{{ route('register') }}">Get Started</a>
            @endauth
        </div>
    </div>
</header>

<main class="container page {{ request()->routeIs('landing') ? 'no-pad-top' : '' }}">
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
<footer class="site-footer">
    <div class="container footer-wrap">
        <div class="footer-brand">
            <h3 class="footer-brand-row">
                @include('partials.logo')
                <span>Ship2Aruba</span>
            </h3>
            <p>Your trusted partner for US-to-Aruba package forwarding.</p>
            <div class="footer-contact">
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Your business address
                </span>
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.9 19.9 0 0 1-8.6-3.1 19.6 19.6 0 0 1-6-6A19.9 19.9 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.4 2.1L8 9.6a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2z"/></svg>
                    Your contact number
                </span>
                <span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                    Your contact email
                </span>
            </div>
            <div class="footer-social">
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                </a>
                <a href="#" aria-label="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 3.5A10.5 10.5 0 0 0 3.6 16L2 22l6.2-1.6A10.5 10.5 0 1 0 20.5 3.5zM12 20a8.5 8.5 0 0 1-4.3-1.2l-.3-.2-3.7 1 1-3.6-.2-.4A8.5 8.5 0 1 1 12 20zm4.7-6.4c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.6.1a7 7 0 0 1-3.4-3c-.3-.4.3-.4.7-1.3.1-.2 0-.3 0-.5l-.8-1.9c-.2-.5-.5-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-1 1-1 2.5s1 2.9 1.1 3.1c.2.2 2 3.1 4.9 4.3 1.8.7 2.5.8 3.4.7.6-.1 1.7-.7 1.9-1.4.2-.7.2-1.3.2-1.4 0-.1-.2-.2-.5-.3z"/></svg>
                </a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#pricing">Pricing</a>
        </div>
        <div class="footer-col">
            <h4>Resources</h4>
            <a href="#testimonials">Testimonials</a>
            <a href="{{ route('login') }}">Sign In</a>
            <a href="{{ route('register') }}">Create Account</a>
        </div>
        <div class="footer-col">
            <h4>Contact</h4>
            <a href="#">Your business email</a>
            <a href="#">Your phone number</a>
            <a href="#">Your business address</a>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container footer-bottom-wrap">
            <p>© {{ date('Y') }} Ship2Aruba. All rights reserved.</p>
            <p>Crafted for clarity, built for trust.</p>
        </div>
    </div>
</footer>
</body>
</html>
