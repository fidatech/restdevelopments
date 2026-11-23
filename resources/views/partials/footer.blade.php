<footer class="footer-wrapper footer-layout1 bg-theme">
  <div class="footer-wrap bg-luxury" data-mask-src="{{ asset('assets/img/bg/footer-bg-mask.png') }}">
    <div class="widget-area space">
      <div class="container">
        <div class="row justify-content-between">
          {{-- About / Social --}}
          <div class="col-md-6 col-xl-4">
            <div class="widget footer-widget">
              <div class="th-widget-about">
                <div class="about-logo">
                    <a href="{{ url('/') }}">
                        @if($globalContactSetting && $globalContactSetting->logo_white)
                          <img src="{{ media_url($globalContactSetting->logo_dark) }}" alt="Rest" style="height:100px;">
                        @else
                          <img src="{{ asset('assets/img/rest_logo.png') }}" alt="Rest" style="height:100px;">
                        @endif
                    </a>
                </div>
                <p class="about-text">{{ $globalContactSetting?->t()?->company_intro }}</p>

                <div class="th-social style3">
                  @if($globalContactSetting?->facebook)<a href="{{ $globalContactSetting->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                  @if($globalContactSetting?->twitter)<a href="{{ $globalContactSetting->twitter }}"  target="_blank"><i class="fab fa-twitter"></i></a>@endif
                  @if($globalContactSetting?->linkedin)<a href="{{ $globalContactSetting->linkedin }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>@endif
                  @if($globalContactSetting?->youtube)<a href="{{ $globalContactSetting->youtube }}"  target="_blank"><i class="fab fa-youtube"></i></a>@endif
                  @if($globalContactSetting?->instagram)<a href="{{ $globalContactSetting->instagram }}"  target="_blank"><i class="fab fa-instagram"></i></a>@endif
                </div>
              </div>
            </div>
          </div>

          {{-- Contact --}}
          <div class="col-md-6 col-xl-auto">
            <div class="widget footer-widget">
              <h3 class="widget_title">{{ __('Get In Touch') }}</h3>
              <div class="th-widget-contact">
                <div class="info-box_text">
                  <div class="icon"><img src="{{ asset('assets/img/icon/location-dot.svg') }}" alt="img"></div>
                  <div class="details">{!! nl2br(e($globalContactSetting?->t()?->address)) !!}</div>
                </div>

                @if($globalContactSetting?->phone_1 || $globalContactSetting?->phone_2)
                <div class="info-box_text">
                  <div class="icon"><img src="{{ asset('assets/img/icon/phone.svg') }}" alt="img"></div>
                  <div class="details">
                    @if($globalContactSetting->phone_1)
                        <p><a href="tel:{!! $globalContactSetting->phone_1 !!}">{!! $globalContactSetting->phone_1 !!}</a></p>
                    @endif
                    @if($globalContactSetting->phone_2)
                        <p><a href="tel:{!! $globalContactSetting->phone_2 !!}">{!! $globalContactSetting->phone_2 !!}</a></p>
                    @endif
                </div>

                </div>
                @endif

                @if($globalContactSetting?->email_1 || $globalContactSetting?->email_2)
                <div class="info-box_text">
                  <div class="icon"><img src="{{ asset('assets/img/icon/envelope.svg') }}" alt="img"></div>
                  <div class="details">
                      @if($globalContactSetting->email_1)<p><a href="mailto:{{ $globalContactSetting->email_1 }}">{{ $globalContactSetting->email_1 }}</a></p>@endif
                      @if($globalContactSetting->email_2)<p><a href="mailto:{{ $globalContactSetting->email_2 }}">{{ $globalContactSetting->email_2 }}</a></p>@endif
                  </div>
                </div>
                @endif
              </div>
            </div>
          </div>

          {{-- Useful Links --}}
          <div class="col-md-6 col-xl-auto">
            <div class="widget widget_nav_menu footer-widget">
              <h3 class="widget_title">{{ __('Useful Link') }}</h3>
              <ul class="menu">
                <li><a href="{{ url('/about') }}">{{ __('About us') }}</a></li>
                <li><a href="{{ url('/latest-launches') }}">{{ __('Featured Properties') }}</a></li>
                <li><a href="{{ url('/services') }}">{{ __('Our Best Services') }}</a></li>
                <li><a href="{{ url('/contact') }}">{{ __('Request Visit') }}</a></li>
                <li><a href="{{ url('/careers') }}">{{ __('Careers') }}</a></li>
              </ul>
            </div>
          </div>

          {{-- Explore --}}
          <div class="col-md-6 col-xl-auto">
            <div class="widget widget_nav_menu footer-widget">
              <h3 class="widget_title">{{ __('Explore') }}</h3>
              <ul class="menu">
                <li><a href="{{ url('/projects') }}">{{ __('All Properties') }}</a></li>
                <li><a href="{{ url('/'.app()->getLocale().'/team') }}">{{ __('Our Team') }}</a></li>
                <li><a href="{{ url('/projects') }}">{{ __('All Projects') }}</a></li>
                <li><a href="{{ url('/about') }}">{{ __('Our Process') }}</a></li>
                <li><a href="{{ url('/blog') }}">{{ __('Blog') }}</a></li>
              </ul>
            </div>
          </div>
        </div>

        {{-- Newsletter --}}
        {{-- <div class="newsletter-wrap">
          <h5 class="newsletter-title">{{ __('Newsletter To Get Updated The Latest News') }}</h5>
          <form method="POST" class="newsletter-form">
            <div class="form-group">
              <input type="email" class="form-control" placeholder="{{ __('Enter Email') }}">
            </div>
            <button type="submit" class="th-btn btn-primary">
              {{ __('Subscribe Now') }}
              <span class="btn-icon"><img src="{{ asset('assets/img/icon/paper-plane.svg') }}" alt="img"></span>
            </button>
          </form>
        </div> --}}

      </div>
    </div>

    {{-- Copyright --}}
    <div class="copyright-wrap">
      <div class="container">
        <div class="row gy-2 align-items-center">
          <div class="col-lg-6">
            <p class="copyright-text">
              &copy; {{ date('Y') }} <a href="https://fida.af" target="_blank">Fida Technologies</a>, {{ __('All rights reserved.') }}
            </p>
          </div>
          <div class="col-lg-6 text-center text-lg-end">
            <div class="footer-links">
              <ul>
                <li><a href="{{ url('/terms') }}">{{ __('Terms of service') }}</a></li>
                <li><a href="{{ url('/privacy') }}">{{ __('Privacy policy') }}</a></li>
                <li><a href="{{ url('/cookies') }}">{{ __('Cookies') }}</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
