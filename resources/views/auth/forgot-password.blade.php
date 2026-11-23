@extends('admin.layouts.auth')
@section('title', 'Forgot password')

@section('content')
<div class="auth-card">
    <div class="ac-icon"><x-icon name="key" /></div>
    <h2>Forgot your password?</h2>
    <p class="ac-sub">Enter your email and we will send you a link to choose a new one.</p>
    @if (session('status'))<div class="alert alert-success small">{{ session('status') }}</div>@endif
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label" for="email">Email</label>
            <div class="auth-field"><x-icon name="envelope" set="regular" /><input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus></div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-gradient btn-lg w-100">Email the reset link</button>
        <p class="text-center small mt-3 mb-0"><a href="{{ route('login') }}">Back to sign in</a></p>
    </form>
</div>
@endsection
