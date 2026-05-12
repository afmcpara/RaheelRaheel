@extends('layouts.app')

@section('content')
    <section class="auth-card">
        <span class="eyebrow">Create Your Account</span>
        <h2>Get your free US suite</h2>
        <p>Sign up to receive a suite number and start managing your packages.</p>

        <form method="post" action="{{ route('register.attempt') }}" class="form">
            @csrf
            <label>Full name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Jane Doe" required />
            <label>Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
            <label>Password</label>
            <div class="password-field">
                <input type="password" name="password" placeholder="At least 8 characters" required />
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">
                    <svg class="eye-open" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <label>Confirm password</label>
            <div class="password-field">
                <input type="password" name="password_confirmation" placeholder="Repeat password" required />
                <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">
                    <svg class="eye-open" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    <svg class="eye-closed" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                </button>
            </div>
            <button class="btn btn-primary" type="submit">Create Account</button>
        </form>

        <p class="hint" style="margin-top: 14px;">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </p>
    </section>
@endsection
