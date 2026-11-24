@extends('layouts.heritage')
@section('title', __('About our company'))
@section('description', __('Learn about Rest Developments, our approach to residential and mixed-use property, and the people behind our projects.'))
@section('content')
@php
    $locale = app()->getLocale();
    $aboutTr = $about?->t();
    $founder = \App\Models\FounderSetting::with('translations')->where('status', true)->first();
    $founderTr = $founder?->t();
@endphp
@include('partials.page-head', ['image' => asset('assets/img/brand/towers-night.webp'), 'title' => $aboutTr?->title ?? __('About Rest Developments'), 'current' => __('About'), 'kicker' => __('Our company'), 'lead' => $aboutTr?->subtitle])

<section class="section">
    <div class="wrap">
        <article class="feature">
            <div class="feature-media"><img src="{{ $about?->image_left ? media_url($about->image_left) : asset('assets/img/brand/aerial.webp') }}" alt="{{ __('A Rest Developments project') }}" width="1000" height="620" decoding="async"></div>
            <div>
                <span class="eyebrow">{{ __('Our story') }}</span>
                <h2>{{ __('A clear purpose. A lasting place.') }}</h2>
                <p class="lead">{{ __('Rest Developments creates residential and mixed-use places across Afghanistan and Uzbekistan. We focus on quality construction, thoughtful design and long-term value for homeowners and investors.') }}</p>
            </div>
        </article>
        @if($founderTr)
        <article class="feature feature--flip">
            <div>
                <span class="eyebrow">{{ __('A message from our founder') }}</span>
                <blockquote>“{{ $founderTr->quote ?: \Illuminate\Support\Str::limit(trim(strip_tags($founderTr->message ?? '')), 220) }}”</blockquote>
                <cite>{{ $founderTr->name }}@if($founderTr->position), {{ $founderTr->position }}@endif</cite>
                <p style="margin-top:28px"><a class="btn btn--line" href="{{ route('founder', ['locale' => $locale]) }}">{{ __('Read the full message') }}</a></p>
            </div>
            <div class="feature-media">
                @if($founder->image)
                    <img class="is-portrait" src="{{ media_url($founder->image) }}" alt="{{ $founderTr->name }}" loading="lazy" width="900" height="1125">
                @else
                    <img src="{{ asset('assets/img/brand/courtyard.webp') }}" alt="{{ __('Courtyard at Rest Complex') }}" loading="lazy" width="920" height="496">
                @endif
            </div>
        </article>
        @elseif($aboutTr?->about_text)
        <article class="feature feature--flip">
            <div>
                <span class="eyebrow">{{ __('A message from our CEO') }}</span>
                <blockquote>“{{ $aboutTr->about_text }}”</blockquote>
                <cite>{{ $aboutTr->circle_text ?: 'Rest Developments' }}</cite>
            </div>
            <div class="feature-media"><img src="{{ asset('assets/img/brand/courtyard.webp') }}" alt="{{ __('Courtyard at Rest Complex') }}" loading="lazy" width="920" height="496"></div>
        </article>
        @endif
    </div>
</section>

@if($sections->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('What guides us') }}</span><h2>{{ __('Mission, vision and values') }}</h2></div></div>
        <div class="values">
            @foreach($sections as $section)
                <div class="value"><h3>{{ $section->t()?->title }}</h3><div class="prose">{!! $section->t()?->body !!}</div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($whyUsItems->isNotEmpty())
<section class="section section--ink on-dark">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Why Rest') }}</span><h2>{{ $whyUsSetting?->t()?->title ?? __('Why families choose us') }}</h2></div>@if($whyUsSetting?->t()?->description)<p>{{ $whyUsSetting->t()->description }}</p>@endif</div>
        <div class="values">
            @foreach($whyUsItems as $item)<div class="value"><h3>{{ $item->t()?->title }}</h3><p>{{ $item->t()?->description }}</p></div>@endforeach
        </div>
    </div>
</section>
@endif

@if($team->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><h2>{{ __('Our people') }}</h2><a class="text-link" href="{{ route('team.index', ['locale' => $locale]) }}">{{ __('Meet the whole team') }} @include('partials.arrow')</a></div>
        <div class="people">
            @foreach($team->take(4) as $member)
                <article class="person"><div class="person-photo">@if($member->image)<img src="{{ media_url($member->image) }}" alt="{{ $member->t()?->name }}" width="480" height="600" loading="lazy">@endif</div><h3>{{ $member->t()?->name }}</h3><p>{{ $member->t()?->position }}</p></article>
            @endforeach
        </div>
    </div>
</section>
@endif
@include('partials.contact-band')
@endsection
