@php
    $tr = $about?->t();
@endphp

@if ($about && $about->status)
    <div class="space-top bg-deep-navy overflow-hidden pb-4" id="about-sec">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-6">
                    <div class="title-area">
                        @if ($tr?->shadow_title)
                            <span class="shadow-title">{{ $tr->shadow_title }}</span>
                        @endif
                        <h2 class="sec-title text-white">{{ $tr->title }}</h2>
                        @if ($tr?->subtitle)
                            <p class="sec-text text-white">{{ $tr->subtitle }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-lg-auto">
                    <div class="sec-btn">
                        @if ($about->button_url && $tr?->button_text)
                            <a href="{{ $about->button_url }}" class="th-btn style4 th-btn-icon">{{ $tr->button_text }}</a>
                        @else
                            <a href="{{ route('about') }}" class="th-btn style4 th-btn-icon">{{ __('Learn More') }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="img-box1">
                        <div class="img1 img-shine" data-mask-src="{{ asset('assets/img/shape/about-1-mask.png') }}">
                            @if ($about->image_left)
                                <img src="{{ media_url($about->image_left) }}" style="width: 636px; height: 336px;" alt="About">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img-box1">
                        <div class="img1 img-shine" data-mask-src="{{ asset('assets/img/shape/about-1-mask.png') }}">
                            @if ($about->image_right)
                                <img src="{{ media_url($about->image_right) }}" style="width: 636px; height: 336px;" alt="About">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-60">
                <div class="row gy-40 flex-row-reverse">
                    <div class="col-xl-3 text-xl-end">
                        <div class="about-tag">
                            <div class="about-experience-tag">
                                <span class="circle-title-anime">{{ $tr?->circle_text }}</span>
                            </div>
                            <div class="about-tag-thumb">
                                @if ($about->tag_image)
                                    <img src="{{ media_url($about->tag_image) }}" style="width: 201px; height: 200px; object-fit: cover; border-radius: 12px;"
                                        alt="img">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="about-wrap1">
                            @if ($tr?->about_text)
                                <p class="about-text text-white">{{ $tr->about_text }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif
