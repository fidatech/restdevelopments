@extends('layouts.heritage')
@section('title', __('Homes and places with purpose'))
@section('description', __('Explore Rest Developments, our residential and mixed-use projects, apartments and company story in Afghanistan and Uzbekistan.'))
@section('content')
@php
    $heroSlides = $sliders->isNotEmpty() ? $sliders : collect([null]);
@endphp
<section class="hero hero-carousel" aria-label="{{ __('Featured developments') }}" data-hero-carousel>
    <div class="hero-slides">
        @foreach($heroSlides as $index => $slide)
            @php
                $tr = $slide?->translation();
                $headline = $tr?->headline ?: __('Places for life. Built with purpose.');
                if ($index === 0 && mb_strlen($headline) > 70) $headline = __('Places for life. Built with purpose.');
                $summary = $tr?->subheadline ?: __('Thoughtful residential and mixed-use developments shaped around the way people live.');
                $buttonUrl = $tr?->button_url ?: route('projects.index', ['locale'=>app()->getLocale()]);
                $buttonPath = trim((string) parse_url($buttonUrl, PHP_URL_PATH), '/');
                if ($buttonPath === 'contacts' || str_ends_with($buttonPath, '/contacts') || $buttonPath === 'contact' || str_ends_with($buttonPath, '/contact')) $buttonUrl = route('contact', ['locale'=>app()->getLocale()]);
                elseif ($buttonPath === 'projects' || str_ends_with($buttonPath, '/projects')) $buttonUrl = route('projects.index', ['locale'=>app()->getLocale()]);
                $buttonText = $tr?->button_text ?: __('Explore our projects');
                $desktop = $slide?->image ? asset('storage/'.$slide->image) : null;
                $mobile = $slide?->image_mobile ? asset('storage/'.$slide->image_mobile) : $desktop;
                $desktopFile = $slide?->image ? 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image), 0, 10).'-desktop.webp' : null;
                $mobileFile = $slide && ($slide->image_mobile ?: $slide->image) ? 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image_mobile ?: $slide->image), 0, 10).'-mobile.webp' : null;
                $desktopOptimized = $desktopFile && is_file(public_path($desktopFile)) ? asset($desktopFile) : $desktop;
                $mobileOptimized = $mobileFile && is_file(public_path($mobileFile)) ? asset($mobileFile) : $mobile;
            @endphp
            <article class="hero-slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide @if($index > 0) hidden @endif>
                <div class="hero-copy">
                    <div class="hero-copy-inner">
                        <span class="eyebrow">{{ __('Afghanistan · Uzbekistan · UAE') }}</span>
                        <h1 @if($index === 0) id="home-heading" @endif>{{ $headline }}</h1>
                        <p class="lead">{{ $summary }}</p>
                        <div class="hero-actions">
                            <a class="button button-light" href="{{ $buttonUrl }}">{{ $buttonText }}</a>
                            <a class="hero-story" href="{{ route('about', ['locale'=>app()->getLocale()]) }}">{{ __('Our story') }}</a>
                        </div>
                    </div>
                    <span class="hero-vertical" aria-hidden="true">REST DEVELOPMENTS</span>
                </div>
                <div class="hero-visual">
                    @if($desktop)
                        <picture>
                            <source media="(max-width: 650px)" @if($index === 0) srcset="{{ $mobileOptimized }}" @else data-srcset="{{ $mobileOptimized }}" @endif>
                            <img @if($index === 0) src="{{ $desktopOptimized }}" fetchpriority="high" loading="eager" @else data-src="{{ $desktopOptimized }}" loading="lazy" @endif
                                width="1100" height="720" decoding="async" alt="{{ $headline }}">
                        </picture>
                    @endif
                    <div class="hero-photo-label"><span class="hero-photo-line"></span><span>{{ __('A sense of place') }}</span></div>
                </div>
            </article>
        @endforeach
    </div>
    @if($heroSlides->count() > 1)
        <div class="hero-controls">
            <div class="wrap hero-controls-inner">
                <div class="hero-pages" role="group" aria-label="{{ __('Choose a slide') }}">
                    @foreach($heroSlides as $index => $slide)
                        <button type="button" data-hero-to="{{ $index }}" aria-label="{{ __('Show slide') }} {{ $index + 1 }}" @if($index === 0) aria-current="true" @endif><span>{{ sprintf('%02d', $index + 1) }}</span></button>
                    @endforeach
                </div>
                <div class="hero-control-actions">
                    <button type="button" data-hero-prev aria-label="{{ __('Previous slide') }}">←</button>
                    <button type="button" data-hero-next aria-label="{{ __('Next slide') }}">→</button>
                    <button type="button" data-hero-play data-pause-label="{{ __('Pause slideshow') }}" data-play-label="{{ __('Play slideshow') }}" aria-label="{{ __('Pause slideshow') }}" aria-pressed="false"></button>
                </div>
            </div>
        </div>
    @endif
