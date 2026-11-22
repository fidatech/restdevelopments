{{-- resources/views/projects/show.blade.php --}}
@extends('layouts.base')
@section('title', $project->t()?->title ?? __('Project'))

@section('content')
    @php
        // Slides: gallery ordered; prepend cover if missing
        $slides = $project->galleries->sortBy('order')->values();
        if ($project->image && !$slides->firstWhere('image', $project->image)) {
            $slides = $slides->prepend((object) ['image' => $project->image]);
        }

        // Documents merged in one row
        $allDocs = collect()
            ->merge($docs['master_plan'] ?? collect())
            ->merge($docs['floor_plan'] ?? collect())
            ->merge($docs['brochure'] ?? collect())
            ->sortBy('order')
            ->values();

        // Features placeholder (will wire amenities later)
        $featuresAll = collect()
            ->merge($amenities ?? collect())
            ->merge($facilities ?? collect())
            ->sortBy('order')
            ->values();
        $featCols = $featuresAll->chunk(ceil(max(1, $featuresAll->count()) / 4));

        // Hero image source
        $heroImg = $slides->first()?->image ?: $project->image;
    @endphp

    <style>
        /* Full-bleed hero */
        .project-hero {
            position: relative;
            width: 100%;
            height: clamp(420px, 70vh, 760px);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .12);
        }

        .project-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .project-hero .hero-overlay {
            position: absolute;
            inset: 0;
            /* background: linear-gradient(180deg, rgba(0, 0, 0, .25), rgba(0, 0, 0, .45)); */
        }

        .project-hero .hero-caption {
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 24px;
            color: #fff;
        }

        .project-hero .hero-title {
            font-size: clamp(24px, 3vw, 42px);
            font-weight: 800;
            margin: 0 0 6px 0;
        }

        .hero-badges .badge {
            backdrop-filter: blur(6px);
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            font-weight: 600;
            margin-right: 8px;
        }

        /* Document cards */
        .doc-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, .06);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
            overflow: hidden;
        }

        .doc-card-thumb {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #f5f6f8;
        }

        .doc-card-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .doc-card-body {
            padding: .7rem .9rem .4rem;
        }

        .doc-card-title {
            font-weight: 600;
            font-size: 0.98rem;
            line-height: 1.3;
            margin: 0;
        }

        .doc-card-type {
            font-size: .8rem;
            color: #8a8fa3;
            margin-top: .2rem;
        }

        .doc-card-actions {
            padding: 0 .9rem .9rem .9rem;
        }

        /* Listing cards */
        .listing-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid rgba(0, 0, 0, .06);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .listing-thumb {
            width: 100%;
            aspect-ratio: 16/10;
            background: #f5f6f8;
        }

        .listing-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .listing-body {
            padding: .8rem .9rem 1rem;
        }

        .listing-title {
            font-weight: 700;
            font-size: 1rem;
            margin: 0 0 .2rem;
        }

        .listing-meta {
            font-size: .82rem;
            color: #8a8fa3;
        }
    </style>

    <div class="breadcumb-wrapper" data-bg-src="{{ asset('storage/' . $heroImg) }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        <h1 class="breadcumb-title">{{ $project->t()?->title }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li>{{ $project->t()?->title }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="pt-50 space-extra-bottom">
        <div class="container">

            {{-- FULL HERO IMAGE --}}
            @if ($heroImg)
                <div class="project-hero mb-30">
                    <img src="{{ asset('storage/' . $heroImg) }}" alt="{{ $project->t()?->title }}">
                    <div class="hero-overlay"></div>
                    <div class="hero-caption">
                        <div class="hero-badges mb-2">
                            @if ($project->state === 'ongoing')
                                <span class="badge rounded-pill">{{ __('Featured') }}</span>
                            @endif
                            @if ($project->purpose)
                                <span class="badge rounded-pill">{{ $project->purpose }}</span>
                            @endif
                        </div>
                        <h1 class="hero-title">{{ $project->t()?->title }}</h1>
                        <div class="small opacity-75">{{ optional($project->created_at)->format('d M, Y') }}</div>
                    </div>
                </div>
            @endif

            <div class="row gx-30">
                <div class="col-xxl-8 col-lg-7">
                    <div class="property-page-single">
                        <div class="page-content">

                            {{-- About --}}
                            <h2 class="page-title">{{ __('About This Property') }}</h2>
                            @if ($project->t()?->description)
                                <p class="mb-30">{!! nl2br(e($project->t()->description)) !!}</p>
                            @endif
                            @if (!empty($project->extra_long_text))
                                <p class="mb-30">{!! nl2br(e($project->extra_long_text)) !!}</p>
                            @endif

                            {{-- Overview --}}
                            <h2 class="page-title mb-20">{{ __('Property Overview') }}</h2>
                            <ul class="property-grid-list">
                                <li>
                                    <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-1.svg') }}" alt="img"></div>
                                    <div class="property-grid-list-details">
                                        <h4 class="property-grid-list-title">ID NO.</h4>
                                        <p class="property-grid-list-text">#{{ $project->id }}</p>
                                    </div>
                                </li>

                                @if (!empty($project->type))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-2.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Type</h4>
                                            <p class="property-grid-list-text">{{ $project->type }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!empty($project->rooms))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-3.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Room</h4>
                                            <p class="property-grid-list-text">{{ $project->rooms }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->beds))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-4.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Bedroom</h4>
                                            <p class="property-grid-list-text">{{ $project->beds }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->baths))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-5.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Bath</h4>
                                            <p class="property-grid-list-text">{{ $project->baths }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!empty($project->purpose))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-6.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Purpose</h4>
                                            <p class="property-grid-list-text">{{ $project->purpose }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->area))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-7.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">m²</h4>
                                            <p class="property-grid-list-text">{{ number_format($project->area) }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->parking))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-8.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Parking</h4>
                                            <p class="property-grid-list-text">{{ $project->parking ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->elevator))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-9.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Elevator</h4>
                                            <p class="property-grid-list-text">{{ $project->elevator ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </li>
                                @endif

                                @if (!is_null($project->wifi))
                                    <li>
                                        <div class="property-grid-list-icon"><img src="{{ asset('assets/img/icon/property-single-icon1-10.svg') }}" alt="img"></div>
                                        <div class="property-grid-list-details">
                                            <h4 class="property-grid-list-title">Wifi</h4>
                                            <p class="property-grid-list-text">{{ $project->wifi ? 'Yes' : 'No' }}</p>
                                        </div>
                                    </li>
                                @endif
                            </ul>

                            {{-- Gallery grid 5/7/7/5 --}}
                            @if ($slides->count())
                                <h3 class="page-title mt-50 mb-30">{{ __('From Our Gallery') }}</h3>
                                @php $g = $slides->values(); @endphp
                                <div class="row gy-4">
                                    @foreach ($g as $s)
                                        @php
                                        $cover = str_contains($s->image, 'storage/') ? str_replace('storage/', '', $s->image) : $s->image;
                                        $cover = asset('storage/' . $cover);
                                        @endphp
                                        <div class="col-xl-5">
                                            <div class="property-gallery-card">
                                                <div class="property-gallery-card-img"><img class="w-100" src="{{ $cover }}" alt="img"></div>
                                                <a class="icon-btn popup-image" href="{{ $cover }}"><i class="fal fa-magnifying-glass-plus"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Units --}}
                            @if (($units ?? collect())->count())
                                <h3 class="page-title mt-50 mb-20">{{ __('Units & Types') }}</h3>
                                <div class="row gy-4 mb-2">
                                    @foreach ($units as $u)
                                        <div class="col-md-6">
                                            <div class="property-grid-plan rounded p-3 shadow-sm" style="background:#fff">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-dark me-2">{{ $u->bedrooms }} {{ __('BR') }}</span>
                                                    <h5 class="mb-0">{{ $u->name ?? $u->bedrooms . ' ' . __('Bedroom') }}</h5>
                                                    @if (!is_null($u->count))
                                                        <span class="text-muted small ms-auto">{{ __('Units') }}: {{ $u->count }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted">
                                                    @php
                                                        $minA = $u->min_area ? number_format($u->min_area) : null;
                                                        $maxA = $u->max_area ? number_format($u->max_area) : null;
                                                    @endphp
                                                    @if ($minA || $maxA)
                                                        <div class="mb-1"><i class="far fa-square me-1"></i>{{ __('Area') }}:
                                                            @if ($minA && $maxA)
                                                                {{ $minA }} – {{ $maxA }} m²
                                                            @elseif($minA)
                                                                ≥ {{ $minA }} m²
                                                            @else
                                                                ≤ {{ $maxA }} m²
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if ($u->description)
                                                        <div class="small">{!! nl2br(e($u->description)) !!}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Features & amenities --}}
                            @if ($featuresAll->count())
                                <h3 class="page-title mt-50 mb-25">{{ __('Features & amenities') }}</h3>
                                <div class="row gy-3">
                                    @foreach ($featCols as $col)
                                        <div class="col-xxl-3 col-sm-6">
                                            <div class="checklist">
                                                <ul>
                                                    @foreach ($col as $f)
                                                        <li><i class="far fa-square-check"></i>{{ $f->t()?->title }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Location --}}
                            @if ($project->map_embed_url || ($project->lat && $project->lng) || $project->location)
                                <h3 class="page-title mt-45 mb-30">{{ __('Location') }}</h3>
                                <div class="location-map">
                                    <div class="contact-map">
                                        @if ($project->map_embed_url)
                                            <iframe src="{{ $project->map_embed_url }}" allowfullscreen="" loading="lazy"></iframe>
                                        @else
                                            <iframe src="https://www.google.com/maps?q={{ $project->lat }},{{ $project->lng }}&hl={{ app()->getLocale() }}&z=14&output=embed"
                                                allowfullscreen="" loading="lazy"></iframe>
                                        @endif
                                    </div>
                                    <div class="location-map-address">
                                        <div class="thumb">
                                            <img src="{{ asset($slides->first() ? 'storage/' . $slides->first()->image : 'assets/img/property/property_inner_1.jpg') }}"
                                                alt="img">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="title">{{ __('Address:') }}</h4>
                                            <p class="text">{{ $project->t()?->address ?? ($project->location ?? '—') }}</p>
                                            @if (!empty($project->postal_code))
                                                <h4 class="title">{{ __('Post Code:') }}</h4>
                                                <p class="text">{{ $project->postal_code }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Floor Plan Tabs --}}
                            @php $floorPlans = ($docs['floor_plan'] ?? collect())->values(); @endphp
                            @if ($floorPlans->count())
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-lg-auto">
                                        <h3 class="page-title mt-50 mb-30">{{ __('Floor Plan') }}</h3>
                                    </div>
                                    <div class="col-lg-auto">
                                        <ul class="nav nav-tabs property-tab mt-50" role="tablist">
                                            @foreach ($floorPlans as $i => $d)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $i === 0 ? 'active' : '' }}" id="floor-tab{{ $i + 1 }}" data-bs-toggle="tab"
                                                        data-bs-target="#floor-tab{{ $i + 1 }}-pane" type="button" role="tab"
                                                        aria-controls="floor-tab{{ $i + 1 }}-pane" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                                                        {{ $d->title ?: __('Floor') . ' ' . ($i + 1) }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="tab-content">
                                    @foreach ($floorPlans as $i => $d)
                                        <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="floor-tab{{ $i + 1 }}-pane" role="tabpanel"
                                            aria-labelledby="floor-tab{{ $i + 1 }}" tabindex="0">
                                            <div class="property-grid-plan">
                                                <div class="property-grid-thumb">
                                                    <img src="{{ asset($d->preview_image ?  $d->preview_image : 'assets/img/property/property_inner_10.jpg') }}"
                                                        alt="img">
                                                </div>
                                                <div class="property-grid-details">
                                                    <h4 class="property-grid-title">{{ $d->title ?: __('Floor Plan') }}</h4>
                                                    @if (!empty($d->description))
                                                        <p class="property-grid-text">{{ $d->description }}</p>
                                                    @endif
                                                    @if ($d->file)
                                                        <a class="th-btn style4 th-btn-icon mt-3" href="{{ asset($d->file) }}" target="_blank" download>
                                                            {{ __('Download PDF') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Documents row (small cards, one row carousel) --}}
                            @if ($allDocs->count())
                                <h3 class="page-title mt-50 mb-25">{{ __('Documents') }}</h3>
                                <div class="slider-area">
                                    <div class="swiper th-slider" id="docsSlider"
                                        data-slider-options='{
                                            "spaceBetween":16,
                                            "loop":false,
                                            "autoplayDisableOnInteraction":"true",
                                            "breakpoints":{
                                              "0":{"slidesPerView":1.2},
                                              "480":{"slidesPerView":2},
                                              "768":{"slidesPerView":3},
                                              "1200":{"slidesPerView":4}
                                            }
                                          }'>
                                        <div class="swiper-wrapper">
                                            @foreach ($allDocs as $d)
                                                @php
                                                    $title = $d->title ?: ucwords(str_replace('_', ' ', $d->type));
                                                    $imgSrc = $d->preview_image ? asset($d->preview_image) : asset('assets/img/property/property_inner_10.jpg');
                                                @endphp
                                                <div class="swiper-slide">
                                                    <div class="doc-card">
                                                        <a href="{{ $d->file ? asset($d->file) : 'javascript:void(0)' }}"
                                                            @if ($d->file) target="_blank" @endif class="doc-card-thumb">
                                                            <img src="{{ $imgSrc }}" alt="{{ $title }}">
                                                        </a>
                                                        <div class="doc-card-body">
                                                            <h6 class="doc-card-title">{{ $title }}</h6>
                                                            <div class="doc-card-type">{{ ucwords(str_replace('_', ' ', $d->type)) }}</div>
                                                        </div>
                                                        <div class="doc-card-actions">
                                                            @if ($d->file)
                                                                <a class="th-btn style4 th-btn-icon w-100" href="{{ asset($d->file) }}" target="_blank" download>
                                                                    {{ __('Download PDF') }}
                                                                </a>
                                                            @else
                                                                <button class="th-btn style4 th-btn-icon w-100" disabled>{{ __('Not Available') }}</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button data-slider-prev="#docsSlider" class="slider-arrow style3 slider-prev">
                                        <img src="{{ asset('assets/img/icon/arrow-left.svg') }}" alt="icon">
                                    </button>
                                    <button data-slider-next="#docsSlider" class="slider-arrow style3 slider-next">
                                        <img src="{{ asset('assets/img/icon/arrow-right.svg') }}" alt="icon">
                                    </button>
                                </div>
                            @endif

                            <section class="space bg-ivory">
                                <div class="container">
                                    <div class="title-area mb-4 text-center">
                                        <h2 class="sec-title text-theme">{{ __('Payment Plans') }}</h2>
                                        <p>{{ __('Flexible installment options for investors and families.') }}</p>
                                    </div>
                                    <div class="row g-4 justify-content-center">
                                        <div class="col-md-4">
                                            <div class="card h-100 p-4 text-center shadow">
                                                <h3 class="fw-bold text-theme">Standard Plan</h3>
                                                <p class="mb-2">10% {{ __('Upfront') }}</p>
                                                <p class="mb-0">70% {{ __('Over 36 Months') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card h-100 p-4 text-center shadow">
                                                <h3 class="fw-bold text-theme">Investor Plan</h3>
                                                <p class="mb-2">50% {{ __('Upfront') }}</p>
                                                <p class="mb-0">50% {{ __('Over 12 Months') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            {{-- Video --}}
                            @if (!empty($project->video_url))
                                <h3 class="page-title mt-50 mb-30">{{ __('Property Video') }}</h3>
                                <div class="video-box2 mb-30">
                                    <img src="{{ asset($slides->get(2) ? 'storage/' . $slides->get(2)->image : 'assets/img/property/property_inner_3.jpg') }}" alt="img">
                                    <a href="{{ $project->video_url }}" class="play-btn style4 popup-video"><i class="fa-sharp fa-solid fa-play"></i></a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                {{-- Sidebar remains optional – we moved For Rent + Listings under hero --}}
                <div class="col-xxl-4 col-lg-5">
                    <aside class="sidebar-area">
                        @if (!empty($project->price_starts_from))
                            <div class="widget widget-property-contact h-100" style="border-radius:16px; overflow:hidden;">
                                @if (!is_null($project->price_starts_from))
                                    <h4 class="widget_price">
                                        {{ __('Prices Start from') }} {{ $project->price_starts_from }}
                                    </h4>
                                @endif
                                <p class="widget_text mb-3">{{ __('I am interested in this property') }}</p>
                                <form action="#" class="widget-property-contact-form">
                                    <div class="form-group"><input type="text" class="form-control style-border" placeholder="{{ __('Name') }}"></div>
                                    <div class="form-group"><input type="text" class="form-control style-border" placeholder="{{ __('Email Address') }}"></div>
                                    <div class="form-group"><input type="text" class="form-control style-border" placeholder="{{ __('Phone Number') }}"></div>
                                    <button class="th-btn style-white th-btn-icon w-100 mt-10">{{ __('Request A Video') }}</button>
                                </form>
                            </div>
                        @endif
                        @if (($featuredProjects ?? collect())->count())
                            <div class="widget">
                                <h3 class="widget_title">Featured Listing</h3>
                                <div class="recent-post-wrap">
                                    @foreach ($featuredProjects as $fp)
                                        @php
                                            $cover = optional($fp->galleries->first())->image ?: $fp->image;
                                            $title = $fp->t()?->title ?? '#' . $fp->id;
                                            $url = route('projects.show', ['locale' => app()->getLocale(), 'slug' => $fp->slug]);
                                        @endphp
                                        <div class="recent-post">
                                            <div class="media-img">
                                                <a href="{{ $url }}"><img src="{{ asset('storage/' . $cover) }}" alt="Blog Image"></a>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="post-title"><a class="text-inherit" href="{{ $url }}">{{ $title }}</a></h4>
                                                <div class="recent-post-meta">
                                                    <a href="{{ $url }}"><i class="far fa-calendar"></i>{{ optional($fp->created_at)->format('d/m/Y') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="widget widget_banner" data-bg-src="assets/img/widget/widget-banner.png">
                            <div class="widget-banner text-center">
                                <h3 class="title">Need Help? We Are Here To Help You</h3>
                                <div class="logo"><img src="{{ asset('storage/' . $globalContactSetting->logo_dark) }}" alt="img"></div>
                                <h4 class="subtitle">You Get Online support</h4>
                                <h5 class="link"><a href="tel:{!! $globalContactSetting->phone_2 !!}">{!! $globalContactSetting->phone_2 !!}</a></h5>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

        </div>
    </section>
@endsection
