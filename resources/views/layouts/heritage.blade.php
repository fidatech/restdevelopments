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
            ['projects.index', __('Projects')],
            ['latest-launches', __('New launches')],
            ['about', __('About')],
            ['blog.index', __('News')],
            ['careers.index', __('Careers')],
            ['contact', __('Contact')],
        ];
        $menuLaunches = \Illuminate\Support\Facades\Cache::remember('site.menu.launches', 300, fn () => \App\Models\Project::active()->where('featured', true)->with('translations')->latest()->take(3)->get());
        $languageNames = ['en' => 'English', 'fa' => 'دری', 'ps' => 'پښتو', 'uz' => 'Oʻzbekcha'];
        $whatsapp = 'https://api.whatsapp.com/send?phone=+93777578657&text=Hello%20Rest%20Developments';
        $socialLinks = [
            ['facebook', 'Facebook', 'https://www.facebook.com/share/1Bc9FitgQD/?mibextid=wwXIfr'],
            ['instagram', 'Instagram', 'https://www.instagram.com/restdevelopments?igsh=MTBibzh5NHVnbTZnaw%3D%3D&utm_source=qr'],
            ['youtube', 'YouTube', 'https://youtu.be/x4Fibo6XObs?si=PJWWInMGtG8Z6IPc'],
            ['telegram', 'Telegram', 'https://t.me/rest_developments'],
            ['whatsapp', 'WhatsApp', $whatsapp],
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600{{ $isRtl ? '&family=Vazirmatn:wght@300;400;500;600' : '' }}&display=swap">
    <link rel="stylesheet" href="{{ $asset('assets/css/heritage.css') }}">
    @stack('head')
    <script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'Organization','@id'=>url('/').'#organization','name'=>'Rest Developments','url'=>url('/'),'logo'=>asset('assets/img/rest_logo.png'),'description'=>'Residential and mixed-use real estate development in Afghanistan and Uzbekistan.','areaServed'=>[['@type'=>'Country','name'=>'Afghanistan'],['@type'=>'Country','name'=>'Uzbekistan']],'sameAs'=>array_map(fn ($s) => $s[2], array_slice($socialLinks, 0, 4))], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
    @stack('schema')
</head>
<body>
<a class="skip" href="#main">{{ __('Skip to content') }}</a>

