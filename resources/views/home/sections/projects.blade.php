<section class="space bg-luxury luxury-projects overflow-hidden">
    {{-- Background shapes (kept subtle; hidden on smaller screens) --}}
    <div class="sec-bg-shape2-1 spin shape-mockup d-xl-block d-none" data-top="6%" data-left="4%">
        <img src="{{ asset('assets/img/shape/section_shape_2_1.jpg') }}" alt="img">
    </div>
    <div class="sec-bg-shape2-2 wave-anim shape-mockup d-xl-block d-none" data-bg-src="{{ asset('assets/img/shape/section_shape_2_2.jpg') }}" data-top="12%" data-right="4%"></div>
    <div class="sec-bg-shape2-3 jump shape-mockup d-xl-block d-none" data-bottom="0" data-left="3%">
        <img src="{{ asset('assets/img/shape/section_shape_2_3.jpg') }}" alt="img">
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-xxl-6 col-lg-8">
                <div class="title-area text-center">
                    <span class="sub-title">{{ __('Best Project') }}</span>
                    <h2 class="sec-title text-theme mb-2">{{ __('Ongoing Projects') }}</h2>
                    <p class="text-theme">
                        {{ __('Explore our active developments and latest builds in progress.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Full-bleed slider --}}
    <div class="container-fluid gx-0">
        <div class="slider-area project-slider4 slider-drag-wrap px-0">
            <div class="swiper th-slider"
                data-slider-options='{
                    "loop": true,
                    "speed": 700,
                    "autoplay": {"delay": 4000, "disableOnInteraction": false},
                    "grabCursor": true,
                    "slideToClickedSlide": true,
                    "centeredSlides": true,
                    "spaceBetween": 32,
                    "breakpoints": {
                        "0":     {"slidesPerView": 1},
                        "576":   {"slidesPerView": 1},
                        "992":   {"slidesPerView": 1.15, "spaceBetween": 32},
                        "1200":  {"slidesPerView": 1.35, "spaceBetween": 40},
                        "1500":  {"slidesPerView": 1.5,  "spaceBetween": 48}
                    }
                 }'>
                <div class="swiper-wrapper">
                    @forelse($ongoingProjects as $p)
                        @php $tr = $p->t(); @endphp
                        <div class="swiper-slide">
                            <div class="portfolio-card style4 luxury-card">
                                <div class="portfolio-img luxury-img">
                                    @if ($p->image)
                                        <img src="{{ media_url($p->image) }}" alt="{{ $tr?->title }}">
                                    @else
                                        <img src="{{ asset('assets/img/placeholder/16x9.jpg') }}" alt="{{ $tr?->title ?? 'Project' }}">
                                    @endif

                                    {{-- Project Status Badge --}}
                                    @if ($p->status)
                                        <span class="badge bg-theme position-absolute start-0 top-0 m-3">
                                            {{ __($p->status) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="portfolio-content luxury-content">
                                    {{-- Project Title --}}
                                    <h3 class="portfolio-title mb-1">
                                        <a href="{{ route('projects.show', ['locale' => app()->getLocale(), 'slug' => $p->slug]) }}">
                                            {{ $tr?->title ?? __('Project') }}
                                        </a>
                                    </h3>

                                    {{-- Location --}}
                                    @if ($p->location)
                                        <p class="portfolio-location mb-10">
                                            <i class="fa-solid fa-location-dot text-theme me-1"></i> {{ $p->location }}
                                        </p>
                                    @endif

                                    {{-- Meta Info --}}
                                    <div class="property-card-meta mb-2">
                                        @if (!is_null($p->beds))
                                            <span><img src="{{ asset('assets/img/icon/property-icon1-1.svg') }}" alt="bed">{{ __('Bed') }} {{ $p->beds }}</span>
                                        @endif
                                        @if (!is_null($p->baths))
                                            <span><img src="{{ asset('assets/img/icon/property-icon1-2.svg') }}" alt="bath">{{ __('Bath') }} {{ $p->baths }}</span>
                                        @endif
                                        @if (!is_null($p->area))
                                            <span><img src="{{ asset('assets/img/icon/property-icon1-3.svg') }}" alt="area">{{ number_format($p->area) }}
                                                {{ __('m²') }}</span>
                                        @endif
                                    </div>

                                    {{-- Price (if available) --}}
                                    @if ($p->price)
                                        <p class="portfolio-price fw-bold text-theme mb-2">
                                            {{ __('Starting From') }}: <span class="text-dark">${{ number_format($p->price) }}/m²</span>
                                        </p>
                                    @endif

                                    {{-- Short Description --}}
                                    @if ($tr?->description)
                                        <p class="portfolio-text mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($tr->description), 120) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide py-5 text-center">
                            <p>{{ __('No ongoing projects available at the moment.') }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="slider-pagination style3 d-sm-block d-none"></div>
            </div>
        </div>

        <div class="btn-wrap justify-content-center mt-55">
            <a href="{{ url('projects') }}" class="th-btn style4 th-btn-icon">{{ __('Explore All Projects') }}</a>
        </div>
    </div>
</section>
