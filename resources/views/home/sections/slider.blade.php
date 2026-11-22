
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
                <div class="col-lg-8">
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
                        <a href="{{ $ctaUrl }}" class="th-btn style4 th-btn-icon">
                          {{ $ctaText }}
                        </a>
                      @endif

                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
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
    <button type="button" class="hero1-prev hero-nav"><i class="fal fa-long-arrow-left"></i></button>
    <button type="button" class="hero1-next hero-nav"><i class="fal fa-long-arrow-right"></i></button>
  </div>

  <div class="hero-social-link">
    <div class="social-wrap">
      @if($globalContactSetting?->facebook)<a href="{{ $globalContactSetting->facebook }}" target="_blank">FACEBOOK</a>@endif
                  @if($globalContactSetting?->twitter)<a href="{{ $globalContactSetting->twitter }}"  target="_blank">TWITTER</a>@endif
                  @if($globalContactSetting?->linkedin)<a href="{{ $globalContactSetting->linkedin }}" target="_blank">LINKEDIN</a>@endif
                  @if($globalContactSetting?->youtube)<a href="{{ $globalContactSetting->youtube }}"  target="_blank">YOUTUTBE</a>@endif
                  @if($globalContactSetting?->instagram)<a href="{{ $globalContactSetting->instagram }}"  target="_blank">INSTAGRAM</a>@endif
    </div>
  </div>

  <div class="scroll-down">
    <a href="#about-sec" class="hero-scroll-wrap"><i class="fal fa-long-arrow-left"></i>Scroll</a>
  </div>
</div>

@push('styles')
<style>
  /* Premium overlay & nav */
  .hero-glow{
    position:absolute; inset:0;
    background: radial-gradient(1200px 600px at 15% 85%, rgba(120,75,155,.25), transparent 60%),
                linear-gradient(120deg, rgba(0,0,0,.55), rgba(0,0,0,.15) 55%, rgba(0,0,0,0));
    pointer-events:none;
  }
  .hero-nav{
    position:absolute; z-index:5; top:50%; transform:translateY(-50%);
    width:46px; height:46px; border-radius:12px; border:1px solid rgba(255,255,255,.4);
    background: rgba(0,0,0,.25); color:#fff; display:flex; align-items:center; justify-content:center;
    backdrop-filter: blur(6px); transition: all .18s ease;
  }
  .hero1-prev{ left:18px; } .hero1-next{ right:18px; }
  .hero-nav:hover{ background: rgba(0,0,0,.4); border-color: rgba(255,255,255,.65); }

  /* Premium CTA buttons */
  .btn-premium-cta{
    background-image: linear-gradient(135deg,#7a39c0,#5d2aa2) !important;
    border:0 !important; box-shadow: 0 14px 28px rgba(120,75,155,.28);
  }
  .btn-premium-cta:hover{ transform: translateY(-2px); box-shadow: 0 18px 36px rgba(120,75,155,.36); }
  .btn-premium-ghost{
    border-radius: 999px; padding:.65rem 1.1rem; font-weight:700; color:#fff !important;
    border:1px solid rgba(255,255,255,.45); background: rgba(255,255,255,.08); backdrop-filter: blur(6px);
  }

  /* Pagination tweak */
  .slider-pagination .swiper-pagination-bullet{
    width:10px; height:10px; opacity:1; background: rgba(255,255,255,.6);
  }
  .slider-pagination .swiper-pagination-bullet-active{
    background:#fff; box-shadow: 0 0 0 5px rgba(255,255,255,.25);
  }

  /* Lazy BG placeholder */
  .lazy-hero{ background-color:#e9e3db; }
  .hero-title,
.hero-text {
  text-shadow: 0 3px 8px rgba(0,0,0,.55), 0 1px 2px rgba(0,0,0,.35);
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
