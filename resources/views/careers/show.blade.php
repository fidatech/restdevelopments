@extends('layouts.base')
@section('title', $vacancy->translation()->title)

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        <h1 class="breadcumb-title">{{ $vacancy->translation()->title }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><a href="{{ url('/careers') }}">{{ __('Careers') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="space bg-amber">
    <div class="container">

        <div class="mb-4">
            <a href="{{ route('careers.index') }}" class="btn btn-sm btn-secondary">&larr; {{ __('Back to list') }}</a>
        </div>

        <h1>{{ $vacancy->translation()->title }}</h1>
        <p class="text-muted">{{ $vacancy->translation()->location }} | {{ $vacancy->translation()->type }}</p>

        @if($vacancy->deadline)
            <p><strong>{{ __('Deadline') }}:</strong> {{ $vacancy->deadline->format('Y-m-d') }}</p>
        @endif

        @if($vacancy->salary)
            <p><strong>{{ __('Salary') }}:</strong> {{ $vacancy->salary }}</p>
        @endif

        <h4 class="mt-4">{{ __('Job Description') }}</h4>
        {!! $vacancy->translation()->description !!}

        @if($vacancy->translation()->requirements)
            <h4 class="mt-4">{{ __('Requirements') }}</h4>
            {!! $vacancy->translation()->requirements !!}
        @endif

        @if($vacancy->translation()->responsibilities)
            <h4 class="mt-4">{{ __('Responsibilities') }}</h4>
            {!! $vacancy->translation()->responsibilities !!}
        @endif

        <div class="mt-4">
            <a href="mailto:careers@restdevelopments.com?subject=Application - {{ $vacancy->translation()->title }}"
               class="btn btn-primary">
                {{ __('Apply via Email') }}
            </a>
        </div>
    </div>
</div>
@endsection
