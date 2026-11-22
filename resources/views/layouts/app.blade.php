<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
  @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#0A1D37;">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Fida Technologies</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
      </ul>
      @auth
        <div class="d-flex align-items-center gap-2">
          <span class="text-white-50">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="btn btn-sm btn-light">Logout</button>
          </form>
        </div>
      @endauth
    </div>
  </div>
</nav>

<main class="py-4">
  <div class="container">
    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    {{ $slot ?? '' }}
    @yield('content')
  </div>
</main>

<footer class="py-4 mt-5" style="background:#0A1D37;">
  <div class="container text-center text-white-50">
    © {{ date('Y') }} Fida Technologies & Marketing Services
  </div>
</footer>
@stack('scripts')
</body>
</html>
