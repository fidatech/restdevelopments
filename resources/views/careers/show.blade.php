@extends('layouts.heritage')
@section('title', $vacancy->translation()?->title ?? __('Careers'))
@section('content')
@php($tr = $vacancy->translation())
@include('partials.page-head', ['title' => $tr?->title ?? __('Open role'), 'kicker' => collect([$tr?->location, $tr?->type])->filter()->implode(', '), 'lead' => $vacancy->deadline ? __('Apply by').' '.$vacancy->deadline->translatedFormat('d F Y') : null, 'crumbs' => [[__('Careers'), route('careers.index', ['locale' => app()->getLocale()])]]])
<article class="section">
    <div class="wrap">
        <div class="prose">
            <h2 style="margin-top:0">{{ __('The role') }}</h2>
            {!! $tr?->description !!}
            @if($tr?->responsibilities)<h2>{{ __('Responsibilities') }}</h2>{!! $tr->responsibilities !!}@endif
            @if($tr?->requirements)<h2>{{ __('Requirements') }}</h2>{!! $tr->requirements !!}@endif
            <p style="margin-top:2.5rem"><a class="btn" href="mailto:careers@restdevelopments.com?subject={{ rawurlencode('Application - '.$tr?->title) }}">{{ __('Apply by email') }}</a></p>
        </div>
    </div>
</article>
@endsection
