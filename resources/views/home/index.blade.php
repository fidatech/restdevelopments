@extends('layouts.heritage')
@section('title', __('Homes and places with purpose'))
@section('description', __('Explore Rest Developments, our residential and mixed-use projects, apartments and company story in Afghanistan and Uzbekistan.'))
@section('content')
@php
    $locale = app()->getLocale();
    $heroSlides = $sliders->isNotEmpty() ? $sliders : collect([null]);
    $launches = ($featuredProjects->isNotEmpty() ? $featuredProjects : $ongoingProjects)->take(3)->values();
    $exploreProjects = \Illuminate\Support\Facades\Cache::remember("site.projects.explore.$locale", 300, fn () => \App\Models\Project::active()->with('translations')->orderBy('order')->orderByDesc('created_at')->take(6)->get());
    $projectCount = \Illuminate\Support\Facades\Cache::remember('site.projects.count', 300, fn () => \App\Models\Project::active()->count());
    $aboutTr = $about?->t();
    $stateLabel = fn ($state) => $state === 'ongoing' ? __('Under construction') : ($state ? __(ucfirst($state)) : null);
@endphp

{{-- Hero --}}
<section class="hero" aria-label="{{ __('Featured developments') }}" data-hero>
    @foreach($heroSlides as $index => $slide)
        @php
            $desktop = $slide?->image ? media_url($slide->image) : asset('assets/img/brand/towers.webp');
            $mobile = $slide?->image_mobile ? media_url($slide->image_mobile) : $desktop;
            if ($slide?->image) {
                $desktopFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image), 0, 10).'-desktop.webp';
                $mobileFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image_mobile ?: $slide->image), 0, 10).'-mobile.webp';
                if (is_file(public_path($desktopFile))) $desktop = media_url($desktopFile);
                if (is_file(public_path($mobileFile))) $mobile = media_url($mobileFile);
            }
        @endphp
        <div class="hero-slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide aria-hidden="true">
            <picture>
                <source media="(max-width: 720px)" @if($index === 0) srcset="{{ $mobile }}" @else data-srcset="{{ $mobile }}" @endif>
                <img @if($index === 0) src="{{ $desktop }}" fetchpriority="high" @else data-src="{{ $desktop }}" @endif width="1920" height="1080" alt="" decoding="async">
            </picture>
        </div>
    @endforeach

    <div class="hero-content">
        <div class="wrap hero-row">
            <div class="hero-text">
                @foreach($heroSlides as $index => $slide)
                    @php
                        $tr = $slide?->translation();
                        $headline = $tr?->headline ?: __('Live above the ordinary.');
                        $buttonUrl = $tr?->button_url ?: route('projects.index', ['locale' => $locale]);
                        $buttonPath = trim((string) parse_url($buttonUrl, PHP_URL_PATH), '/');
                        if (preg_match('#(^|/)contacts?$#', $buttonPath)) $buttonUrl = route('contact', ['locale' => $locale]);
                        elseif (preg_match('#(^|/)projects$#', $buttonPath)) $buttonUrl = route('projects.index', ['locale' => $locale]);
                    @endphp
                    <div class="hero-copy{{ $index === 0 ? ' is-active' : '' }}" data-hero-copy>
                        <span class="eyebrow">{{ __('Rest Developments') }}</span>
                        @if($index === 0)
                            <h1 class="hero-title{{ mb_strlen($headline) > 34 ? ' is-long' : '' }}">{{ $headline }}</h1>
                        @else
                            <p class="hero-title{{ mb_strlen($headline) > 34 ? ' is-long' : '' }}">{{ $headline }}</p>
                        @endif
                        @if($tr?->button_text)<p style="margin:22px 0 0"><a class="text-link" href="{{ $buttonUrl }}">{{ $tr->button_text }}</a></p>@endif
                    </div>
                @endforeach
            </div>
            <div class="hero-side">
                <p>{{ $heroSlides->first()?->translation()?->subheadline ?: __('10+ years of trusted real estate development. From residences to retail, we deliver quality, on time.') }}</p>
                @if($heroSlides->count() > 1)
                    <div class="hero-dots" role="group" aria-label="{{ __('Choose a slide') }}">
                        @foreach($heroSlides as $index => $slide)
                            <button class="hero-dot" type="button" data-hero-to="{{ $index }}" aria-label="{{ __('Show slide') }} {{ $index + 1 }}" @if($index === 0) aria-current="true" @endif></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="wrap">
            <form class="search-bar" method="get" action="{{ route('projects.index', ['locale' => $locale]) }}" role="search" aria-label="{{ __('Find a property') }}">
                <label>{{ __('City') }}
                    <select name="location">
                        <option value="">{{ __('All cities') }}</option>
                        <option value="Kabul">{{ __('Kabul') }}</option>
                        <option value="Termiz">{{ __('Termiz') }}</option>
                    </select>
                </label>
                <label>{{ __('Status') }}
                    <select name="state">
                        <option value="">{{ __('Any status') }}</option>
                        <option value="ongoing">{{ __('Under construction') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                    </select>
                </label>
                <label>{{ __('Keyword') }}
                    <input type="search" name="q" placeholder="{{ __('Apartment, office, retail…') }}">
                </label>
                <button type="submit">{{ __('Show results') }} @include('partials.arrow')</button>
            </form>
        </div>
    </div>
</section>

{{-- New launches --}}
@if($launches->isNotEmpty())
<section class="section" data-launches>
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('New launches') }}</span>
                <h2>{{ __('The latest from Rest') }}</h2>
            </div>
            @if($launches->count() > 1)
                <div class="launch-controls">
                    <span class="launch-count" data-launch-count aria-live="polite">01 / {{ sprintf('%02d', $launches->count()) }}</span>
                    <button class="round-btn" type="button" data-launch-prev aria-label="{{ __('Previous project') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M11 3L5 9l6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
                    <button class="round-btn round-btn--dark" type="button" data-launch-next aria-label="{{ __('Next project') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M7 3l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
                </div>
            @endif
        </div>
        @foreach($launches as $i => $project)
            @php
                $tr = $project->t();
                $url = route('projects.show', ['locale' => $locale, 'slug' => $project->slug]);
            @endphp
            <article class="launch{{ $i === 0 ? ' is-active' : '' }}" data-launch>
                <a class="launch-media" href="{{ $url }}" tabindex="-1" aria-hidden="true">@if($project->image)<img src="{{ media_url($project->image) }}" alt="" loading="lazy" decoding="async" width="1100" height="600">@endif</a>
                <div>
                    @if($project->location)<p class="launch-place">{{ $project->location }}</p>@endif
                    <h3>{{ $tr?->title }}</h3>
                    <p class="lead">{{ \Illuminate\Support\Str::limit(trim(strip_tags($tr?->description ?? '')), 220) }}</p>
                    <dl class="launch-facts">
                        @if($stateLabel($project->state))<div><dt>{{ __('Status') }}</dt><dd>{{ $stateLabel($project->state) }}</dd></div>@endif
                        @if($project->area)<div><dt>{{ __('Area') }}</dt><dd>{{ number_format((float) $project->area) }} m²</dd></div>@endif
                        @if($project->price_starts_from)<div><dt>{{ __('Prices from') }}</dt><dd>{{ $project->price_starts_from }}</dd></div>@endif
                        @if($project->beds)<div><dt>{{ __('Bedrooms') }}</dt><dd>{{ $project->beds }}</dd></div>@endif
                    </dl>
                    <div class="actions">
                        <a class="btn btn--green" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Register interest') }}</a>
                        <a class="btn btn--line" href="{{ $url }}">{{ __('Explore project') }}</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

