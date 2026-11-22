@extends('layouts.base')

@section('title', __('Latest Launches'))

@push('styles')
    <style>
        /* set all card bodies to the same height */
        .fixed-card-body {
            min-height: 200px;
        }
    </style>
@endpush

@section('content')
    <!--============================== Breadcumb ==============================-->
    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        <h1 class="breadcumb-title">{{ __('Latest Launches') }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li>{{ __('Latest Launches') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--============================== Page Area ==============================-->
    <section class="space-top space-extra-bottom">
        <div class="container">
            <div class="th-sort-bar mb-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h2 class="fw-medium">{{ __('Latest Launches') }}</h2>
                    </div>
                </div>
            </div>

            <div class="row gy-40">
                @foreach ($projects as $project)
                    <div class="col-md-6">
                        <div class="agency-card">
                            <div class="agency-card">
                                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->t()?->title }}">
                            </div>
                            <div class="agency-card-details fixed-card-body">
                                <h4 class="agency-card-title">
                                    <a href="{{ route('projects.show', [app()->getLocale(), $project->slug]) }}">
                                        {{ $project->t()?->title }}
                                    </a>
                                </h4>

                                <p class="agency-card-text">
                                    {{ \Str::limit(strip_tags($project->t()?->description), 200) }}
                                </p>

                                <div class="agency-card-meta">
                                    <div class="media-left">
                                        <p class="agency-card-text mb-1">
                                            <i class="far fa-location-dot"></i>
                                            {{ $project->location }}
                                        </p>
                                        <p class="agency-card-text mb-1">
                                            <i class="far fa-up-right-and-down-left-from-center"></i>
                                            {{ $project->area }} m<sup>2</sup>
                                        </p>
                                        <p class="agency-card-text mb-0">
                                            <i class="far fa-dollar-sign"></i>
                                            {{ __('Starts From:') }} ${{ number_format($project->price_starts_from) }}
                                        </p>
                                    </div>
                                    <div class="btn-wrap">
                                        <a href="{{ route('projects.show', [app()->getLocale(), $project->slug]) }}" class="th-btn style-border2 th-btn-icon">
                                            {{ __('Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            @if ($projects->hasPages())
                <div class="mt-60">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
