@extends('layouts.heritage')
@section('title', __('Homes and places with purpose'))
@section('description', __('Explore Rest Developments, our residential and mixed-use projects, apartments and company story in Afghanistan and Uzbekistan.'))
@section('content')
@php
    $locale = app()->getLocale();
    $cache = \Illuminate\Support\Facades\Cache::class;
    $heroSlides = $sliders->isNotEmpty() ? $sliders : collect([null]);
    $allProjects = $cache::remember("site.home.projects.$locale", 300, fn () => \App\Models\Project::active()->with(['translations', 'units', 'galleries'])->orderBy('order')->orderByDesc('created_at')->get());
    $titleOf = fn ($p) => $p->t()?->title ?? '';
    // Developments are the projects with a name of their own; "Gym & Fitness at Rest Complex" is a place inside one
    $communities = $allProjects->reject(fn ($p) => preg_match('/\s(at|in)\s+rest\b/i', $titleOf($p)))->values();
    $lifestyle = $allProjects->filter(fn ($p) => preg_match('/\s(at|in)\s+rest\b/i', $titleOf($p)))->values();
    $launches = $allProjects->where('featured', true)->values();
    if ($launches->count() < 3) $launches = $communities->merge($launches)->unique('id')->values();
    $launches = $launches->take(8);
    $projectCount = $allProjects->count();
    $aboutTr = $about?->t();
    $meta = function ($p) {
        $beds = $p->units?->pluck('bedrooms')->filter()->unique()->sort()->values();
        $types = $beds && $beds->count() ? $beds->implode(', ').' '.($beds->count() === 1 && (int) $beds->first() === 1 ? __('bedroom') : __('bedrooms')) : ($p->beds ? $p->beds.' '.__('bedrooms') : __('On request'));
        $price = $p->price_starts_from ? __('From').' '.$p->price_starts_from : __('Price on request');
        $status = $p->state === 'ongoing' ? __('Under construction') : ($p->state ? __(ucfirst($p->state)) : null);
        return compact('types', 'price', 'status');
    };
    $isVideo = fn ($url) => $url && preg_match('/\.(mp4|webm)(\?.*)?$/i', $url);
    $youtubeId = function ($url) {
        if (! $url) return null;
        return preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $url, $m) ? $m[1] : null;
    };
    $film = $heroSlides->map(fn ($s) => $s?->video_url)->filter()->first();
    $filmId = $youtubeId($film) ?: 'x4Fibo6XObs';
@endphp

