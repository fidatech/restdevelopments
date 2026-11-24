@extends('layouts.heritage')
@php
    $locale = app()->getLocale();
    $tr = $founder->t();
    $name = $tr?->name ?: 'Habibullah Ahmadzai';
    $position = $tr?->position ?: __('Founder');
    $initials = collect(preg_split('/\s+/u', trim($name)))->filter()->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp
@section('title', $name.', '.$position)
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags($tr?->quote ?: $tr?->message ?: '')), 155))
@section('content')

<section class="founder-hero">
    <div class="wrap founder-hero-inner">
        <div class="founder-portrait">
            @if($founder->image)
                <img src="{{ media_url($founder->image) }}" alt="{{ $name }}" width="900" height="1125" fetchpriority="high">
            @else
                <span class="founder-initials" aria-hidden="true">{{ $initials }}</span>
            @endif
        </div>
        <div class="founder-intro">
            <nav class="crumbs" aria-label="{{ __('Breadcrumb') }}">
                <a href="{{ route('home', ['locale' => $locale]) }}">{{ __('Home') }}</a> /
                <a href="{{ route('about', ['locale' => $locale]) }}">{{ __('About') }}</a> /
                <span aria-current="page">{{ __('Our founder') }}</span>
            </nav>
            <p class="founder-role">{{ $position }}</p>
            <h1>{{ $name }}</h1>
            @if($tr?->quote)
                <blockquote class="founder-quote"><p>{{ $tr->quote }}</p></blockquote>
            @endif
            <a class="text-link" href="#message">{{ __('Read his message') }} @include('partials.arrow')</a>
        </div>
    </div>
</section>

@if($tr?->message)
<section class="section" id="message">
    <div class="wrap founder-body">
        <h2>{{ __('A message from our founder') }}</h2>
        <div>
            <div class="prose founder-letter">{!! $tr->message !!}</div>
            <div class="founder-sign">
                @if($founder->signature)<img src="{{ media_url($founder->signature) }}" alt="" height="70">@endif
                <strong>{{ $name }}</strong>
                <span>{{ $position }}, Rest Developments</span>
            </div>
        </div>
    </div>
</section>
@endif

@if($tr?->biography)
<section class="section section--mist">
    <div class="wrap founder-body">
        <h2>{{ __('About') }} {{ $name }}</h2>
        <div class="prose">{!! $tr->biography !!}</div>
    </div>
</section>
@endif

<section class="section section--ink on-dark">
    <div class="wrap founder-next">
        <div>
            <span class="eyebrow">{{ __('Rest Developments') }}</span>
            <h2>{{ __('See what we are building') }}</h2>
        </div>
        <div class="actions">
            <a class="btn btn--gold" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('Explore our projects') }}</a>
            <a class="btn btn--line" href="{{ route('about', ['locale' => $locale]) }}">{{ __('Our story') }}</a>
        </div>
    </div>
</section>
@endsection
