@extends('layouts.base')
@section('title', ($about?->t()?->title) ?? __('About'))

@section('content')
    <!--==============================
        Breadcumb
    ============================== -->
    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        @php $tr = $about?->t(); @endphp
                        <h1 class="breadcumb-title">{{ $tr?->title ?? __('About Us') }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li>{{ $tr?->title ?? __('About Us') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--==============================
        About Area
    ==============================-->
    @php
        // typed groups from controller
        $vision   = optional($byType->get('vision'))?->first();
        $mission  = optional($byType->get('mission'))?->first();
        $values   = optional($byType->get('values'))?->first();
        $customs  = $byType->get('custom') ?? collect();

        $vTr = $vision?->t();
        $mTr = $mission?->t();
        $valTr = $values?->t();

        // pick images: prefer section image (526x370), else fallback to AboutSetting images
        $visionImage  = ($vision && $vision->icon_set==='image' && $vision->image)
                        ? asset('storage/'.$vision->image)
                        : ($about?->image_right ? asset('storage/'.$about->image_right) : null); // Vision image RIGHT side

        $missionImage = ($mission && $mission->icon_set==='image' && $mission->image)
                        ? asset('storage/'.$mission->image)
                        : ($about?->image_left ? asset('storage/'.$about->image_left) : null);   // Mission image LEFT side

        // Build a list for Values + Customs, each with its own image (if any) and translation
        $moreBlocks = collect();
        if ($values) $moreBlocks->push($values);
        if ($customs->count()) $moreBlocks = $moreBlocks->merge($customs);

        // checklist from values body (optional)
        $checkItems = [];
        if ($valTr?->body) {
            $checkItems = preg_split('/\r\n|\r|\n|;/', $valTr->body);
            $checkItems = array_values(array_filter(array_map('trim', $checkItems)));
        }
    @endphp

    <div class="space bg-white overflow-hidden" id="about-sec">
        <div class="sec-bg-shape2-1 spin shape-mockup d-xl-block d-none" data-bottom="25%" data-right="12%">
            <img src="{{ asset('assets/img/shape/section_shape_2_1.jpg') }}" alt="img">
        </div>
        <div class="sec-bg-shape2-1 jump shape-mockup d-xl-block d-none" data-bottom="0%" data-left="5%">
            <img src="{{ asset('assets/img/shape/section_shape_2_3.jpg') }}" alt="img">
        </div>

        <div class="container">
            <div class="about-page-wrap">

                {{-- VISION: text LEFT, image RIGHT --}}
                @if($vTr || $visionImage)
                <div class="row gy-40 justify-content-between align-items-center mb-60">
                    <div class="col-lg-6">
                        <div class="title-area mb-0">
                            <h2 class="sec-title text-theme mb-2">{{ $vTr?->title ?? __('Vision') }}</h2>
                            @if($vTr?->body)
                                <p class="text-theme mb-0">{!! nl2br(e($vTr->body)) !!}</p>
                            @elseif($tr?->subtitle)
                                <p class="text-theme mb-0">{!! nl2br(e($tr->subtitle)) !!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5">
                        @if($visionImage)
                        <div class="img-box3">
                            <div class="img1">
                                <img src="{{ $visionImage }}" alt="Vision">
                            </div>

                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- MISSION: image LEFT, text RIGHT --}}
                @if($mTr || $missionImage)
                <div class="row gy-40 justify-content-between align-items-center mb-60 flex-row-reverse flex-lg-row">
                    <div class="col-lg-6 order-2 order-lg-2">
                        <div class="title-area mb-0">
                            <h2 class="sec-title text-theme mb-2">{{ $mTr?->title ?? __('Mission') }}</h2>
                            @if($mTr?->body)
                                <p class="text-theme mb-0">{!! nl2br(e($mTr->body)) !!}</p>
                            @elseif($tr?->about_text)
                                <p class="text-theme mb-0">{!! nl2br(e($tr->about_text)) !!}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-5 order-1 order-lg-1">
                        @if($missionImage)
                        <div class="img-box3">
                            <div class="img1">
                                <img src="{{ $missionImage }}" alt="Mission">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- VALUES + CUSTOM blocks: alternate left/right for each --}}
                @if($moreBlocks->count())
                    @foreach($moreBlocks as $i => $block)
                        @php
                            $bTr = $block->t();
                            $bImg = ($block->icon_set==='image' && $block->image) ? asset('storage/'.$block->image) : null;
                            $imageLeft = $i % 2 === 1; // alternate: even -> text left, odd -> image left
                        @endphp

                        <div class="row gy-40 justify-content-between align-items-center mb-60 {{ $imageLeft ? 'flex-row-reverse flex-lg-row' : '' }}">
                            {{-- text side --}}
                            <div class="col-lg-6 {{ $imageLeft ? 'order-2 order-lg-2' : '' }}">
                                <div class="title-area mb-0">
                                    <h3 class="sec-title text-theme mb-2">{{ $bTr?->title ?? ucfirst($block->type ?? 'block') }}</h3>

                                    @if($block->type === 'custom' && count($checkItems))
                                        <div class="about-wrap2 style-theme mt-3">
                                            <div class="checklist style4">
                                                <ul>
                                                    @foreach($checkItems as $item)
                                                        <li>
                                                            <img src="{{ asset('assets/img/icon/checkmark.svg') }}" alt="img">
                                                            {{ $item }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @elseif($bTr?->body)
                                        <p class="text-theme mb-0">{!! nl2br(e($bTr->body)) !!}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- image side --}}
                            <div class="col-lg-5 {{ $imageLeft ? 'order-1 order-lg-1' : '' }}">
                                @if($bImg)
                                <div class="img-box3">
                                    <div class="img1">
                                        <img src="{{ $bImg }}" alt="{{ $bTr?->title ?? '' }}">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>

    <!--==============================
