{{-- Sign-in pages of the admin panel: the auth layout of the fida-sarafi template. --}}
@php
    $ui = fn (string $path) => asset('admin-ui/'.$path).'?v='.(@filemtime(public_path('admin-ui/'.$path)) ?: 1);
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-primary="rest" data-icons="{{ $ui('icons.svg') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#035203">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Sign in') | Rest Developments</title>
    <link rel="icon" href="{{ $ui('img/rest-mark.png') }}" type="image/png">
    <script>(function(){var s={};try{s=JSON.parse(localStorage.getItem('erp.ui')||'{}')}catch(e){}document.documentElement.setAttribute('data-bs-theme',s.theme==='dark'?'dark':'light')})();</script>
    <link rel="stylesheet" href="{{ $ui('css/app.css') }}">
</head>
<body class="auth-body">
<div class="auth-shell">
    <aside class="auth-hero">
        <a href="{{ url('/') }}" class="brand-link"><span class="brand-mark"><img src="{{ $ui('img/rest-mark.png') }}" alt="" width="36" height="36"></span><span class="brand-text">Rest Developments</span></a>
        <div>
            <span class="ah-kicker"><x-icon name="building" /> Admin panel</span>
            <h1>Manage the Rest Developments website</h1>
            <p>Projects, sliders, news, careers and contact details, in one place.</p>
        </div>
        <div></div>
    </aside>
    <main class="auth-main">
        <div class="auth-tools">
            <button class="topbar-icon" type="button" data-theme-toggle title="Light or dark"><x-icon name="moon" /></button>
        </div>
        <div class="auth-mobile-hero"><span class="amh-logo"><img src="{{ $ui('img/rest-mark.png') }}" alt="" width="44" height="44"></span><strong>Rest Developments</strong><small>Admin panel</small></div>
        @yield('content')
        <footer class="mt-4 small text-muted">© {{ date('Y') }} Rest Developments · <a href="https://fida.af" target="_blank" rel="noopener">Fida Technologies</a></footer>
    </main>
</div>
@foreach (['success', 'warning', 'danger', 'info', 'status'] as $type)
    @if (session($type))<div data-toast="{{ $type === 'status' ? 'success' : $type }}" hidden>{{ session($type) }}</div>@endif
@endforeach
<script>window.ERP_TEXT = {"cancel":"Cancel","confirmOk":"Yes","confirmTitle":"Are you sure?","noResults":"No results","failed":"Something went wrong. Please try again."};</script>
<script src="{{ $ui('js/app.js') }}" defer></script>
</body>
</html>
