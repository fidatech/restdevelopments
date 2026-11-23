@extends('admin.layouts.auth')
@section('title', 'Confirm password')

@section('content')
<div class="auth-card">
    <div class="ac-icon"><x-icon name="lock" /></div>
    <h2>Confirm your password</h2>
    <p class="ac-sub">This is a protected area. Please enter your password to continue.</p>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label" for="password">Password</label>
            <div class="auth-field"><x-icon name="lock" /><input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password"><button type="button" class="field-eye" data-toggle-password aria-label="Show the password"><x-icon name="eye" set="regular" /></button></div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-gradient btn-lg w-100">Confirm</button>
    </form>
</div>
@endsection
