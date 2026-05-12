@extends($user->role === 'admin' ? 'layouts.admin' : 'layouts.client')

@section('page_title', 'My Account')
@section('page_subtitle', 'Update your personal details and account password.')

@section('content')
    @if(session('profile_success') || session('password_success'))
        <div class="flash-banner success">
            {{ session('profile_success') ?? session('password_success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flash-banner error">
            <strong>Please fix the following:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-row">
        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Profile Information</h2>
                    <p>Update your name and email address.</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="form">
                @csrf
                @method('patch')

                <label>Full name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required />

                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required />

                @if($user->suite_number)
                    <label>Suite Number</label>
                    <input type="text" value="{{ $user->suite_number }}" disabled style="background: var(--canvas); color: var(--muted); cursor: not-allowed;" />
                    <p class="hint" style="margin-top: -8px;">Your suite number is permanent and cannot be changed.</p>
                @endif

                <button class="btn btn-primary" type="submit">Save Changes</button>
            </form>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Change Password</h2>
                    <p>Use a strong password (minimum 8 characters).</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.password') }}" class="form">
                @csrf
                @method('patch')

                <label>Current password</label>
                <div class="password-field">
                    <input type="password" name="current_password" placeholder="Enter your current password" required />
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">
                        <svg class="eye-open" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                <label>New password</label>
                <div class="password-field">
                    <input type="password" name="password" placeholder="At least 8 characters" required />
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">
                        <svg class="eye-open" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                <label>Confirm new password</label>
                <div class="password-field">
                    <input type="password" name="password_confirmation" placeholder="Repeat new password" required />
                    <button type="button" class="password-toggle" data-password-toggle aria-label="Show password">
                        <svg class="eye-open" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>

                <button class="btn btn-primary" type="submit">Update Password</button>
            </form>
        </section>
    </div>
@endsection
