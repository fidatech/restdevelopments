@extends('layouts.heritage')
@section('title', __('Careers'))
@section('description', __('Explore career opportunities with Rest Developments.'))
@section('content')
<div class="page-intro"><div class="wrap"><span class="eyebrow">{{ __('Join us') }}</span><h1>{{ __('Build a future with Rest.') }}</h1><p class="lead">{{ __('Explore opportunities with the team behind our developments.') }}</p></div></div><section class="section"><div class="wrap"><div class="cards">@forelse($vacancies as $job)@php($tr=$job->translation())<article class="card"><div class="card-body"><span class="eyebrow">{{ $tr?->location }} · {{ $tr?->type }}</span><h3>{{ $tr?->title }}</h3><p>{{ \Illuminate\Support\Str::limit(strip_tags($tr?->description ?? ''), 150) }}</p><a class="text-link" href="{{ route('careers.show', ['locale'=>app()->getLocale(), 'slug'=>$job->slug]) }}">{{ __('View opportunity') }} </a></div></article>@empty<p class="empty">{{ __('There are no open roles at the moment.') }}</p>@endforelse</div></div></section>
@endsection
