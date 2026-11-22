@extends('layouts.base')
@section('title', __('Careers'))

@section('content')
    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        <h1 class="breadcumb-title">{{ __('Careers') }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li>{{ __('Careers') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="space bg-ivory">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">{{ __('Join Our Team') }}</span>
                <h2 class="sec-title text-theme">{{ __('Current Vacancies') }}</h2>
            </div>

            <div class="row g-4">
                @forelse($vacancies as $job)
                    @php
                        $tr = $job->translation();
                    @endphp
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="mb-2">{{ $tr->title }}</h5>

                                <ul class="list-unstyled small text-muted mb-3">
                                    @if ($tr->location)
                                        <li><i class="fas fa-map-marker-alt me-1"></i> {{ $tr->location }}</li>
                                    @endif
                                    @if ($tr->type)
                                        <li><i class="fas fa-briefcase me-1"></i> {{ $tr->type }}</li>
                                    @endif
                                    @if ($job->salary)
                                        <li><i class="fas fa-dollar-sign me-1"></i> {{ $job->salary }}</li>
                                    @endif
                                    @if ($job->deadline)
                                        <li><i class="far fa-calendar-alt me-1"></i> {{ $job->deadline->format('d M Y') }}</li>
                                    @endif
                                </ul>

                                <p class="flex-grow-1">{!! Str::limit(strip_tags($tr->description), 120) !!}</p>

                                <a href="{{ route('careers.show', $job->slug) }}" class="th-btn style4 th-btn-icon" style="color:white !important;">
                                    {{ __('View & Apply') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>{{ __('No vacancies available at the moment.') }}</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection
