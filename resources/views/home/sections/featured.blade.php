{{-- Featured Listing Section — Premium Masonry Grid --}}
<section class="space bg-ivory signature-featured overflow-hidden" id="property-sec">
    {{-- Heading --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="title-area text-center">
                    <span class="sub-title text-dark">{{ __('Featured Listing') }}</span>
                    <h2 class="sec-title mb-2 text-dark">{{ __('Discover the Most Recent Properties') }}</h2>
                    <p class="sec-text text-dark opacity-90">
                        {{ __('Explore stunning properties that capture the essence of luxury and sophistication.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Masonry Grid --}}
    <div class="container">
        @if ($featuredProjects->count())
            <div class="masonry-grid">
                @foreach ($featuredProjects as $fp)
                    @php
                        $tr   = $fp->t();
                        $url  = route('projects.show', ['slug' => $fp->slug]);
                        $cover = optional($fp->galleries->first())->image ?? $fp->image;
                        $img  = $cover ? media_url($cover) : asset('assets/img/placeholder/16x9.jpg');
                    @endphp

                    <article class="project-card masonry-item shadow-sm border-0 rounded-4 overflow-hidden">
                        {{-- Image --}}
                        <div class="project-media position-relative">
                            <img src="{{ $img }}" class="w-100" alt="{{ $tr?->title ?? 'Property' }}">
                            <div class="overlay-gradient"></div>
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow">
                                {{ __('Featured') }}
                            </span>
                        </div>

                        {{-- Details --}}
                        <div class="project-body p-4 bg-white">
                            <h3 class="h5 fw-bold mb-2">
                                <a href="{{ $url }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $tr?->title }}
                                </a>
                            </h3>

                            @if (!is_null($fp->price_starts_from))
                                <div class="text-success fw-semibold mb-2">
                                    {{ __('Starts from') }} ${{ number_format($fp->price_starts_from) }}/m²
                                </div>
                            @endif

                            @if ($fp->location)
                                <div class="text-muted mb-3">
                                    <i class="fas fa-map-marker-alt text-secondary me-1"></i> {{ $fp->location }}
                                </div>
                            @endif

                            {{-- Meta --}}
                            <ul class="list-inline text-muted small mb-3">
                                @if (!is_null($fp->beds))
                                    <li class="list-inline-item me-3">
                                        <i class="fas fa-bed me-1 text-primary"></i>{{ $fp->beds }} {{ __('Beds') }}
                                    </li>
                                @endif
                                @if (!is_null($fp->baths))
                                    <li class="list-inline-item me-3">
                                        <i class="fas fa-bath me-1 text-info"></i>{{ $fp->baths }} {{ __('Baths') }}
                                    </li>
                                @endif
                                @if (!is_null($fp->area))
                                    <li class="list-inline-item">
                                        <i class="fas fa-ruler-combined me-1 text-warning"></i>{{ number_format($fp->area) }} {{ __('m²') }}
                                    </li>
                                @endif
                            </ul>

                            {{-- Short Description --}}
                            @if ($tr?->short_desc)
                                <p class="text-muted small mb-3">
                                    {{ Str::limit($tr->short_desc, 120) }}
                                </p>
                            @endif

                            {{-- CTA --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ $url }}" class="btn btn-outline-primary btn-sm rounded-pill px-4" style="color:aliceblue !important;">
                                    {{ __('View Details') }}
                                </a>
                                <span class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i>{{ $fp->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="py-4 text-center text-dark opacity-75">{{ __('No featured projects found.') }}</div>
        @endif
    </div>
</section>