{{-- Hero: film or slides, the brand, one line of proof, and the search --}}
<section class="hero hero--emaar" aria-label="{{ __('Featured developments') }}" data-hero>
    @foreach($heroSlides as $index => $slide)
        @php
            $desktop = $slide?->image ? media_url($slide->image) : asset('assets/img/brand/towers.webp');
            $mobile = $slide?->image_mobile ? media_url($slide->image_mobile) : $desktop;
            if ($slide?->image) {
                $desktopFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image), 0, 10).'-desktop.webp';
                $mobileFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image_mobile ?: $slide->image), 0, 10).'-mobile.webp';
                if (is_file(public_path($desktopFile))) $desktop = asset($desktopFile);
                if (is_file(public_path($mobileFile))) $mobile = asset($mobileFile);
            }
        @endphp
        <div class="hero-slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide aria-hidden="true">
            @if($isVideo($slide?->video_url))
                <video class="hero-video" @if($index === 0) autoplay @endif muted loop playsinline preload="{{ $index === 0 ? 'auto' : 'none' }}" poster="{{ $desktop }}" data-hero-video>
                    <source src="{{ $slide->video_url }}" type="video/{{ str_contains(strtolower($slide->video_url), '.webm') ? 'webm' : 'mp4' }}">
                </video>
            @else
                <picture>
                    <source media="(max-width: 720px)" @if($index === 0) srcset="{{ $mobile }}" @else data-srcset="{{ $mobile }}" @endif>
                    <img @if($index === 0) src="{{ $desktop }}" fetchpriority="high" @else data-src="{{ $desktop }}" @endif width="1920" height="1080" alt="" decoding="async">
                </picture>
            @endif
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
                        @if($index === 0)
                            <h1 class="hero-title{{ mb_strlen($headline) > 34 ? ' is-long' : '' }}">{{ $headline }}</h1>
                        @else
                            <p class="hero-title{{ mb_strlen($headline) > 34 ? ' is-long' : '' }}">{{ $headline }}</p>
                        @endif
                        @if($tr?->button_text)<p class="hero-link"><a class="text-link" href="{{ $buttonUrl }}">{{ $tr->button_text }} @include('partials.arrow')</a></p>@endif
                    </div>
                @endforeach
            </div>
            <div class="hero-proof">
                <p class="hero-figure"><b data-count="10" data-suffix="+">10+</b> <span>{{ __('years of building.') }}<br>{{ __('And counting.') }}</span></p>
                <div class="hero-controls">
                    <button class="film-btn" type="button" data-film-open data-film="{{ $filmId }}">
                        <span class="film-icon"><svg viewBox="0 0 20 20" aria-hidden="true"><path d="M7 5l9 5-9 5z" fill="currentColor"/></svg></span>{{ __('Watch the film') }}
                    </button>
                    @if($heroSlides->count() > 1)
                        <div class="hero-dots" role="group" aria-label="{{ __('Choose a slide') }}">
                            @foreach($heroSlides as $index => $slide)
                                <button class="hero-dot" type="button" data-hero-to="{{ $index }}" aria-label="{{ __('Show slide') }} {{ $index + 1 }}" @if($index === 0) aria-current="true" @endif></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="wrap">
            <form class="finder" method="get" action="{{ route('projects.index', ['locale' => $locale]) }}" role="search" aria-label="{{ __('Find a property') }}" data-finder>
                <label>{{ __('City') }}
                    <select name="location"><option value="">{{ __('All cities') }}</option><option value="Kabul">{{ __('Kabul') }}</option><option value="Termiz">{{ __('Termiz') }}</option></select>
                </label>
                <label>{{ __('Status') }}
                    <select name="state"><option value="">{{ __('Any status') }}</option><option value="ongoing">{{ __('Under construction') }}</option><option value="completed">{{ __('Completed') }}</option></select>
                </label>
                <label>{{ __('Bedrooms') }}
                    <select name="beds"><option value="">{{ __('Any') }}</option>@foreach(range(1, 5) as $b)<option value="{{ $b }}">{{ $b }}{{ $b === 5 ? '+' : '' }}</option>@endforeach</select>
                </label>
                <label>{{ __('Keyword') }}
                    <input type="search" name="q" placeholder="{{ __('Project, apartment, office…') }}">
                </label>
                <div class="finder-actions">
                    <button class="finder-reset" type="reset">{{ __('Reset all') }}</button>
                    <button class="finder-go" type="submit">{{ __('Show results') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- New launches: a numbered carousel --}}
