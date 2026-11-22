
<div class="hero-1" id="hero">
  <div class="swiper th-slider hero-slider1"
       id="heroSlide1"
       data-slider-options='{
         "effect":"fade",
         "autoHeight":true,
         "loop":true,
         "speed":900,
         "autoplay":{"delay":4500,"disableOnInteraction":false},
         "pagination":{"el":".slider-pagination","clickable":true},
         "navigation":{"nextEl":".hero1-next","prevEl":".hero1-prev"}
       }'>

    <div class="swiper-wrapper">
      @foreach ($sliders as $s)
        @php
          $t = $s->translation();
          $headline = $t?->headline;
          $sub      = $t?->subheadline;
          $ctaText  = $t?->button_text;
          $ctaUrl   = $t?->button_url;
          $desktop  = $s->image ? asset('storage/'.$s->image) : null;
          $mobile   = asset('storage/'.($s->image_mobile ?: $s->image));
        @endphp

        <div class="swiper-slide">
          <div class="hero-inner" data-mask-src="{{ asset('assets/img/hero/hero_1_bg_mask.png') }}">
            {{-- Background (lazy; auto-switches to mobile) --}}
            <div class="th-hero-bg lazy-hero"
                 data-bg-src="{{ $desktop }}"
                 data-bg-src-mobile="{{ $mobile }}"></div>

            {{-- Big watermark text (optional) --}}
            <div class="hero-big-text">REST</div>

            {{-- Gradient overlay for readability --}}
            <div class="hero-glow"></div>

            <div class="container">
              <div class="row align-items-center">
                <div class="col-lg-9">
                  <div class="hero-style1">
                    @if($headline)
                      <h1 class="hero-title text-white">
                        <span class="title1" data-ani="slideindown" data-ani-delay="0.25s">
                          {{ $headline }}
                        </span>
                      </h1>
                    @endif

                    @if($sub)
                      <p class="hero-text text-white" data-ani="slideinup" data-ani-delay="0.45s">
                        {{ $sub }}
                      </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2" data-ani="slideinup" data-ani-delay="0.6s">
                      @if($ctaText && $ctaUrl)
                        <a href="{{ $ctaUrl }}" class="th-btn style4 th-btn-icon hero-visit-button">
                          {{ $ctaText }}
                        </a>
                      @endif

                    </div>
                  </div>
                </div>

                <div class="col-lg-3">
                  @if($s->video_url)
                    <div class="hero-video-wrap text-center" data-ani="slideinright" data-ani-delay="0.5s">
                      <a href="{{ $s->video_url }}" class="play-btn style2 popup-video">
                        <i class="fa-sharp fa-solid fa-play"></i>
                      </a>
                    </div>
                  @endif
                </div>
              </div>
            </div>

          </div>
        </div>
      @endforeach
    </div>

    <div class="slider-pagination"></div>

    {{-- Premium nav arrows --}}
    <button type="button" class="hero1-prev hero-nav" aria-label="{{ __('Previous slide') }}"><i class="fal fa-long-arrow-left"></i></button>
    <button type="button" class="hero1-next hero-nav" aria-label="{{ __('Next slide') }}"><i class="fal fa-long-arrow-right"></i></button>
  </div>

  <div class="hero-social-link">
    <div class="social-wrap">
      @if($globalContactSetting?->facebook)<a href="{{ $globalContactSetting->facebook }}" target="_blank">FACEBOOK</a>@endif
                  @if($globalContactSetting?->twitter)<a href="{{ $globalContactSetting->twitter }}"  target="_blank">TWITTER</a>@endif
                  @if($globalContactSetting?->linkedin)<a href="{{ $globalContactSetting->linkedin }}" target="_blank">LINKEDIN</a>@endif
                  @if($globalContactSetting?->youtube)<a href="{{ $globalContactSetting->youtube }}"  target="_blank">YOUTUBE</a>@endif
                  @if($globalContactSetting?->instagram)<a href="{{ $globalContactSetting->instagram }}"  target="_blank">INSTAGRAM</a>@endif
    </div>
  </div>

  <div class="scroll-down">
    <a href="#about-sec" class="hero-scroll-wrap"><i class="fal fa-long-arrow-left"></i>Scroll</a>
  </div>
</div>

