@extends('admin.layouts.auth')
@section('title', 'Choose a new password')

@section('content')
<div class="auth-card">
    <div class="ac-icon"><x-icon name="lock" /></div>
    <h2>Choose a new password</h2>
    <p class="ac-sub">Use at least 8 characters.</p>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <div class="auth-field"><x-icon name="envelope" set="regular" /><input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username"></div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">New password</label>
            <div class="auth-field"><x-icon name="lock" /><input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password"><button type="button" class="field-eye" data-toggle-password aria-label="Show the password"><x-icon name="eye" set="regular" /></button></div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="form-label" for="password_confirmation">Repeat the new password</label>
            <div class="auth-field"><x-icon name="lock" /><input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password"></div>
        </div>
        <button type="submit" class="btn btn-gradient btn-lg w-100">Save the new password</button>
    </form>
</div>
@endsection
