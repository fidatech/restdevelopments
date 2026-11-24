@extends('layouts.heritage')
@section('title', $vacancy->translation()?->title ?? __('Careers'))
@section('content')
@php($tr = $vacancy->translation())
@php($closed = $vacancy->deadline && $vacancy->deadline->endOfDay()->isPast())
@include('partials.page-head', ['image' => asset('assets/img/brand/towers-park.webp'), 'title' => $tr?->title ?? __('Open role'), 'kicker' => collect([$tr?->location, $tr?->type])->filter()->implode(', '), 'lead' => $vacancy->deadline ? ($closed ? __('Applications closed on') : __('Apply by')).' '.$vacancy->deadline->translatedFormat('d F Y') : null, 'crumbs' => [[__('Careers'), route('careers.index', ['locale' => app()->getLocale()])]]])
<article class="section">
    <div class="wrap">
        <div class="prose">
            @if($closed)<p class="notice" role="status">{{ __('This position is closed and no longer accepting applications. Send your CV to careers@restdevelopments.com and we will contact you when a similar role opens.') }}</p>@endif
            <h2 style="margin-top:0">{{ __('The role') }}</h2>
            {!! $tr?->description !!}
            @if($tr?->responsibilities)<h2>{{ __('Responsibilities') }}</h2>{!! $tr->responsibilities !!}@endif
            @if($tr?->requirements)<h2>{{ __('Requirements') }}</h2>{!! $tr->requirements !!}@endif
            @if($closed)
                <p style="margin-top:2.5rem"><a class="btn btn--line" href="{{ route('careers.index', ['locale' => app()->getLocale()]) }}">{{ __('See all careers') }}</a></p>
            @else
                <p style="margin-top:2.5rem"><a class="btn btn--green" href="mailto:careers@restdevelopments.com?subject={{ rawurlencode('Application - '.$tr?->title) }}">{{ __('Apply by email') }}</a></p>
            @endif
        </div>
    </div>
</article>
@endsection