<header class="site-header" data-header>
    <div class="wrap header-inner">
        <a class="brand" href="{{ route('home', ['locale' => $locale]) }}" aria-label="Rest Developments, {{ __('Home') }}">@include('partials.brand-logo')</a>
        <nav class="main-nav" id="main-nav" aria-label="{{ __('Main navigation') }}">
            <a href="{{ route('latest-launches', ['locale' => $locale]) }}" @if(request()->routeIs('latest-launches')) aria-current="page" @endif>{{ __('New launches') }}</a>
            <div class="nav-item has-mega">
                <button class="nav-trigger" type="button" aria-expanded="false" aria-controls="mega-projects" @if(request()->routeIs('projects.*')) aria-current="page" @endif>{{ __('Projects') }} <svg viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.4"/></svg></button>
                <div class="mega" id="mega-projects">
                    <div class="wrap mega-inner">
                        <div class="mega-col">
                            <h2>{{ __('By status') }}</h2>
                            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'ongoing']) }}">{{ __('Under construction') }}</a>
                            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'completed']) }}">{{ __('Completed') }}</a>
                            <a href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('All projects') }}</a>
                        </div>
                        <div class="mega-col">
                            <h2>{{ __('By city') }}</h2>
                            <a href="{{ route('projects.index', ['locale' => $locale, 'location' => 'Kabul']) }}">{{ __('Kabul') }}</a>
                            <a href="{{ route('projects.index', ['locale' => $locale, 'location' => 'Termiz']) }}">{{ __('Termiz') }}</a>
                        </div>
                        <div class="mega-feature">
                            <h2>{{ __('Newly launched') }}</h2>
                            <div class="mega-cards">
                                @foreach($menuLaunches as $menuProject)
                                    <a class="mega-card" href="{{ route('projects.show', ['locale' => $locale, 'slug' => $menuProject->slug]) }}">
                                        <span class="mega-card-img">@if($menuProject->image)<img src="{{ media_url($menuProject->image) }}" alt="" loading="lazy" width="320" height="200">@endif</span>
                                        <strong>{{ \Illuminate\Support\Str::limit($menuProject->t()?->title, 46) }}</strong>
                                        @if($menuProject->location)<small>{{ $menuProject->location }}</small>@endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item has-mega">
                <button class="nav-trigger" type="button" aria-expanded="false" aria-controls="mega-company" @if(request()->routeIs('about', 'team.*', 'careers.*')) aria-current="page" @endif>{{ __('About Rest') }} <svg viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.4"/></svg></button>
                <div class="mega mega--small" id="mega-company">
                    <div class="wrap mega-inner">
                        <div class="mega-col">
                            <h2>{{ __('Company') }}</h2>
                            <a href="{{ route('about', ['locale' => $locale]) }}">{{ __('Our story') }}</a>
                            <a href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Our people') }}</a>
                            <a href="{{ route('careers.index', ['locale' => $locale]) }}">{{ __('Careers') }}</a>
                        </div>
                        <div class="mega-col">
                            <h2>{{ __('Media') }}</h2>
                            <a href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('News and updates') }}</a>
                            <a href="https://youtu.be/x4Fibo6XObs" target="_blank" rel="noopener">{{ __('Watch our film') }}</a>
                        </div>
                        <div class="mega-quote">
                            <p>{{ __('10+ years of trusted real estate development in Afghanistan and Uzbekistan.') }}</p>
                            <a class="text-link" href="{{ route('about', ['locale' => $locale]) }}">{{ __('Discover Rest') }} @include('partials.arrow')</a>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('blog.index', ['locale' => $locale]) }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>{{ __('News') }}</a>
            <a href="{{ route('contact', ['locale' => $locale]) }}" @if(request()->routeIs('contact')) aria-current="page" @endif>{{ __('Contact') }}</a>
        </nav>
        <div class="header-tools">
            <button class="icon-btn" type="button" data-search-open aria-label="{{ __('Search') }}"><svg viewBox="0 0 20 20" aria-hidden="true"><circle cx="9" cy="9" r="6" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M14 14l4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></button>
            <a class="icon-btn hide-sm" href="tel:+93777578657" aria-label="{{ __('Call us') }}"><svg viewBox="0 0 20 20" aria-hidden="true"><path d="M5.5 2.5h2.6l1.3 3.9-1.9 1.3a10 10 0 0 0 4.8 4.8l1.3-1.9 3.9 1.3v2.6a2 2 0 0 1-2 2A14.5 14.5 0 0 1 3.5 4.5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></a>
            <details class="lang">
                <summary aria-label="{{ __('Language') }}">{{ strtoupper($locale) }} <svg viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.4"/></svg></summary>
                <div class="lang-menu">
                    @foreach(config('app.available_locales', ['en','ps','fa','uz']) as $language)
                        <a href="{{ route('lang.switch', ['locale' => $language]) }}" lang="{{ $language }}" hreflang="{{ $language }}" @if($locale === $language) aria-current="true" @endif>{{ $languageNames[$language] ?? strtoupper($language) }}</a>
                    @endforeach
                </div>
            </details>
            <a class="btn header-cta" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Get in touch') }}</a>
            <button class="menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="{{ __('Open menu') }}" data-open-label="{{ __('Open menu') }}" data-close-label="{{ __('Close menu') }}"><span></span><span></span><span></span></button>
        </div>
    </div>
</header>

{{-- Search over the whole page --}}
<div class="search-overlay" id="siteSearch" hidden>
    <div class="wrap">
        <button class="search-close" type="button" data-search-close aria-label="{{ __('Close') }}"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
        <form method="get" action="{{ route('projects.index', ['locale' => $locale]) }}" role="search">
            <label for="siteSearchInput">{{ __('Search projects, cities and amenities') }}</label>
            <div class="search-overlay-field">
                <input id="siteSearchInput" type="search" name="q" placeholder="{{ __('Rest Complex, Kabul, office…') }}" autocomplete="off">
                <button class="btn btn--green" type="submit">{{ __('Search') }}</button>
            </div>
        </form>
        <div class="search-suggest">
            <span>{{ __('Popular') }}</span>
            <a href="{{ route('projects.index', ['locale' => $locale, 'location' => 'Termiz']) }}">{{ __('Termiz') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'location' => 'Kabul']) }}">{{ __('Kabul') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'ongoing']) }}">{{ __('Under construction') }}</a>
            <a href="{{ route('latest-launches', ['locale' => $locale]) }}">{{ __('New launches') }}</a>
        </div>
    </div>
</div>

<main id="main">@yield('content')</main>

