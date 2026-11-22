@extends('layouts.heritage')
@section('title', $vacancy->translation()?->title ?? __('Careers'))
@section('content')
@php($tr=$vacancy->translation())
<div class="page-intro"><div class="wrap"><span class="eyebrow">{{ __('Opportunity') }} · {{ $tr?->location }}</span><h1>{{ $tr?->title }}</h1><p class="lead">{{ $tr?->type }} @if($vacancy->deadline) · {{ __('Apply by') }} {{ $vacancy->deadline->format('d M Y') }} @endif</p></div></div><article class="section"><div class="wrap prose"><h2>{{ __('The role') }}</h2><div>{!! $tr?->description !!}</div>@if($tr?->requirements)<h2>{{ __('Requirements') }}</h2><div>{!! $tr->requirements !!}</div>@endif @if($tr?->responsibilities)<h2>{{ __('Responsibilities') }}</h2><div>{!! $tr->responsibilities !!}</div>@endif<hr class="rule"><a class="button" href="mailto:careers@restdevelopments.com?subject={{ rawurlencode('Application - '.$tr?->title) }}">{{ __('Apply via email') }} ↗</a></div></article>
@endsection