</section>
<section class="section" id="about-sec"><div class="wrap split">
    <div class="split-copy"><span class="eyebrow">01 / {{ __('Our approach') }}</span><h2>{{ $about?->t()?->title ?? __('Development with a sense of belonging') }}</h2><p class="lead">{{ $about?->t()?->subtitle ?? __('We create spaces where architecture, community and everyday life meet.') }}</p><a class="text-link" href="{{ route('about', ['locale'=>app()->getLocale()]) }}">{{ __('Meet Rest Developments') }} </a></div>
    <div class="image-frame">@if($about?->image_left)<img src="{{ asset('storage/'.$about->image_left) }}" alt="{{ __('Rest Developments architecture') }}" loading="lazy" decoding="async" width="760" height="570">@endif<span class="caption">Kabul / Termiz</span></div>
</div></section>
@if($about?->t()?->about_text)<section class="section section-dark"><div class="wrap"><span class="eyebrow">{{ __('A message from our CEO') }}</span><blockquote class="lead leadership-message">{{ $about->t()->about_text }}</blockquote><span class="eyebrow">{{ $about->t()->circle_text ?: 'Rest Developments' }}</span></div></section>@endif
<section class="section section-cream"><div class="wrap"><div class="section-head"><div><span class="eyebrow">02 / {{ __('Selected developments') }}</span><h2>{{ __('Places to discover') }}</h2></div><a class="text-link" href="{{ route('projects.index', ['locale'=>app()->getLocale()]) }}">{{ __('View all projects') }} </a></div><div class="cards">@forelse($featuredProjects->take(3) as $project)@include('partials.heritage-project-card')@empty @foreach($ongoingProjects->take(3) as $project)@include('partials.heritage-project-card')@endforeach @endforelse</div></div></section>
<section class="section"><div class="wrap split"><div class="image-frame">@if($about?->image_right)<img src="{{ asset('storage/'.$about->image_right) }}" loading="lazy" decoding="async" width="760" height="570" alt="{{ __('Rest Developments residences') }}">@endif<span class="caption">{{ __('A closer look') }}</span></div><div class="split-copy"><span class="eyebrow">03 / {{ __('Life at Rest') }}</span><h2>{{ __('A home is more than its walls.') }}</h2><p class="lead">{{ __('Explore the apartments, shared spaces and details that make our developments places to call home.') }}</p><a class="text-link" href="{{ route('projects.index', ['locale'=>app()->getLocale()]) }}">{{ __('Explore apartments') }} </a></div></div></section>
<section class="section pattern-band"><div class="wrap"><span class="eyebrow">{{ __('Built on place and people') }}</span><h2>{{ __('Rooted in our region. Designed for what comes next.') }}</h2><p>{{ __('Our visual language takes cues from the brick arches of ancient Termiz and the geometric tile traditions of Afghanistan. Our spaces are designed for life today.') }}</p><a class="button button-light" href="{{ route('contact', ['locale'=>app()->getLocale()]) }}">{{ __('Speak with our team') }} </a></div></section>
@if($latestBlogs->isNotEmpty())<section class="section section-cream"><div class="wrap"><div class="section-head"><div><span class="eyebrow">04 / {{ __('From our journal') }}</span><h2>{{ __('Stories and updates') }}</h2></div><a class="text-link" href="{{ route('blog.index', ['locale'=>app()->getLocale()]) }}">{{ __('All stories') }} </a></div><div class="cards">@foreach($latestBlogs->take(3) as $post)<article class="card"><a href="{{ route('blog.show', ['locale'=>app()->getLocale(), 'slug'=>$post->slug]) }}"><div class="card-media">@if($post->thumb_large)<img src="{{ asset('storage/'.$post->thumb_large) }}" loading="lazy" decoding="async" width="640" height="480" alt="{{ $post->t()?->title }}">@endif</div><div class="card-body"><span class="eyebrow">{{ $post->created_at?->format('d M Y') }}</span><h3>{{ $post->t()?->title }}</h3><span class="text-link">{{ __('Read story') }} </span></div></a></article>@endforeach</div></div></section>@endif
@endsection