@push('styles')
<style>
  #hero .hero-slider1 .hero-inner {
    min-height: clamp(620px, 75svh, 820px);
    display: flex;
    align-items: center;
    overflow: hidden;
  }
  #hero .hero-slider1 .hero-inner .container { position: relative; z-index: 2; }
  #hero .hero-slider1 .hero-big-text { display: none; }
  #hero .hero-slider1 .hero-glow {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(90deg, rgba(7, 19, 13, .82) 0%, rgba(7, 19, 13, .64) 46%, rgba(7, 19, 13, .12) 100%);
    pointer-events: none;
  }
  #hero .hero-slider1 .hero-style1 {
    max-width: 760px;
    padding: 110px 0 132px;
    text-align: start;
  }
  #hero .hero-slider1 .hero-title {
    max-width: 760px;
    margin: 0 0 22px;
    font-size: clamp(2.8rem, 4.2vw, 4.4rem);
    line-height: 1.13;
    letter-spacing: -.035em;
    text-wrap: balance;
    text-shadow: 0 2px 16px rgba(0, 0, 0, .35);
  }
  #hero .hero-slider1 .hero-text {
    max-width: 570px;
    margin: 0 0 30px;
    font-size: clamp(1rem, 1.3vw, 1.15rem);
    line-height: 1.65;
    text-shadow: 0 2px 8px rgba(0, 0, 0, .4);
  }
  #hero .hero-slider1 .hero-visit-button {
    padding: 17px 27px;
    border-radius: 6px;
    background: #075b25;
    color: #fff;
  }
  #hero .hero-slider1 .hero-visit-button:hover { background: #0b7130; }
  #hero .hero-slider1 .hero-nav {
    position: absolute;
    z-index: 5;
    top: auto;
    bottom: 28px;
    transform: none;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, .65);
    border-radius: 50%;
    background: rgba(8, 19, 13, .45);
    color: #fff;
    transition: background .2s ease;
  }
  #hero .hero-slider1 .hero1-prev { left: 32px; }
  #hero .hero-slider1 .hero1-next { left: 90px; right: auto; }
  #hero .hero-slider1 .hero-nav:hover { background: #075b25; }
  #hero .hero-slider1 .hero-nav:focus-visible,
  #hero .hero-slider1 .hero-visit-button:focus-visible { outline: 2px solid #fff; outline-offset: 3px; }
  #hero .hero-slider1 .slider-pagination {
    position: absolute;
    z-index: 5;
    bottom: 43px;
    right: 36px;
    left: auto;
    width: auto;
    padding: 0;
    text-align: end;
  }
  #hero .hero-slider1 .slider-pagination .swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    margin: 0 6px;
    opacity: .85;
    background: #fff;
  }
  #hero .hero-slider1 .slider-pagination .swiper-pagination-bullet-active {
    background: #fff;
    opacity: 1;
    box-shadow: 0 0 0 4px rgba(255, 255, 255, .3);
  }
  #hero .lazy-hero { background-color: #183020; }

  @media (max-width: 991px) {
    #hero .hero-slider1 .hero-inner { min-height: 650px; }
    #hero .hero-slider1 .hero-style1 {
      padding: 115px 28px 110px;
      max-width: 640px;
      text-align: start;
    }
    #hero .hero-slider1 .hero-glow {
      background: linear-gradient(90deg, rgba(7, 19, 13, .8), rgba(7, 19, 13, .28));
    }
    #hero .hero-slider1 .hero-video-wrap { padding: 0 0 100px; text-align: start !important; }
  }

  @media (max-width: 575px) {
    #hero .hero-slider1 .hero-inner { min-height: clamp(590px, 78svh, 730px); }
    #hero .hero-slider1 .hero-style1 { padding: 96px 10px 105px; }
    #hero .hero-slider1 .hero-title {
      font-size: clamp(2rem, 8.1vw, 2.85rem);
      line-height: 1.15;
      letter-spacing: -.025em;
    }
    #hero .hero-slider1 .hero-text { font-size: .98rem; line-height: 1.55; margin-bottom: 24px; }
    #hero .hero-slider1 .hero-glow {
      background: linear-gradient(0deg, rgba(7, 19, 13, .84), rgba(7, 19, 13, .56) 65%, rgba(7, 19, 13, .23));
    }
    #hero .hero-slider1 .hero-nav { bottom: 20px; width: 40px; height: 40px; }
    #hero .hero-slider1 .hero1-prev { left: 18px; }
    #hero .hero-slider1 .hero1-next { left: 68px; }
    #hero .hero-slider1 .slider-pagination { bottom: 30px; right: 16px; }
  }

  @media (prefers-reduced-motion: reduce) {
    #hero .hero-slider1 .hero-inner .th-hero-bg { animation: none; }
  }
</style>
@endpush

@push('scripts')
<script>
  // Swap to mobile/desktop background images and lazy-load when visible
  (function(){
    const mq = window.matchMedia('(max-width: 768px)');
    function applyBg(el){
      const mobile = el.getAttribute('data-bg-src-mobile');
      const desktop = el.getAttribute('data-bg-src');
      const src = (mq.matches && mobile) ? mobile : desktop;
      if (src && !el.dataset.loaded) {
        const img = new Image();
        img.onload = () => { el.style.backgroundImage = `url('${src}')`; el.dataset.loaded = '1'; };
        img.src = src;
      } else if (src && el.dataset.loaded) {
        el.style.backgroundImage = `url('${src}')`;
      }
    }
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{
        if(e.isIntersecting){ applyBg(e.target); }
      });
    }, {rootMargin:'200px'});
    document.querySelectorAll('.lazy-hero').forEach(el=>{
      io.observe(el);
      applyBg(el); // initial pass (above the fold)
    });
    mq.addEventListener?.('change', ()=> {
      document.querySelectorAll('.lazy-hero').forEach(applyBg);
    });
  })();
</script>
@endpush
