@extends('layouts.heritage')
@section('title', __('Homes and places with purpose'))
@section('description', __('Explore Rest Developments, our residential and mixed-use projects, apartments and company story in Afghanistan and Uzbekistan.'))
@section('content')
@php
    $locale = app()->getLocale();
    $heroSlides = $sliders->isNotEmpty() ? $sliders : collect([null]);
    $projects = $featuredProjects->isNotEmpty() ? $featuredProjects : $ongoingProjects;
    $lead = $projects->first();
    $others = $projects->slice(1, 2);
    $aboutTr = $about?->t();
@endphp

{{-- Hero --}}
<section class="hero" aria-roledescription="carousel" aria-label="{{ __('Featured developments') }}" data-hero>
    @foreach($heroSlides as $index => $slide)
        @php
            $tr = $slide?->translation();
            $headline = $tr?->headline ?: __('Places for life. Built with purpose.');
            $summary = $tr?->subheadline ?: __('Residential and mixed-use developments in Kabul and Termiz, planned around the way families live.');
            $buttonUrl = $tr?->button_url ?: route('projects.index', ['locale' => $locale]);
            $buttonPath = trim((string) parse_url($buttonUrl, PHP_URL_PATH), '/');
            if (preg_match('#(^|/)contacts?$#', $buttonPath)) $buttonUrl = route('contact', ['locale' => $locale]);
            elseif (preg_match('#(^|/)projects$#', $buttonPath)) $buttonUrl = route('projects.index', ['locale' => $locale]);
            $buttonText = $tr?->button_text ?: __('Explore our projects');
            $desktop = $slide?->image ? asset('storage/'.$slide->image) : asset('assets/img/brand/towers.webp');
            $mobile = $slide?->image_mobile ? asset('storage/'.$slide->image_mobile) : $desktop;
            if ($slide?->image) {
                $desktopFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image), 0, 10).'-desktop.webp';
                $mobileFile = 'assets/img/slider/slide-'.$slide->id.'-'.substr(sha1($slide->image_mobile ?: $slide->image), 0, 10).'-mobile.webp';
                if (is_file(public_path($desktopFile))) $desktop = asset($desktopFile);
                if (is_file(public_path($mobileFile))) $mobile = asset($mobileFile);
            }
        @endphp
        <article class="hero-slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide aria-roledescription="slide" aria-label="{{ $index + 1 }} / {{ $heroSlides->count() }}" @if($index > 0) aria-hidden="true" @endif>
            <picture class="hero-media">
                <source media="(max-width: 720px)" @if($index === 0) srcset="{{ $mobile }}" @else data-srcset="{{ $mobile }}" @endif>
                <img @if($index === 0) src="{{ $desktop }}" fetchpriority="high" @else data-src="{{ $desktop }}" @endif width="1920" height="1080" alt="" decoding="async">
            </picture>
            <div class="wrap hero-copy">
                <span class="kicker">{{ __('Kabul, Termiz and Dubai') }}</span>
                @if($index === 0)<h1>{{ $headline }}</h1>@else<h2 class="hero-title">{{ $headline }}</h2>@endif
                <p class="lead">{{ $summary }}</p>
                <div class="hero-actions">
                    <a class="btn btn--gold" href="{{ $buttonUrl }}">{{ $buttonText }}</a>
                    <a class="btn btn--ghost" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Book a visit') }}</a>
                </div>
            </div>
        </article>
    @endforeach


    @if($heroSlides->count() > 1)
        <div class="hero-index">
            <div class="wrap">
                <div class="hero-tabs" role="group" aria-label="{{ __('Choose a slide') }}">
                    @foreach($heroSlides as $index => $slide)
                        <button class="hero-tab" type="button" data-hero-to="{{ $index }}" @if($index === 0) aria-current="true" @endif>
                            <b>{{ sprintf('%02d', $index + 1) }}</b>
                            <span>{{ $slide?->translation()?->headline ?: __('Slide').' '.($index + 1) }}</span>
                        </button>
                    @endforeach
                </div>
                <button class="hero-pause" type="button" data-hero-pause data-pause-label="{{ __('Pause slideshow') }}" data-play-label="{{ __('Play slideshow') }}" aria-label="{{ __('Pause slideshow') }}" aria-pressed="false"></button>
            </div>
        </div>
    @endif
</section>

