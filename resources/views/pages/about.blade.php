@extends('layouts.heritage')
@section('title', __('About our company'))
@section('description', __('Learn about Rest Developments, our approach to residential and mixed-use property, and the people behind our projects.'))
@section('content')
@php($locale = app()->getLocale())
@php($aboutTr = $about?->t())
@include('partials.page-head', ['title' => $aboutTr?->title ?? __('About Rest Developments'), 'current' => __('Company'), 'kicker' => __('Our company'), 'lead' => $aboutTr?->subtitle ?? __('Building places for people and their future.')])

<section class="section">
    <div class="wrap split">
        <div>
            <span class="kicker">{{ __('Our story') }}</span>
            <h2 style="margin-bottom:1.4rem">{{ __('A clear purpose. A lasting place.') }}</h2>
            <p class="lead">{{ __('Rest Developments creates residential and mixed-use places across Afghanistan and Uzbekistan. We focus on quality construction, thoughtful design and long-term value for homeowners and investors.') }}</p>
        </div>
        <div class="framed"><img src="{{ $about?->image_left ? asset('storage/'.$about->image_left) : asset('assets/img/brand/aerial.webp') }}" alt="{{ __('A Rest Developments project') }}" width="960" height="720" decoding="async"></div>
    </div>
</section>

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

@if($sections->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('What guides us') }}</span><h2>{{ __('Mission and vision') }}</h2></div></div>
        <div class="values">
            @foreach($sections as $section)
                <div class="value"><h3>{{ $section->t()?->title }}</h3><div class="prose" style="font-size:1rem;color:var(--muted)">{!! $section->t()?->body !!}</div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($whyUsItems->isNotEmpty())
<section class="section section--night on-dark">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Why Rest') }}</span><h2>{{ $whyUsSetting?->t()?->title ?? __('Why families choose us') }}</h2>@if($whyUsSetting?->t()?->description)<p style="color:rgba(255,255,255,.75)">{{ $whyUsSetting->t()->description }}</p>@endif</div></div>
        <div class="values">
            @foreach($whyUsItems as $item)
                <div class="value"><h3>{{ $item->t()?->title }}</h3><p>{{ $item->t()?->description }}</p></div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($team->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Our people') }}</span><h2>{{ __('The team behind the work') }}</h2></div><a class="link" href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Meet the whole team') }}</a></div>
        <div class="people">
            @foreach($team->take(4) as $member)
                <article class="person"><div class="person-photo">@if($member->image)<img src="{{ asset('storage/'.$member->image) }}" alt="{{ $member->t()?->name }}" width="480" height="600" loading="lazy">@endif</div><h3>{{ $member->t()?->name }}</h3><p>{{ $member->t()?->position }}</p></article>
            @endforeach
        </div>
    </div>
</section>
@endif
@include('partials.contact-band')
@endsection