@if($launches->isNotEmpty())
<section class="section launches" data-rail>
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('New launches') }}</span>
                <h2>{{ __('Discover the latest at Rest') }}</h2>
            </div>
            <div class="rail-controls">
                <span class="rail-count" data-rail-count>01 / {{ sprintf('%02d', $launches->count()) }}</span>
                <button class="round-btn" type="button" data-rail-prev aria-label="{{ __('Previous') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M11 3L5 9l6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
                <button class="round-btn round-btn--dark" type="button" data-rail-next aria-label="{{ __('Next') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M7 3l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
            </div>
        </div>
    </div>
    <div class="rail" data-rail-track tabindex="0" aria-label="{{ __('New launches') }}">
        @foreach($launches as $project)
            @php
                $m = $meta($project); $url = route('projects.show', ['locale' => $locale, 'slug' => $project->slug]);
            @endphp
            <article class="launch-card" data-rail-item>
                <a class="launch-card-img" href="{{ $url }}" tabindex="-1" aria-hidden="true">@if($project->image)<img src="{{ media_url($project->image) }}" alt="" loading="lazy" decoding="async" width="800" height="1000">@endif
                    @if($m['status'])<span class="badge {{ $project->state === 'ongoing' ? 'badge--gold' : '' }}">{{ $m['status'] }}</span>@endif
                </a>
                <div class="launch-card-body">
                    @if($project->location)<p class="card-place">{{ $project->location }}</p>@endif
                    <h3><a href="{{ $url }}">{{ $project->t()?->title }}</a></h3>
                    <dl class="launch-card-meta">
                        @if($m['types'])<div><dt>{{ __('Unit types') }}</dt><dd>{{ $m['types'] }}</dd></div>@endif
                        <div><dt>{{ __('Price') }}</dt><dd>{{ $m['price'] }}</dd></div>
                    </dl>
                    <div class="launch-card-links">
                        <a class="text-link" href="{{ $url }}">{{ __('Explore') }} @include('partials.arrow')</a>
                        <a class="text-link text-link--muted" href="{{ $url }}#register">{{ __('Register interest') }}</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
    <div class="wrap rail-foot">
        <span class="scroll-hint"><svg viewBox="0 0 28 12" aria-hidden="true"><path d="M0 6h26M21 1l5 5-5 5" fill="none" stroke="currentColor" stroke-width="1.2"/></svg> {{ __('Scroll to explore') }}</span>
        <a class="btn btn--line" href="{{ route('latest-launches', ['locale' => $locale]) }}">{{ __('View all launches') }}</a>
    </div>
</section>
@endif

{{-- Communities: large alternating sets --}}
@if($communities->isNotEmpty())
<section class="section section--mist communities">
    <div class="wrap">
        <div class="section-head section-head--center">
            <span class="eyebrow">{{ __('Rest communities') }}</span>
            <h2>{{ __('Places built around everyday life') }}</h2>
            <p>{{ __('Homes, work, shopping and wellness, planned together in the heart of the city.') }}</p>
        </div>
        @foreach($communities->take(4) as $i => $project)
            @php
                $tr = $project->t();
                $extra = $project->galleries?->take(2) ?? collect();
                $place = \Illuminate\Support\Str::contains(strtolower($project->location ?? ''), 'termiz') ? __('Mixed-use community') : __('City-centre living');
            @endphp
            <article class="community {{ $i % 2 ? 'community--flip' : '' }}">
                <div class="community-media">
                    <div class="community-main">@if($project->image)<img src="{{ media_url($project->image) }}" alt="{{ $tr?->title }}" loading="lazy" decoding="async" width="900" height="600">@endif</div>
                    @if($extra->count() === 2)
                        <div class="community-side">
                            @foreach($extra as $g)<div><img src="{{ media_url($g->image) }}" alt="" loading="lazy" decoding="async" width="400" height="300"></div>@endforeach
                        </div>
                    @endif
                </div>
                <div class="community-text">
                    <span class="community-label">{{ $place }}</span>
                    <h3>{{ $tr?->title }}</h3>
                    @if($project->location)<p class="card-place">{{ $project->location }}</p>@endif
                    <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($tr?->description ?? '')), 240) }}</p>
                    <a class="btn btn--line" href="{{ route('projects.show', ['locale' => $locale, 'slug' => $project->slug]) }}">{{ __('Discover more') }}</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

