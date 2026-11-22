<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['fa','ps']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#073d2a">
    <meta name="google-site-verification" content="LENZQHS9BfAGydgrszKg9F4Qiu-D2MpgEYoGNJ6LRlQ">
    @php
        $pageTitle = trim($__env->yieldContent('title')) ?: 'Rest Developments';
        $pageDescription = trim($__env->yieldContent('description')) ?: 'Rest Developments creates residential and mixed-use places in Afghanistan and Uzbekistan. Explore our company and projects.';
        $canonical = url()->current();
        $socialImage = trim($__env->yieldContent('og_image')) ?: asset('assets/img/rest_logo.png');
    @endphp
    <title>{{ $pageTitle }} | Rest Developments</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Rest Developments">
    <meta property="og:title" content="{{ $pageTitle }} | Rest Developments">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $socialImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="preload" href="{{ asset('assets/img/rest-logo-small.webp') }}" as="image" type="image/webp">
    <link rel="stylesheet" href="{{ asset('assets/css/heritage.css') }}">
    <script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Organization','@id'=>url('/').'#organization','name'=>'Rest Developments','url'=>url('/'),'logo'=>asset('assets/img/rest_logo.png'),'description'=>'Residential and mixed-use real estate development in Afghanistan and Uzbekistan.','areaServed'=>[['@type'=>'Country','name'=>'Afghanistan'],['@type'=>'Country','name'=>'Uzbekistan']]], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('schema')
</head>
<body>
<a class="skip" href="#main">{{ __('Skip to content') }}</a>
<header class="site-header">
    <div class="header-inner wrap">
        <a class="brand" href="{{ route('home', ['locale'=>app()->getLocale()]) }}" aria-label="Rest Developments — {{ __('Home') }}"><img src="{{ asset('assets/img/rest-logo-small.webp') }}" width="160" height="81" alt="Rest Developments"></a>
        <nav class="main-nav" aria-label="{{ __('Main navigation') }}" id="main-nav">
            <a href="{{ route('home', ['locale'=>app()->getLocale()]) }}">{{ __('Home') }}</a>
            <a href="{{ route('about', ['locale'=>app()->getLocale()]) }}">{{ __('Company') }}</a>
            <a href="{{ route('projects.index', ['locale'=>app()->getLocale()]) }}">{{ __('Projects') }}</a>
            <a href="{{ route('latest-launches', ['locale'=>app()->getLocale()]) }}">{{ __('Latest launches') }}</a>
            <a href="{{ route('blog.index', ['locale'=>app()->getLocale()]) }}">{{ __('Journal') }}</a>
            <a href="{{ route('careers.index', ['locale'=>app()->getLocale()]) }}">{{ __('Careers') }}</a>
            <a class="nav-contact" href="{{ route('contact', ['locale'=>app()->getLocale()]) }}">{{ __('Contact') }} <span aria-hidden="true">↗</span></a>
            <div class="languages" aria-label="{{ __('Language') }}">
                @foreach(config('app.available_locales', ['en','ps','fa','uz']) as $language)
                    <a href="{{ route('lang.switch', ['locale'=>$language]) }}" lang="{{ $language }}" @if(app()->getLocale()===$language) aria-current="true" @endif>{{ strtoupper($language) }}</a>
                @endforeach
            </div>
        </nav>
        <button class="menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="{{ __('Open menu') }}"><span></span><span></span><span></span></button>
    </div>
</header>
<main id="main">@yield('content')</main>
<footer class="site-footer">
    <div class="wrap footer-grid">
        <div><img src="{{ asset('assets/img/rest-logo-small.webp') }}" width="140" height="71" loading="lazy" alt="Rest Developments"><p>{{ __('Places for life, built with purpose.') }}</p></div>
        <div><h2>{{ __('Explore') }}</h2><a href="{{ route('about', ['locale'=>app()->getLocale()]) }}">{{ __('Our company') }}</a><a href="{{ route('projects.index', ['locale'=>app()->getLocale()]) }}">{{ __('Projects') }}</a><a href="{{ route('team.index', ['locale'=>app()->getLocale()]) }}">{{ __('Our people') }}</a></div>
        <div><h2>{{ __('Connect') }}</h2><a href="{{ route('contact', ['locale'=>app()->getLocale()]) }}">{{ __('Contact us') }}</a><a href="mailto:info@restdevelopments.com">info@restdevelopments.com</a><a href="tel:+93777578657">+93 777 578 657</a></div>
        <div><h2>{{ __('Locations') }}</h2><p>Kabul, Afghanistan<br>Termiz, Uzbekistan<br>Dubai, United Arab Emirates</p></div>
    </div>
    <div class="wrap footer-bottom"><span>© {{ date('Y') }} Rest Developments</span><span>{{ __('Built for the places we call home.') }}</span></div>
</footer>
<div class="welcome-backdrop" id="socialRedirectModal" hidden>
    <section class="welcome-panel" role="dialog" aria-modal="true" aria-labelledby="socialRedirectLabel">
        <button class="welcome-close" type="button" data-welcome-dismiss aria-label="{{ __('Close') }}">×</button>
        <div class="welcome-heading"><span class="eyebrow">REST DEVELOPMENTS</span><h2 id="socialRedirectLabel">{{ __('Where would you like to go?') }}</h2><p>{{ __('Explore the website or connect with us.') }}</p></div>
        <button class="button button-light welcome-continue" type="button" data-welcome-dismiss>{{ __('Continue to website') }} <span aria-hidden="true">↗</span></button>
        <div class="welcome-links"><a href="https://www.facebook.com/share/1Bc9FitgQD/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer">Facebook ↗</a><a href="https://www.instagram.com/restdevelopments?igsh=MTBibzh5NHVnbTZnaw%3D%3D&amp;utm_source=qr" target="_blank" rel="noopener noreferrer">Instagram ↗</a><a href="https://youtu.be/x4Fibo6XObs?si=PJWWInMGtG8Z6IPc" target="_blank" rel="noopener noreferrer">YouTube ↗</a><a href="https://t.me/rest_developments" target="_blank" rel="noopener noreferrer">Telegram ↗</a><a href="https://api.whatsapp.com/send?phone=+93777578657&amp;text=Hello%20Rest%20Developments" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a></div>
    </section>
</div>
<script src="{{ asset('assets/js/heritage.js') }}" defer></script>
</body>
</html>
