<!doctype html>
@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, ['fa', 'ps'], true);
@endphp
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#035203">
    <meta name="google-site-verification" content="LENZQHS9BfAGydgrszKg9F4Qiu-D2MpgEYoGNJ6LRlQ">
    @php
        $pageTitle = trim($__env->yieldContent('title')) ?: 'Rest Developments';
        $pageDescription = trim($__env->yieldContent('description')) ?: 'Rest Developments creates residential and mixed-use places in Afghanistan and Uzbekistan. Explore our company and projects.';
        $pageRoute = request()->route()?->getName();
        $routeParameters = request()->route()?->parameters() ?? [];
        $publicRoute = in_array($pageRoute, ['home','about','projects.index','projects.show','latest-launches','blog.index','blog.show','team.index','careers.index','careers.show','contact'], true);
        $canonical = $publicRoute ? route($pageRoute, array_merge($routeParameters, ['locale' => $locale])) : url()->current();
        $socialImage = trim($__env->yieldContent('og_image')) ?: asset('assets/img/rest_logo.png');
        $asset = fn (string $path) => asset($path).'?v='.(@filemtime(public_path($path)) ?: '1');
        $navItems = [
            ['home', __('Home')],
            ['about', __('Company')],
            ['projects.index', __('Projects')],
            ['latest-launches', __('Latest launches')],
            ['blog.index', __('News')],
            ['careers.index', __('Careers')],
            ['contact', __('Contact')],
        ];
        $socialLinks = [
            ['facebook', 'Facebook', 'https://www.facebook.com/share/1Bc9FitgQD/?mibextid=wwXIfr', __('Follow')],
            ['instagram', 'Instagram', 'https://www.instagram.com/restdevelopments?igsh=MTBibzh5NHVnbTZnaw%3D%3D&utm_source=qr', __('Follow')],
            ['youtube', 'YouTube', 'https://youtu.be/x4Fibo6XObs?si=PJWWInMGtG8Z6IPc', __('Watch')],
            ['telegram', 'Telegram', 'https://t.me/rest_developments', __('Join')],
            ['whatsapp', 'WhatsApp', 'https://api.whatsapp.com/send?phone=+93777578657&text=Hello%20Rest%20Developments', __('Chat with our sales team')],
        ];
    @endphp
    <title>{{ $pageTitle }} | Rest Developments</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    @if($publicRoute)
        @foreach(config('app.available_locales', ['en']) as $alternateLocale)
            <link rel="alternate" hreflang="{{ $alternateLocale }}" href="{{ route($pageRoute, array_merge($routeParameters, ['locale' => $alternateLocale])) }}">
        @endforeach
    @endif
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Rest Developments">
    <meta property="og:title" content="{{ $pageTitle }} | Rest Developments">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $socialImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="preload" href="{{ asset('assets/fonts/cardo-regular.woff') }}" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="{{ asset('assets/fonts/dmsans-var.woff') }}" as="font" type="font/woff" crossorigin>
    @if($isRtl)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300..700&display=swap">
    @endif
    <link rel="stylesheet" href="{{ $asset('assets/css/heritage.css') }}">
    <script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Organization','@id'=>url('/').'#organization','name'=>'Rest Developments','url'=>url('/'),'logo'=>asset('assets/img/rest_logo.png'),'description'=>'Residential and mixed-use real estate development in Afghanistan and Uzbekistan.','areaServed'=>[['@type'=>'Country','name'=>'Afghanistan'],['@type'=>'Country','name'=>'Uzbekistan']],'sameAs'=>array_map(fn ($s) => $s[2], array_slice($socialLinks, 0, 4))], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('schema')
</head>
<body>
<a class="skip" href="#main">{{ __('Skip to content') }}</a>

