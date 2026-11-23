@extends('admin.layouts.auth')
@section('title', 'Sign in')

@section('content')
<div class="auth-card">
    <div class="ac-icon"><x-icon name="right-to-bracket" /></div>
    <h2>Welcome back</h2>
    <p class="ac-sub">Sign in to manage the website.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <div class="auth-field"><x-icon name="envelope" set="regular" /><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" required autofocus></div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <div class="auth-field"><x-icon name="lock" /><input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required><button type="button" class="field-eye" data-toggle-password aria-label="Show the password"><x-icon name="eye" set="regular" /></button></div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                <label class="form-check-label small" for="remember">Keep me signed in</label>
            </div>
            @if (Route::has('password.request'))
                <a class="small" href="{{ route('password.request') }}">Forgot your password?</a>
            @endif
        </div>
        <button type="submit" class="btn btn-gradient btn-lg w-100">Sign in <x-icon name="arrow-right" class="ms-1" /></button>
    </form>
</div>
@endsection