{{-- Projects and destinations --}}
@if($exploreProjects->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('Our projects') }}</span>
                <h2>{{ __('Places to live, work and gather') }}</h2>
            </div>
            <a class="text-link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('View all projects') }} @include('partials.arrow')</a>
        </div>
        <div class="tiles">
            @foreach($exploreProjects as $project)
                <a class="tile" href="{{ route('projects.show', ['locale' => $locale, 'slug' => $project->slug]) }}">
                    @if($project->image)<img src="{{ media_url($project->image) }}" alt="" loading="lazy" decoding="async" width="800" height="440">@endif
                    <span class="tile-label"><span>@if($project->location)<small>{{ $project->location }}</small>@endif<strong>{{ $project->t()?->title }}</strong></span>@include('partials.arrow')</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- About and leadership --}}
<section class="section">
    <div class="wrap">
        <article class="feature">
            <div class="feature-media"><img src="{{ $about?->image_left ? media_url($about->image_left) : asset('assets/img/brand/towers-night.webp') }}" alt="{{ __('Rest Developments project') }}" loading="lazy" decoding="async" width="1000" height="620"></div>
            <div>
                <span class="eyebrow">{{ __('About Rest') }}</span>
                <h2>{{ $aboutTr?->title ?: __('Building places for people') }}</h2>
                <p class="lead">{{ $aboutTr?->subtitle ?: __('Rest Developments delivers modern residential and mixed-use projects across Afghanistan and Uzbekistan, with a focus on quality construction, thoughtful design and long-term value.') }}</p>
                <div class="actions" style="margin-top:32px"><a class="btn btn--line" href="{{ route('about', ['locale' => $locale]) }}">{{ __('Our story') }}</a></div>
            </div>
        </article>
        @if($aboutTr?->about_text)
        <article class="feature feature--flip">
            <div>
                <span class="eyebrow">{{ __('A message from our CEO') }}</span>
                <blockquote>“{{ $aboutTr->about_text }}”</blockquote>
                <cite>{{ $aboutTr->circle_text ?: 'Rest Developments' }}</cite>
                <a class="btn btn--line" href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Meet our people') }}</a>
            </div>
            <div class="feature-media"><img src="{{ asset('assets/img/brand/courtyard.webp') }}" alt="{{ __('Courtyard at Rest Complex') }}" loading="lazy" decoding="async" width="920" height="496"></div>
        </article>
        @endif
    </div>
</section>

{{-- Figures --}}
<section class="section section--green on-dark" style="padding-block: clamp(72px, 8vw, 110px)">
    <div class="wrap stats">
        <div class="stat"><b>10+</b><p>{{ __('Years of real estate development') }}</p></div>
        <div class="stat"><b>3</b><p>{{ __('Countries: Afghanistan, Uzbekistan and the UAE') }}</p></div>
        <div class="stat"><b>{{ $projectCount }}</b><p>{{ __('Projects and destinations') }}</p></div>
        <div class="stat stat--gold"><b>4</b><p>{{ __('Languages we serve you in') }}</p></div>
    </div>
</section>

{{-- News --}}
@if($latestBlogs->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <h2>{{ __('News') }}</h2>
            <a class="text-link" href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('All news') }} @include('partials.arrow')</a>
        </div>
        <div class="news-list">
            @foreach($latestBlogs->take(3) as $post)
                <article class="news-item">
                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->translatedFormat('d M Y') }}</time>
                    <div>
                        <h3><a href="{{ route('blog.show', ['locale' => $locale, 'slug' => $post->slug]) }}">{{ $post->t()?->title }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($post->t()?->content ?? '')), 140) }}</p>
                    </div>
                    <div class="news-thumb">@if($post->thumb_large)<img src="{{ media_url($post->thumb_large) }}" alt="" loading="lazy" decoding="async" width="640" height="400">@endif</div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.contact-band')
@endsection
