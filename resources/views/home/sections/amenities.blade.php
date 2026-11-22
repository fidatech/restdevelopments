{{-- === Amenities section === --}}
@if(($amenities ?? collect())->count())
<section class="space overflow-hidden bg-luxury">
  <div class="container">
      <div class="row justify-content-center align-items-center">
          <div class="col-xl-6 col-lg-8">
              <div class="title-area text-center">
                  <span class="shadow-title style2">{{ __('Facilities') }}</span>
                  <h2 class="sec-title">{{ __('Rest Amenities') }}</h2>
                  <p class="sec-text text-title">{{ __('We are a real estate firm with over 10 years of expertise...') }}</p>
              </div>
          </div>
      </div>
  </div>

  <div class="container th-container2">
      <div class="swiper th-slider aminities-slider"
          id="aminitiesSlider1"
          data-slider-options='{"paginationType":"progressbar","breakpoints":{"0":{"slidesPerView":1},"375":{"slidesPerView":2},"768":{"slidesPerView":3},"992":{"slidesPerView":4},"1200":{"slidesPerView":6}}}'>

          <div class="swiper-wrapper">
            @foreach($amenities as $a)
              <div class="swiper-slide">
                <div class="aminities-card" data-mask-src="{{ asset('assets/img/theme-img/aminities-shape1.png') }}">
                    <div class="aminities-card-img">
                      <img src="{{ asset('storage/'.$a->image) }}" alt="">
                    </div>
                    <div class="aminities-content">
                      <div class="aminities-card-icon">
                        <img src="{{ asset('storage/'.$a->icon) }}" alt="">
                      </div>
                      <h3 class="box-title">{{ $a->t()?->title }}</h3>
                    </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="slider-pagination"></div>
          <button data-slider-prev="#aminitiesSlider1" class="slider-arrow slider-prev"><img src="{{ asset('assets/img/icon/arrow-left.svg') }}" alt="icon"></button>
          <button data-slider-next="#aminitiesSlider1" class="slider-arrow slider-next"><img src="{{ asset('assets/img/icon/arrow-right.svg') }}" alt="icon"></button>
      </div>
  </div>
</section>
@endif
