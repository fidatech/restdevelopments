@extends('layouts.base')
@section('title', __('Home'))

@section('content')
    <!--==============================
                            Hero Area
                            ==============================-->
    @include('home.sections.slider')
    <!--======== / Hero Section ========-->
    <!--==============================
                            Counter Area
                            ==============================-->
    @include('home.sections.counters')
    <!--==============================
                            About Area
                            ==============================-->
    @include('home.sections.about', ['about' => $about])

    <!--==============================
                            Project Area
                            ==============================-->
    @include('home.sections.projects')
    <!--==============================
                            Featured Area
                            ==============================-->
    @include('home.sections.featured')
    <!--==============================
                            Amenities Area
                            ==============================-->
    @include('home.sections.amenities')
    <!--==============================
                            Testimonial Area
                            ==============================-->
    <section class="space bg-ivory position-relative overflow-hidden">
        <!-- Luxury Background Shapes -->
        <div class="sec-bg-shape2-1 spin shape-mockup d-xxl-block d-none" data-bottom="8%" data-left="8%">
            <img src="assets/img/shape/section_shape_2_1.jpg" alt="shape">
        </div>
        <div class="sec-bg-shape2-2 d-none d-xl-block" style="position:absolute; top:-50px; right:-50px; opacity:0.08;">
            <img src="assets/img/shape/section_shape_2_3.jpg" alt="shape" style="max-width:250px;">
        </div>

        <div class="container">
            <div class="row justify-content-lg-between justify-content-center align-items-center mb-5">
                <div class="col-xxl-6 col-lg-7 text-lg-start text-center">
                    <div class="title-area">
                        <h2 class="sec-title fw-bold" style="color:#2c2c2c; font-size:2.5rem;">
                            <span style="color:#000;">What Our Customers Say</span>
                        </h2>
                        <p class="sec-text text-muted mt-3">
                            Real voices from <strong>Afghanistan</strong> and <strong>Uzbekistan</strong> who trusted Rest Developments with their homes and investments.
                        </p>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="sec-btn d-flex gap-2">
                        <button data-slider-prev="#testiSlider2" class="slider-arrow btn btn-light rounded-circle border shadow-sm">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <button data-slider-next="#testiSlider2" class="slider-arrow btn btn-light rounded-circle border shadow-sm">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimonials -->
        <div class="container-fluid px-4">
            <div class="testi-wrap2">
                <div class="swiper th-slider testi-slider2" id="testiSlider2"
                    data-slider-options='{"spaceBetween":48,"breakpoints":{"0":{"slidesPerView":1},"1200":{"slidesPerView":2}}}'>
                    <div class="swiper-wrapper">

                        <!-- Testimonial Card -->
                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “Rest Developments exceeded my expectations. From design to delivery, everything felt premium yet practical.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Ahmad">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Ahmad Zahir</h5>
                                        <small class="text-muted">Kabul, Afghanistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “The professionalism of Rest Developments impressed me. They delivered a safe and welcoming environment for my family.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/women/45.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Nilufar">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Nilufar Karimova</h5>
                                        <small class="text-muted">Termiz, Uzbekistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “As an investor, I found Rest Developments to be trustworthy and transparent. Their projects show true quality.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/men/18.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Shahbaz">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Shahbaz Rahimi</h5>
                                        <small class="text-muted">Kabul, Afghanistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “Luxury with everyday comfort — that’s how I’d describe my apartment. Rest Developments balanced both perfectly.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/men/77.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Dilshod">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Dilshod Bekmurodov</h5>
                                        <small class="text-muted">Termiz, Uzbekistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 5 -->
                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “From booking to handover, the process was smooth. I feel proud owning a home with Rest Developments.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/women/21.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow"
                                        width="60" height="60" alt="Arezoo">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Arezoo Habibi</h5>
                                        <small class="text-muted">Kabul, Afghanistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 6 -->
                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “Their transparency gave me confidence. I always knew where my investment was going.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/men/64.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Samiullah">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Abdullah Ahmadzai</h5>
                                        <small class="text-muted">Kabul, Afghanistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 7 -->
                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “I appreciated the after-sales support. They don’t just build properties; they build trust.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/women/47.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow"
                                        width="60" height="60" alt="Malika">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Malika Usmonova</h5>
                                        <small class="text-muted">Termiz, Uzbekistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimonial 8 -->
                        <div class="swiper-slide">
                            <div class="testi-card rounded-4 p-4 shadow-lg" style="background:rgba(255,255,255,0.85); backdrop-filter:blur(10px);">
                                <div class="testi-grid_review text-warning fs-5 mb-3">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <p class="testi-card_text fst-italic text-dark">
                                    “Rest Developments gave me both luxury and peace of mind — a rare combination.”
                                </p>
                                <div class="testi-card_profile d-flex align-items-center mt-4">
                                    <img src="https://randomuser.me/api/portraits/men/14.jpg" class="rounded-circle border-3 border-warning-subtle me-3 border shadow" width="60"
                                        height="60" alt="Abdulloh">
                                    <div>
                                        <h5 class="fw-semibold mb-0">Abdulloh Qodirov</h5>
                                        <small class="text-muted">Termiz, Uzbekistan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- swiper-wrapper -->
                </div>
            </div>
        </div>
    </section>

    <!--==============================
                            Blog Area
                            ==============================-->
    @if ($latestBlogs->count() > 0)

        <section class="space bg-deep-navy" id="blog-sec">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-5 col-lg-7">
                        <div class="title-area">
                            <span class="shadow-title">Blog</span>
                            <h2 class="sec-title text-white">Latest Blog & News</h2>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="sec-btn">
                            <a href="{{ route('blog.index') }}" class="th-btn style4 th-btn-icon">Browse All Blog</a>
                        </div>
                    </div>
                </div>
                <div class="slider-area">
                    <div class="swiper th-slider has-shadow slider-drag-wrap" id="blogSlider3"
                        data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"1"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"2"},"1200":{"slidesPerView":"3"},"1500":{"slidesPerView":"3"}},"autoHeight":true}'>
                        <div class="swiper-wrapper">
                            @foreach ($latestBlogs as $blog)
                                <div class="swiper-slide">
                                    <div class="blog-card style2">
                                        <div class="blog-img">
                                            <a href="{{ route('blog.show', $blog->slug) }}">
                                                <img src="{{ asset('storage/' . $blog->thumb_small) }}" alt="blog image">
                                            </a>
                                        </div>
                                        <div class="blog-content">
                                            <div class="blog-meta">
                                                <span>{{ $blog->created_at->format('F d, Y') }}</span>
                                            </div>
                                            <h3 class="box-title">
                                                <a href="{{ route('blog.show', $blog->slug) }}">
                                                    {{ $blog->t()?->title }}
                                                </a>
                                            </h3>
                                            <a href="{{ route('blog.show', $blog->slug) }}" class="th-btn style-border6 th-btn-icon mt-15">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </section>
    @endif

    <!--==============================
                            FAQ Area
                            ==============================-->
    <section class="space bg-deep-navy overflow-hidden">
        <div class="container">
            <div class="title-area mb-5 text-center">
                <span class="sub-title text-white">{{ __('Help & Support') }}</span>
                <h2 class="sec-title text-white">{{ __('Frequently Asked Questions') }}</h2>
                <p class="sec-text text-success">{{ __('Everything you need to know about buying, investing, and living in Rest Developments projects.') }}</p>
            </div>

            <div class="accordion accordion-flush premium-faq" id="faqAccordion">

                {{-- Q1 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
                            <i class="fa-solid fa-house-circle-check text-theme me-2"></i>
                            {{ __('How can I buy an apartment with Rest Developments?') }}
                        </button>
                    </h2>
                    <div id="a1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('You can purchase directly from our sales team at our project offices or through accredited partner agencies.
                                            Once you select your preferred unit, you will pay a small booking deposit which immediately secures your apartment.
                                            After booking, we sign a formal Sales & Purchase Agreement (SPA) that outlines the exact payment schedule, delivery milestones, and all legal protections for the buyer.
                                            Our sales consultants guide you step-by-step — from the initial reservation to handing over the keys — ensuring clarity and full transparency throughout the process.') }}
                        </div>
                    </div>
                </div>

                {{-- Q2 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
                            <i class="fa-solid fa-credit-card text-theme me-2"></i>
                            {{ __('What are the installment / payment conditions?') }}
                        </button>
                    </h2>
                    <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('We offer flexible and transparent payment structures designed to fit different client needs.
                                            The standard plan requires 10% as a down payment, with the balance distributed across 36 to 45 monthly installments — often interest-free during construction.
                                            For investors, we also offer milestone-based plans, where payments are tied to construction progress (foundation, structure, finishing, etc.).
                                            In addition, we create tailored solutions for corporate or bulk buyers.
                                            All conditions are included in your SPA, so there are no hidden charges or surprises later.') }}
                        </div>
                    </div>
                </div>

                {{-- Q3 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
                            <i class="fa-solid fa-handshake text-theme me-2"></i>
                            {{ __('Is financing available through banks or partners?') }}
                        </button>
                    </h2>
                    <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Yes. Rest Developments works with trusted local and international banks to provide mortgage and Islamic financing options.
                                            Depending on your profile, financing can cover up to 70% of the property value with repayment terms of up to 15 years.
                                            We also assist you with the entire financing journey: preparing required documents, coordinating with the bank, and ensuring the loan disbursement process is smooth.
                                            This makes property ownership accessible not just to cash buyers but also to salaried professionals, entrepreneurs, and investors.') }}
                        </div>
                    </div>
                </div>

                {{-- Q4 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a4">
                            <i class="fa-solid fa-building text-theme me-2"></i>
                            {{ __('When will the projects be completed and handed over?') }}
                        </button>
                    </h2>
                    <div id="a4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Every project has a detailed timeline that is clearly communicated before purchase.
                                            For example, Rest Complex is scheduled for handover in Q4 2028.
                                            We keep clients updated with quarterly progress reports, site tours, and photos of construction milestones.
                                            All promised handover dates are included in the SPA, which makes them legally binding.
                                            In the rare case of delays due to external factors, clients are informed transparently and compensation mechanisms are applied as per the contract.
                                            Our reputation is built on delivering what we promise — on time and with guaranteed quality.') }}
                        </div>
                    </div>
                </div>

                {{-- Q5 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a5">
                            <i class="fa-solid fa-couch text-theme me-2"></i>
                            {{ __('Are the apartments furnished or unfurnished?') }}
                        </button>
                    </h2>
                    <div id="a5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('All our apartments come with premium standard finishes — high-quality flooring, branded sanitary ware, modular kitchens, and top-grade electrical fittings.
                                            By default, they are delivered unfurnished so that buyers can personalize interiors to their own taste.
                                            However, we also offer curated furnishing packages (modern, luxury, or classic styles), designed by professional interior designers.
                                            These packages allow you to move in immediately without the hassle of arranging furniture and decor separately.
                                            Custom packages can also be created for overseas investors who want a “ready-to-rent” or “turn-key” apartment.') }}
                        </div>
                    </div>
                </div>

                {{-- Q6 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a6">
                            <i class="fa-solid fa-shield-halved text-theme me-2"></i>
                            {{ __('What security and facilities are provided?') }}
                        </button>
                    </h2>
                    <div id="a6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Every Rest Developments project is built with safety, comfort, and lifestyle in mind.
                                            Security includes 24/7 gated access, CCTV monitoring, trained security personnel, and earthquake-resistant design.
                                            Residents also enjoy facilities such as underground parking, modern elevators, backup power generators, landscaped gardens, gyms, swimming pools, children’s play zones, and community lounges.
                                            After handover, our dedicated facility management team ensures all amenities are maintained to international standards, protecting the long-term value of your investment.') }}
                        </div>
                    </div>
                </div>

                {{-- Q7 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a7">
                            <i class="fa-solid fa-passport text-theme me-2"></i>
                            {{ __('Can foreigners or overseas Afghans/Uzbek buy property with Rest Developments?') }}
                        </button>
                    </h2>
                    <div id="a7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Yes. We actively welcome overseas Afghans, Uzbek expatriates, and international buyers.
                                            Foreign buyers can purchase property through special legal frameworks, and our legal team assists with all required documentation.
                                            We provide certified translations, help with residency permits if required, and ensure all property ownership rights are protected by Afghan and Uzbek property laws.
                                            For overseas clients who cannot be present physically, booking, payments, and contract signing can be done remotely through secure channels.
                                            This makes investing from abroad both safe and simple.') }}
                        </div>
                    </div>
                </div>

                {{-- Q8 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q8">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a8">
                            <i class="fa-solid fa-hand-holding-dollar text-theme me-2"></i>
                            {{ __('What are the expected rental returns or investment benefits?') }}
                        </button>
                    </h2>
                    <div id="a8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Our projects are designed with investors in mind. Rental yields in Kabul, Tashkent, and Termiz typically range between 6%–10% annually, depending on unit size and location.
                                            Rest Developments properties attract premium tenants because of high-quality construction, modern facilities, and prime locations.
                                            We also provide a dedicated property management service that can handle tenant sourcing, rent collection, and maintenance on behalf of investors, ensuring hassle-free returns.
                                            Capital appreciation is also strong — properties have historically increased in value 20–30% by handover.') }}
                        </div>
                    </div>
                </div>

                {{-- Q9 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q9">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a9">
                            <i class="fa-solid fa-file-contract text-theme me-2"></i>
                            {{ __('What happens if I need to resell my apartment before handover?') }}
                        </button>
                    </h2>
                    <div id="a9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('We allow reselling of units even before project handover, subject to certain terms in your contract.
                                            Our legal and sales team will assist with transferring the Sales & Purchase Agreement to the new buyer.
                                            In most cases, reselling is straightforward: the new buyer pays the outstanding installments, while you recover your equity and any appreciation.
                                            This provides investors with liquidity and flexibility, as they are not locked into holding the property until completion.') }}
                        </div>
                    </div>
                </div>

                {{-- Q10 --}}
                <div class="accordion-item mb-3 rounded shadow-sm">
                    <h2 class="accordion-header" id="q10">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a10">
                            <i class="fa-solid fa-headset text-theme me-2"></i>
                            {{ __('What after-sales support does Rest Developments provide?') }}
                        </button>
                    </h2>
                    <div id="a10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ __('Our commitment does not end at handover. We provide ongoing after-sales services including warranty on construction and fittings, maintenance support, and a dedicated customer service hotline.
                                            Residents can also access our online portal to log issues, request maintenance, and track updates.
                                            For investors, we provide periodic reports on property value, rental trends, and investment opportunities.
                                            This ensures that your relationship with Rest Developments continues long after your purchase — keeping your property in top condition and your investment secure.') }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