<footer class="site-footer">
    <div class="wrap footer-lead">
        <div>
            @include('partials.brand-logo')
            <p>{{ __('Residential and mixed-use real estate in Afghanistan and Uzbekistan.') }}</p>
        </div>
        <div class="footer-updates">
            <h2>{{ __('Stay updated') }}</h2>
            <p>{{ __('New launches, construction progress and offers, first on our channels.') }}</p>
            <div class="actions">
                <a class="btn btn--line" href="https://t.me/rest_developments" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'telegram']) {{ __('Follow on Telegram') }}</a>
                <a class="btn btn--whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) WhatsApp</a>
            </div>
        </div>
    </div>
    <div class="wrap footer-top">
        <nav aria-label="{{ __('Explore') }}">
            <h2>{{ __('Explore') }}</h2>
            <a href="{{ route('latest-launches', ['locale' => $locale]) }}">{{ __('New launches') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('All projects') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'ongoing']) }}">{{ __('Under construction') }}</a>
            <a href="{{ route('projects.index', ['locale' => $locale, 'state' => 'completed']) }}">{{ __('Completed') }}</a>
        </nav>
        <nav aria-label="{{ __('About') }}">
            <h2>{{ __('About') }}</h2>
            <a href="{{ route('about', ['locale' => $locale]) }}">{{ __('Our story') }}</a>
            <a href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Our people') }}</a>
            <a href="{{ route('careers.index', ['locale' => $locale]) }}">{{ __('Careers') }}</a>
            <a href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('News') }}</a>
        </nav>
        <div class="footer-contact">
            <h2>{{ __('Connect') }}</h2>
            <a href="tel:+93777578657" dir="ltr">+93 777 578 657</a>
            <a href="mailto:info@restdevelopments.com">info@restdevelopments.com</a>
            <div class="socials">
                @foreach($socialLinks as [$key, $name, $url])
                    <a class="is-{{ $key }}" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $name }}">@include('partials.social-icon', ['name' => $key])</a>
                @endforeach
            </div>
        </div>
        <div class="footer-offices">
            <h2>{{ __('Offices') }}</h2>
            <address>Kabul, Afghanistan<br>Termiz, Uzbekistan<br>Dubai, United Arab Emirates</address>
        </div>
    </div>
    <div class="wrap footer-bottom">
        <span>© {{ date('Y') }} Rest Developments</span>
        <a href="#main">{{ __('Back to top') }}</a>
    </div>
</footer>

<div class="float-contact" aria-label="{{ __('Contact') }}">
    <a class="float-btn is-whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">@include('partials.social-icon', ['name' => 'whatsapp'])</a>
    <a class="float-btn is-call" href="tel:+93777578657" aria-label="{{ __('Call us') }}"><svg viewBox="0 0 20 20" aria-hidden="true"><path d="M5.5 2.5h2.6l1.3 3.9-1.9 1.3a10 10 0 0 0 4.8 4.8l1.3-1.9 3.9 1.3v2.6a2 2 0 0 1-2 2A14.5 14.5 0 0 1 3.5 4.5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg></a>
</div>

{{-- First-visit welcome: go to the website or open a social channel --}}
<div class="welcome" id="socialRedirectModal" hidden>
    <section class="welcome-panel" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="welcome-title" aria-describedby="welcome-text">
        <button class="welcome-close" type="button" data-welcome-dismiss aria-label="{{ __('Close') }}">
            <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        </button>
        <div class="welcome-visual">
            <img src="{{ asset('assets/img/brand/courtyard.webp') }}" alt="" width="920" height="496" loading="lazy" decoding="async">
            @include('partials.brand-logo')
            <p>{{ __('Live above the ordinary.') }}</p>
        </div>
        <div class="welcome-body">
            <h2 id="welcome-title">{{ __('Welcome to Rest Developments') }}</h2>
            <p id="welcome-text">{{ __('Explore our projects on the website, or follow our latest news on social media.') }}</p>
            <button class="btn btn--green welcome-enter" type="button" data-welcome-dismiss>
                {{ __('Continue to website') }}
                <svg viewBox="0 0 22 22" aria-hidden="true"><path d="M3 11h15M12 5l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="welcome-or">{{ __('or connect with us') }}</div>
            <div class="social-buttons">
                @foreach($socialLinks as [$key, $name, $url])
                    <a class="social-btn is-{{ $key }}" href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                        @include('partials.social-icon', ['name' => $key])
                        <span>{{ $name }}</span>
                        @if($key === 'whatsapp')<small>{{ __('Chat with our sales team') }}</small>@endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script src="{{ $asset('assets/js/heritage.js') }}" defer></script>
</body>
</html>
