<header class="th-header header-layout1">
    <div class="sticky-wrapper">
        <div class="menu-area">
            <div class="container">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="header-logo">
                            <a href="{{ url('/') }}" class="d-inline-block">
                                <!-- Light logo for transparent hero -->
                                <img class="logo-light" src="{{ asset('assets/img/rest_logo_white.webp') }}" style="width:auto;height:80px" alt="REST">
                                <!-- Dark logo for white sticky header -->
                                <img class="logo-dark" src="{{ asset('assets/img/rest_logo.png') }}" style="width:auto;height:80px;display:none" alt="REST">
                            </a>
                        </div>

                    </div>

                    <div class="col-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            <ul>
                                <li><a href="{{ url('/') }}" class="active">HOME</a></li>
                                <li><a href="{{ url('/about') }}">ABOUT US</a></li>
                                <li><a href="{{ url('/latest-launches') }}">LATEST LAUNCHES</a></li>
                                <li><a href="{{ url('/projects') }}">PROJECTS</a></li>
                                {{-- <li><a href="{{ url('/communities') }}">COMMUNITIES</a></li> --}}
                                <li><a href="{{ url('/blog') }}">MEDIA</a></li>
                                <li><a href="{{ url('/careers') }}">CAREERS</a></li>
                            </ul>
                        </nav>

                        {{-- Mobile burger --}}
                        <div class="header-button d-flex d-lg-none">
                            <button type="button" class="th-menu-toggle sidebar-btn">
                                <span class="line"></span><span class="line"></span><span class="line"></span>
                            </button>
                        </div>
                    </div>

                    <div class="d-none d-xl-flex align-items-center col-auto gap-3">
                        {{-- Language switcher (desktop) --}}
                        @php
                            $availableLocales = config('app.available_locales', ['en', 'fa', 'ps', 'uz']);
                            $localeLabels = [
                                'en' => ['name' => 'English', 'short' => 'EN', 'flag' => asset('assets/img/flags/us.svg')],
                                'fa' => ['name' => 'فارسی', 'short' => 'FA', 'flag' => asset('assets/img/flags/af.svg')],
                                'ps' => ['name' => 'پښتو', 'short' => 'PS', 'flag' => asset('assets/img/flags/af.svg')],
                                'uz' => ['name' => 'O\'zbek', 'short' => 'UZ', 'flag' => asset('assets/img/flags/uz.svg')],
                            ];
                            $current = app()->getLocale();
                        @endphp
                        <div class="dropdown lang-switch">
                            <button class="btn btn-premium-lang dropdown-toggle" style="color:white; border: none !important;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{-- @if ($localeLabels[$current]['flag'] ?? null)
                                    <img src="{{ $localeLabels[$current]['flag'] }}" class="me-2" alt="" width="18" height="18">
                                @endif --}}
                                {{ $localeLabels[$current]['short'] ?? strtoupper($current) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                @foreach ($availableLocales as $loc)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center {{ $current === $loc ? 'active' : '' }} gap-2" href="{{ route('lang.switch', $loc) }}">
                                            {{-- @if ($localeLabels[$loc]['flag'] ?? null)
                                                <img src="{{ $localeLabels[$loc]['flag'] }}" alt="" width="16" height="16">
                                            @endif --}}
                                            <span>{{ $localeLabels[$loc]['name'] ?? strtoupper($loc) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="header-button">
                            <a href="{{ url('/contact') }}" class="th-btn style4 th-btn-icon">Contact Us</a>
                            <button type="button" class="simple-icon sideMenuInfo sidebar-btn">
                                <span class="line"></span><span class="line"></span><span class="line"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</header>