{{-- Who we are --}}
<section class="section" id="about-sec">
    <div class="wrap">
        <div class="statement">
            <div class="statement-side"><span class="kicker">{{ __('About Rest Developments') }}</span></div>
            <div>
                <p class="statement-text">{{ $aboutTr?->subtitle ?: __('We build homes and neighbourhoods where architecture, community and everyday life sit comfortably together.') }}</p>
                <a class="link" href="{{ route('about', ['locale' => $locale]) }}">{{ __('Read our story') }}</a>
            </div>
        </div>
        <div class="places">
            <div class="place"><h3>Kabul</h3><p>{{ __('Afghanistan') }}</p></div>
            <div class="place"><h3>Termiz</h3><p>{{ __('Uzbekistan') }}</p></div>
            <div class="place"><h3>Dubai</h3><p>{{ __('United Arab Emirates') }}</p></div>
        </div>
    </div>
</section>

{{-- Projects --}}
@if($lead)
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head">
            <div><span class="kicker">{{ __('Our projects') }}</span><h2>{{ __('Places to discover') }}</h2></div>
            <a class="link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('See all projects') }}</a>
        </div>
        @php($leadTr = $lead->t())
        <article class="project project-feature">
            <div class="project-media">
                @if($lead->image)<img src="{{ asset('storage/'.$lead->image) }}" alt="" loading="lazy" decoding="async" width="1000" height="800">@endif
                @if($lead->state)<span class="project-status {{ $lead->state === 'ongoing' ? 'is-ongoing' : '' }}">{{ $lead->state === 'ongoing' ? __('Under construction') : __(ucfirst($lead->state)) }}</span>@endif
            </div>
            <div>
                @if($lead->location)<span class="project-location">{{ $lead->location }}</span>@endif
                <h3><a href="{{ route('projects.show', ['locale' => $locale, 'slug' => $lead->slug]) }}">{{ $leadTr?->title }}</a></h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags($leadTr?->description ?? ''), 260) }}</p>
                <span class="btn" aria-hidden="true">{{ __('View project') }}</span>
            </div>
        </article>
        @if($others->isNotEmpty())
            <div class="project-grid project-grid--two">
                @foreach($others as $project)@include('partials.heritage-project-card')@endforeach
            </div>
        @endif
    </div>
</section>
@endif

{{-- Leadership message --}}
@if($aboutTr?->about_text)
<section class="section section--green on-dark">
    <div class="wrap">
        <figure class="quote" style="margin:0">
            <span class="quote-mark" aria-hidden="true">“</span>
            <div>
                <span class="kicker">{{ __('A message from our CEO') }}</span>
                <blockquote>{{ $aboutTr->about_text }}</blockquote>
                <figcaption>{{ $aboutTr->circle_text ?: 'Rest Developments' }}</figcaption>
            </div>
        </figure>
    </div>
</section>
@endif

{{-- Life at Rest --}}
<section class="section">
    <div class="wrap overlap">
        <div class="overlap-media">
            <img src="{{ $about?->image_right ? asset('storage/'.$about->image_right) : asset('assets/img/brand/interior.webp') }}" alt="{{ __('Living room in a Rest Developments apartment') }}" loading="lazy" decoding="async" width="1280" height="880">
        </div>
        <div class="overlap-panel">
            <span class="kicker">{{ __('Life at Rest') }}</span>
            <h2>{{ __('A home is more than its walls.') }}</h2>
            <p class="lead">{{ __('Bright apartments, shared courtyards and the small details that make a building feel like home.') }}</p>
            <a class="link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('Explore apartments') }}</a>
        </div>
    </div>
</section>

{{-- News --}}
@if($latestBlogs->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head">
            <div><span class="kicker">{{ __('News') }}</span><h2>{{ __('Latest from Rest') }}</h2></div>
            <a class="link" href="{{ route('blog.index', ['locale' => $locale]) }}">{{ __('All news') }}</a>
        </div>
        <div class="journal">
            @foreach($latestBlogs->take(3) as $post)
                <article class="journal-item">
                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->translatedFormat('d M Y') }}</time>
                    <div>
                        <h3><a href="{{ route('blog.show', ['locale' => $locale, 'slug' => $post->slug]) }}">{{ $post->t()?->title }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->t()?->content ?? ''), 130) }}</p>
                    </div>
                    <div class="journal-thumb">@if($post->thumb_large)<img src="{{ asset('storage/'.$post->thumb_large) }}" alt="" loading="lazy" decoding="async" width="400" height="267">@endif</div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.contact-band')
@endsection