Why choose Us Area
==============================-->
@if($whyUsItems->count())
    <div class="why-sec-1 overflow-hidden space bg-deep-navy">
        <div class="container">
            <div class="row justify-content-lg-between align-items-center">
                <div class="col-lg-6">
                    <div class="title-area">
                        <h2 class="sec-title text-white">{{ $whyUsSetting->t()?->title }}</h2>
                        <p class="text-light">{{ $whyUsSetting->t()?->description }}</p>
                    </div>
                </div>
            </div>

            <div class="swiper th-slider"
                 data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":1},"768":{"slidesPerView":2},"992":{"slidesPerView":2},"1200":{"slidesPerView":3}}}'>
                <div class="swiper-wrapper">
                    @foreach($whyUsItems as $item)
                        <div class="swiper-slide">
                            <div class="service-card style4">
                                <div class="service-card-icon">
                                    <img src="{{ asset('storage/'.$item->icon) }}" alt="Icon">
                                </div>
                                <h3 class="box-title text-white">{{ $item->t()?->title }}</h3>
                                <p class="box-text">{{ $item->t()?->description }}</p>
                                <div class="service-img img-shine">
                                    <img src="{{ asset('storage/'.$item->image) }}" alt="img">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<!--==============================
 Client Slider Area
==============================-->
@if($clients->count())
    <div class="client-area-1 bg-luxury" data-bg-src="{{ asset('assets/img/bg/clients_hero_bg_4_1.jpg') }}">
        <div class="container">
            <div class="slider-area client-slider3">
                <div class="swiper th-slider has-shadow"
                     id="clientSlider1"
                     data-slider-options='{"breakpoints":{"0":{"slidesPerView":2},"576":{"slidesPerView":"3"},"768":{"slidesPerView":"4"},"992":{"slidesPerView":"5"},"1200":{"slidesPerView":"6"}}}'>
                    <div class="swiper-wrapper">
                        @foreach($clients as $client)
                            <div class="swiper-slide">
                                @if($client->link)
                                    <a href="{{ $client->link }}" class="client-card" target="_blank">
                                        <img src="{{ asset('storage/'.$client->image) }}" alt="Client Logo">
                                    </a>
                                @else
                                    <span class="client-card">
                                        <img src="{{ asset('storage/'.$client->image) }}" alt="Client Logo">
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!--==============================
Team Area
==============================-->
@if($team->count())
<section class="space bg-ivory">
    <div class="sec-bg-shape2-3 jump shape-mockup d-xxl-block d-none text-white" data-bottom="5%" data-right="8%">
        <img src="{{ asset('assets/img/shape/section_shape_2_3.jpg') }}" alt="shape">
    </div>
    <div class="container">
        <div class="row justify-content-lg-between align-items-center">
            <div class="col-lg-6">
                <div class="title-area">
                    <h2 class="sec-title text-dark">{{ __('Meet Our Real Estate Experts') }}</h2>
                    <p class="text-dark">
                        {{ __('Behind every successful project is a passionate team of professionals — dedicated to building modern properties, delivering custom solutions, and turning visions into reality.') }}
                    </p>
                </div>

            </div>
            <div class="col-auto">
                <div class="sec-btn">
                    {{-- Change the link below if you have a dedicated /team page --}}
                    <a href="{{ url('/'.app()->getLocale().'/team') }}" class="th-btn style4 th-btn-icon">{{ __('View All Team') }}</a>
                </div>
            </div>
        </div>

        <div class="slider-area team-slider3">
            <div class="swiper th-slider" id="teamSlider3"
                 data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":1},"768":{"slidesPerView":2},"992":{"slidesPerView":3},"1400":{"slidesPerView":3}}}'>
                <div class="swiper-wrapper">
                    @foreach($team as $member)
                        <div class="swiper-slide">
                            <div class="th-team team-card style3">
                                <div class="img-wrap">
                                    <div class="team-img">
                                        <img src="{{ asset('storage/'.$member->image) }}" alt="{{ $member->t()?->name }}">
                                    </div>
                                    @php $social = ['facebook','twitter','linkedin','instagram']; @endphp
                                    @if ($social)
                                        <div class="th-social-wrap">
                                            <div class="th-social">
                                                @foreach($social as $s)
                                                    @php $url = $member->$s; @endphp
                                                    @if($url)
                                                        <a target="_blank" href="{{ $url }}"><i class="fab fa-{{ $s }}"></i></a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="team-card-content">
                                    <div class="media-left">
                                        <h3 class="box-title">{{ $member->t()?->name }}</h3>
                                        <span class="team-desig">{{ $member->t()?->position }}</span>
                                    </div>
                                    @if($member->phone)
                                        <a class="icon-btn" href="tel:{{ $member->phone }}">
                                            <img src="{{ asset('assets/img/icon/phone.svg') }}" alt="phone">
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button data-slider-prev="#teamSlider3" class="slider-arrow style6 slider-prev"><img src="{{ asset('assets/img/icon/arrow-left.svg') }}" alt="icon"></button>
            <button data-slider-next="#teamSlider3" class="slider-arrow style6 slider-next"><img src="{{ asset('assets/img/icon/arrow-right.svg') }}" alt="icon"></button>
        </div>
    </div>
</section>
@endif


@endsection
