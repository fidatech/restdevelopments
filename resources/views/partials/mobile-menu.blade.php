<div class="th-menu-wrapper onepage-nav">
  <div class="th-menu-area text-center">
    <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
    <div class="mobile-logo">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/img/rest_logo_white.webp') }}" alt="REST">
      </a>
    </div>

    <div class="th-mobile-menu">
      <ul>
        <li><a href="{{ url('/') }}">HOME</a></li>
        <li><a href="{{ url('/about') }}">ABOUT US</a></li>
        <li><a href="{{ url('/latest-launches') }}">LATEST LAUNCHES</a></li>
        <li><a href="{{ url('/properties') }}">PROJECTS</a></li>
        {{-- <li><a href="{{ url('/communities') }}">COMMUNITIES</a></li> --}}
        <li><a href="{{ url('/blog') }}">MEDIA</a></li>
        <li><a href="{{ url('/careers') }}">CAREERS</a></li>
        <li><a href="{{ url('/contact') }}">CONTACT US</a></li>
      </ul>
    </div>

    {{-- Language switcher (mobile) --}}
    @php
      $availableLocales = config('app.available_locales', ['en','fa','ps', 'uz']);
      $localeLabels = [
        'en' => ['name' => 'English', 'short'=>'EN', 'flag' => asset('assets/img/flags/us.svg')],
                'fa' => ['name' => 'فارسی',   'short'=>'FA', 'flag' => asset('assets/img/flags/af.svg')],
                'ps' => ['name' => 'پښتو',    'short'=>'PS', 'flag' => asset('assets/img/flags/af.svg')],
                'uz' => ['name' => 'O\'zbek',    'short'=>'UZ', 'flag' => asset('assets/img/flags/uz.svg')],
      ];
      $current = app()->getLocale();
    @endphp
    <div class="mt-3 px-3">
      <div class="mobile-lang d-flex justify-content-center flex-wrap gap-2">
        @foreach($availableLocales as $loc)
          <a href="{{ route('lang.switch', $loc) }}"
             class="lang-chip {{ $current===$loc ? 'active' : '' }}">
            @if(($localeLabels[$loc]['flag'] ?? null))
              <img src="{{ $localeLabels[$loc]['flag'] }}" alt="" width="16" height="16" class="me-1">
            @endif
            {{ $localeLabels[$loc]['short'] ?? strtoupper($loc) }}
          </a>
        @endforeach
      </div>
    </div>

  </div>
</div>