{{-- Lifestyle inside the communities --}}
@if($lifestyle->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('Lifestyle') }}</span>
                <h2>{{ __('More than a place to live') }}</h2>
            </div>
            <a class="text-link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('View all') }} @include('partials.arrow')</a>
        </div>
        <div class="life-grid">
            @foreach($lifestyle->take(4) as $project)
                @php
                $name = preg_replace('/\s+(at|in)\s+rest\b.*$/i', '', $titleOf($project));
            @endphp
                <a class="life-tile" href="{{ route('projects.show', ['locale' => $locale, 'slug' => $project->slug]) }}">
                    @if($project->image)<img src="{{ media_url($project->image) }}" alt="" loading="lazy" decoding="async" width="600" height="760">@endif
                    <span class="life-label"><strong>{{ $name }}</strong><small>{{ \Illuminate\Support\Str::limit(trim(strip_tags($project->t()?->description ?? '')), 80) }}</small></span>
                    <span class="life-go">@include('partials.arrow')</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- A legacy in figures --}}
<section class="section section--ink on-dark figures">
    <div class="wrap">
        <div class="figures-head">
            <span class="eyebrow">{{ __('About Rest') }}</span>
            <h2>{{ $aboutTr?->title ?: __('Built to last') }}</h2>
            <p>{{ $aboutTr?->subtitle ?: __('Rest Developments delivers modern residential and mixed-use projects across Afghanistan and Uzbekistan, with a focus on quality construction, thoughtful design and long-term value.') }}</p>
            <a class="btn btn--line" href="{{ route('about', ['locale' => $locale]) }}">{{ __('Our story') }}</a>
        </div>
        <div class="stats">
            <div class="stat"><b data-count="10" data-suffix="+">10+</b><p>{{ __('Years of real estate development') }}</p></div>
            <div class="stat"><b data-count="3">3</b><p>{{ __('Countries: Afghanistan, Uzbekistan and the UAE') }}</p></div>
            <div class="stat"><b data-count="{{ $projectCount }}">{{ $projectCount }}</b><p>{{ __('Projects and destinations') }}</p></div>
            <div class="stat stat--gold"><b data-count="4">4</b><p>{{ __('Languages we serve you in') }}</p></div>
        </div>
    </div>
</section>

{{-- Press releases --}}
@if($latestBlogs->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head">
            <div>
                <span class="eyebrow">{{ __('Press releases') }}</span>
                <h2>{{ __('The latest from Rest') }}</h2>
            </div>
            <a class="text-link" href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('All news') }} @include('partials.arrow')</a>
        </div>
        <div class="press-list">
            @foreach($latestBlogs->take(4) as $post)
                <article class="press-item">
                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->translatedFormat('d M Y') }}</time>
                    <span class="press-tag">{{ $post->category?->t()?->name ?? $post->category?->t()?->title ?? __('News') }}</span>
                    <h3><a href="{{ route('blog.show', ['locale' => $locale, 'slug' => $post->slug]) }}">{{ $post->t()?->title }}</a></h3>
                    <span class="press-go">@include('partials.arrow')</span>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Conversation --}}
<section class="talk">
    <div class="wrap talk-inner">
        <div>
            <span class="eyebrow">{{ __('Speak with us') }}</span>
            <h2>{{ __('Your next home starts with a conversation') }}</h2>
        </div>
        <div class="talk-actions">
            <a class="talk-link" href="mailto:info@restdevelopments.com"><small>{{ __('Email') }}</small><span>info@restdevelopments.com</span></a>
            <a class="talk-link" href="tel:+93777578657"><small>{{ __('Instant call') }}</small><span dir="ltr">+93 777 578 657</span></a>
            <a class="btn btn--whatsapp" href="https://api.whatsapp.com/send?phone=+93777578657&amp;text=Hello%20Rest%20Developments" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) {{ __('Chat on WhatsApp') }}</a>
        </div>
    </div>
</section>

{{-- The film --}}
<div class="film-modal" id="filmModal" hidden>
    <div class="film-frame" role="dialog" aria-modal="true" aria-label="{{ __('Rest Developments film') }}">
        <button class="film-close" type="button" data-film-close aria-label="{{ __('Close') }}"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
        <div class="film-player" data-film-player></div>
    </div>
</div>
@endsection