<header class="site-header" data-header>
    <div class="wrap header-inner">
        <a class="brand" href="{{ route('home', ['locale' => $locale]) }}" aria-label="Rest Developments — {{ __('Home') }}">@include('partials.brand-logo', ['variant' => 'green'])</a>
        <button class="menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="{{ __('Open menu') }}" data-open-label="{{ __('Open menu') }}" data-close-label="{{ __('Close menu') }}"><span></span><span></span><span></span></button>
        <nav class="main-nav" id="main-nav" aria-label="{{ __('Main navigation') }}">
            @foreach($navItems as [$routeName, $label])
                @continue($routeName === 'contact')
                <a href="{{ route($routeName, ['locale' => $locale]) }}" @if(request()->routeIs($routeName) || ($routeName !== 'home' && request()->routeIs(str_replace('.index', '', $routeName).'.*'))) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <div class="header-tools">
                <div class="languages" role="group" aria-label="{{ __('Language') }}">
                    @foreach(config('app.available_locales', ['en','ps','fa','uz']) as $language)
                        <a href="{{ route('lang.switch', ['locale' => $language]) }}" lang="{{ $language }}" hreflang="{{ $language }}" @if($locale === $language) aria-current="true" @endif>{{ strtoupper($language) }}</a>
                    @endforeach
                </div>
                <a class="btn header-cta" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Contact us') }}</a>
            </div>
        </nav>
    </div>
</header>

<main id="main">@yield('content')</main>

<footer class="site-footer">
    <div class="wrap footer-top">
        <div class="footer-brand">
            @include('partials.brand-logo', ['variant' => 'light'])
            <p>{{ __('Residential and mixed-use developments in Afghanistan and Uzbekistan, planned around the way families live.') }}</p>
            <div class="socials">
                @foreach($socialLinks as [$key, $name, $url])
                    <a class="is-{{ $key }}" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $name }}">@include('partials.social-icon', ['name' => $key])</a>
                @endforeach
            </div>
        </div>
        <nav aria-label="{{ __('Company') }}">
            <h2>{{ __('Company') }}</h2>
            <a href="{{ route('about', ['locale' => $locale]) }}">{{ __('About us') }}</a>
            <a href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Our people') }}</a>
            <a href="{{ route('careers.index', ['locale' => $locale]) }}">{{ __('Careers') }}</a>
            <a href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('News') }}</a>
        </nav>
        <nav aria-label="{{ __('Projects') }}">
            <h2>{{ __('Projects') }}</h2>
            <a href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('All projects') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'ongoing']) }}">{{ __('Under construction') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'completed']) }}">{{ __('Completed') }}</a>
            <a href="{{ route('latest-launches', ['locale' => $locale]) }}">{{ __('Latest launches') }}</a>
        </nav>
        <div class="footer-contact">
            <h2>{{ __('Get in touch') }}</h2>
            <a href="tel:+93777578657" dir="ltr">+93 777 578 657</a>
            <a href="mailto:info@restdevelopments.com">info@restdevelopments.com</a>
            <address>Kabul, Afghanistan<br>Termiz, Uzbekistan<br>Dubai, United Arab Emirates</address>
        </div>
    </div>
    <div class="wrap footer-bottom">
        <span>© {{ date('Y') }} Rest Developments</span>
        <a href="#main">{{ __('Back to top') }}</a>
    </div>
</footer>

{{-- First-visit welcome: go to the website or open a social channel --}}
<div class="welcome" id="socialRedirectModal" hidden>
    <section class="welcome-panel" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="welcome-title" aria-describedby="welcome-text">
        <button class="welcome-close" type="button" data-welcome-dismiss aria-label="{{ __('Close') }}">
            <svg viewBox="0 0 18 18" aria-hidden="true"><path d="M2 2l14 14M16 2L2 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <div class="welcome-visual">
            <img src="{{ asset('assets/img/brand/welcome.webp') }}" alt="" width="760" height="1206" loading="lazy" decoding="async">
            @include('partials.brand-logo', ['variant' => 'light'])
            <p>{{ __('Places for life, built with purpose.') }}</p>
        </div>
        <div class="welcome-body">
            <h2 id="welcome-title">{{ __('Welcome to Rest Developments') }}</h2>
            <p id="welcome-text">{{ __('Browse our projects on the website, or follow our latest news on social media.') }}</p>
            <button class="btn welcome-enter" type="button" data-welcome-dismiss>
                {{ __('Continue to website') }}
                <svg viewBox="0 0 22 22" aria-hidden="true"><path d="M3 11h15M12 5l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="welcome-or">{{ __('or connect with us') }}</div>
            <div class="social-buttons">
                @foreach($socialLinks as [$key, $name, $url, $action])
                    <a class="social-btn is-{{ $key }}" href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                        @include('partials.social-icon', ['name' => $key])
                        <span>{{ $name }}</span>
                        @if($key === 'whatsapp')<small>{{ $action }}</small>@endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script src="{{ $asset('assets/js/heritage.js') }}" defer></script>
</body>
</html>
