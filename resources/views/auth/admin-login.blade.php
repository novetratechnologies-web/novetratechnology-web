@extends('layouts.empty')

@section('content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}?v=1">
@endpush

<main class="admin-login" aria-labelledby="admin-login-title">
    <div class="admin-login-shell">
        <aside class="admin-login-brand">
            <div class="admin-login-brand-top">
                <a class="admin-login-logo" href="{{ route('web.home') }}" aria-label="IGM Church home">
                    <img src="{{ asset('uploads/brand/igm-church-logo.png') }}" alt="International Gospel Ministries">
                </a>
                <div class="admin-login-brand-copy">
                    <div class="admin-login-tag">Administration portal</div>
                    <h1>Serve the church with clarity.</h1>
                    <p>Manage people, ministries, communications, and church updates from one secure workspace.</p>
                    <div class="admin-login-trust">
                        <span class="admin-login-trust-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3 5 6v5c0 4.7 2.9 8.2 7 10 4.1-1.8 7-5.3 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg></span>
                        <span><strong>Private staff access</strong><br>For authorized church administrators and staff.</span>
                    </div>
                </div>
            </div>
            <div class="admin-login-brand-bottom">International Gospel Ministries · Migori, Kenya</div>
        </aside>

        <section class="admin-login-main">
            <div class="admin-login-form-wrap">
                <div class="admin-login-eyebrow">Welcome back</div>
                <h2 class="admin-login-heading" id="admin-login-title">Sign in to your workspace</h2>
                <p class="admin-login-subtitle">Use your staff account details to continue to the administration portal.</p>

                @if($errors->any())
                    <div class="admin-login-error" role="alert" aria-live="polite">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5m0 3h.01"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" aria-label="Administration sign in">
                    @csrf
                    <div class="admin-login-field">
                        <label class="admin-login-label" for="email">Work email</label>
                        <div class="admin-login-input-wrap">
                            <svg class="admin-login-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg>
                            <input class="admin-login-input" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@church.org" aria-describedby="email-error">
                        </div>
                        @error('email')<span id="email-error" class="admin-login-footer" style="display:block;margin-top:6px;color:#a43731;text-align:left">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-login-field">
                        <label class="admin-login-label" for="password">Password</label>
                        <div class="admin-login-input-wrap">
                            <svg class="admin-login-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input class="admin-login-input" id="password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
                            <button class="admin-login-eye" type="button" id="toggle-password" aria-label="Show password" aria-pressed="false">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M2.5 12s3.3-6 9.5-6 9.5 6 9.5 6-3.3 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.6"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="admin-login-options">
                        <label class="admin-login-remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Keep me signed in</label>
                        <span class="admin-login-secure"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3 5 6v5c0 4.7 2.9 8.2 7 10 4.1-1.8 7-5.3 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>Secure connection</span>
                    </div>
                    <button class="admin-login-submit" type="submit">Sign in to administration <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></button>
                </form>

                <div class="admin-login-divider"></div>
                <p class="admin-login-member">Church member or guest? <a href="{{ route('web.guest.login') }}">Sign in to the church website</a></p>
                <p class="admin-login-footer">Need help accessing your staff account? Contact the church office.</p>
            </div>
        </section>
    </div>
</main>

<script>
    (() => {
        const button = document.getElementById('toggle-password');
        const input = document.getElementById('password');
        if (!button || !input) return;
        button.addEventListener('click', () => {
            const reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(reveal));
            button.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
        });
    })();
</script>
@endsection
