<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'fa', 'ps']) ? 'rtl' : 'ltr' }}">

    <head>
        <meta charset="utf-8">
        <meta name="google-site-verification" content="LENZQHS9BfAGydgrszKg9F4Qiu-D2MpgEYoGNJ6LRlQ" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <!--<title> @yield('title') </title>-->
        <title>Rest Developments – Real Estate Development in Afghanistan & Uzbekistan</title>
        <meta name="author" content="Rest Developments">
        <meta name="description" content="Rest Developments is a real estate development company delivering modern residential and mixed-use projects across Afghanistan and Uzbekistan.">
        <meta name="keywords" content="rest, rest tower, rest complex, rest developments, real estate, real estate in afghanistan, property management, property for sale,ریسټ ">
        <meta name="robots" content="INDEX,FOLLOW">

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Favicons - Place favicon.ico in the root directory -->
        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="assets/img/favicons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
        <link rel="manifest" href="assets/img/favicons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "RealEstateAgent",
  "@id": "https://restdevelopments.com/#organization",
  "name": "Rest Developments",
  "url": "https://restdevelopments.com/",
  "logo": "https://restdevelopments.com/assets/img/rest_logo.png",
  "image": "https://restdevelopments.com/assets/img/rest_logo.png",
  "description": "Rest Developments is a real estate development company delivering modern residential and mixed-use projects across Afghanistan and Uzbekistan.",
  "address": {
      "@type": "PostalAddress",
      "streetAddress": "Shahr-e-naw",
      "addressLocality": "Kabul",
      "addressCountry": "AF"
    },
  "areaServed": [
    {
      "@type": "Country",
      "name": "Afghanistan"
    },
    {
      "@type": "Country",
      "name": "Uzbekistan"
    }
  ],
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer service",
    "telephone": "+93777578657",
    "email": "info@restdevelopments.com",
    "availableLanguage": [
      "English",
      "Dari",
      "Pashto",
      "Uzbek"
    ]
  },
  "sameAs": [
    "https://www.facebook.com/profile.php?id=61571457661694",
    "https://www.instagram.com/restdevelopments",
    "https://www.youtube.com/@restdevelopments",
    "https://t.me/rest_developments"
  ]
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Rest Developments",
  "url": "https://restdevelopments.com",
  "logo": "https://restdevelopments.com/assets/img/rest_logo.png",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "AF"
  },
  "areaServed": [
    {
      "@type": "Country",
      "name": "Afghanistan"
    },
    {
      "@type": "Country",
      "name": "Uzbekistan"
    }
  ]
}
</script>

@endverbatim


@verbatim
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What does Rest Developments do?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Rest Developments is a real estate development company delivering residential and mixed-use projects in Afghanistan and Uzbekistan."
      }
    },
    {
      "@type": "Question",
      "name": "Where does Rest Developments operate?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Rest Developments operates in Afghanistan and Uzbekistan, focusing on modern residential and commercial developments."
      }
    },
    {
      "@type": "Question",
      "name": "How can I contact Rest Developments?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "You can contact Rest Developments via email at info@restdevelopments.com or through our official social media channels."
      }
    }
  ]
}
</script>
@endverbatim

@include('partials.schema.breadcrumb')


        <!--==============================
 Google Fonts
 ============================== -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

        <!--==============================
 All CSS File
 ============================== -->
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <!-- Fontawesome Icon -->
        <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}">
        <!-- Magnific Popup -->
        <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
        <!-- Swiper Js -->
        <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">
        <!-- datetimepicker -->
        <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

        <style>
            /* --- Make main navigation links white --- */
            .header-layout1 .main-menu>ul>li>a {
                color: #fff !important;
                letter-spacing: .4px;
                text-transform: uppercase;
            }

            .header-layout1 .main-menu>ul>li>a:hover,
            .header-layout1 .main-menu>ul>li.current-menu-item>a {
                color: #fff !important;
                opacity: .9;
            }

            /* Optional: subtle underline on hover/active */
            .header-layout1 .main-menu>ul>li>a::after {
                content: '';
                display: block;
                height: 2px;
                width: 0;
                background: linear-gradient(90deg, #fff, rgba(255, 255, 255, .6));
                transition: width .2s ease;
                margin-top: 6px;
            }

            .header-layout1 .main-menu>ul>li:hover>a::after,
            .header-layout1 .main-menu>ul>li.current-menu-item>a::after {
                width: 100%;
            }

            /* Burger & simple icon lines to white */
            .th-menu-toggle .line,
            .simple-icon .line {
                background: #fff !important;
            }

            /* Language button already white; keep dropdown readable on white bg */
            .lang-switch .dropdown-menu {
                border-radius: 12px;
                background: #fff;
            }

            .lang-switch .dropdown-item {
                color: #212529;
            }

            .lang-switch .dropdown-item.active {
                background: rgba(120, 75, 155, .1);
                color: #5d2aa2;
                font-weight: 700;
            }

            .mobile-lang .lang-chip {
                display: inline-flex;
                align-items: center;
                border-radius: 999px;
                padding: .4rem .8rem;
                font-weight: 700;
                border: 1px solid rgba(255, 255, 255, .5);
                color: #fff;
                background: rgba(255, 255, 255, .08);
                backdrop-filter: blur(6px);
            }

            .mobile-lang .lang-chip.active {
                border-color: transparent;
                background: linear-gradient(135deg, #7a39c0, #5d2aa2);
            }
        </style>
        @stack('styles')
        
        
@stack('schema')


        <style>
            #socialRedirectModal .modal-dialog { max-width: 470px; margin-inline: auto; padding-inline: 12px; }
            #socialRedirectModal .rest-welcome {
                border: 0;
                background: #fff !important;
                border-radius: 16px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 20px 50px rgba(0, 0, 0, .26);
            }
            .rest-welcome__header {
                position: relative;
                padding: 25px 48px;
                background: #1c1c1c;
                text-align: center;
            }
            .rest-welcome__header h2 {
                color: #fff;
                font-size: clamp(1.25rem, 5vw, 1.6rem);
                line-height: 1.3;
                margin: 0;
            }
            .rest-welcome__close {
                position: absolute;
                inset-block-start: 7px;
                inset-inline-end: 7px;
                width: 40px;
                height: 40px;
                border: 0;
                border-radius: 50%;
                background: transparent;
                color: #fff;
                font-size: 1.75rem;
                line-height: 1;
            }
            .rest-welcome__close:hover { background: rgba(255, 255, 255, .16); }
            #socialRedirectModal .rest-welcome__body { padding: clamp(18px, 5vw, 28px); background: #fff !important; }
            .rest-welcome__primary {
                display: flex;
                gap: 10px;
                align-items: center;
                justify-content: center;
                width: 100%;
                min-height: 52px;
                padding: 10px 16px;
                border: 0;
                border-radius: 999px;
                background: #005601;
                color: #fff;
                font-weight: 700;
            }
            .rest-welcome__primary:hover { background: #004400; }
            .rest-welcome__label {
                margin: 20px 0 12px;
                color: #b45b00;
                font-weight: 700;
                text-align: center;
            }
            .rest-welcome__links {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }
            .rest-welcome__links a {
                display: flex;
                gap: 8px;
                align-items: center;
                justify-content: center;
                min-height: 46px;
                padding: 8px;
                border-radius: 999px;
                color: #fff;
                font-size: .875rem;
                font-weight: 700;
                text-align: center;
            }
            .rest-welcome__links a:hover { color: #fff; filter: brightness(.88); }
            .rest-welcome__links i { color: #fff; font-size: 1rem; }
            .rest-welcome__links a:last-child { grid-column: 1 / -1; justify-self: center; width: calc(50% - 5px); }
            .rest-welcome__facebook { background: #1877F2; }
            .rest-welcome__instagram { background: linear-gradient(45deg, #f58529, #dd2a7b, #8134af, #515bd4); }
            .rest-welcome__youtube { background: #FF0000; }
            .rest-welcome__telegram { background: #0088cc; }
            .rest-welcome__whatsapp { background: #25D366; }
            .rest-welcome :focus-visible { outline: 2px solid #1c1c1c; outline-offset: 2px; }
            .rest-welcome__close:focus-visible { outline-color: #fff; }
        </style>
    </head>

    <body class="bg-smoke">

        <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

        <!--********************************
   Code Start From Here
 ******************************** -->
        <div class="cursor-follower"></div>

        <!-- slider drag cursor -->
        <div class="slider-drag-cursor"><i class="fas fa-angle-left me-2"></i> CLICK <i class="fas fa-angle-right ms-2"></i></div>

        <!--==============================
    Preloader
==============================-->
        @include('partials.preloader')
        <!--==============================
    Mobile Menu
  ============================== -->
        @include('partials.mobile-menu')
        <!--==============================
    Sidemenu
============================== -->
        {{-- <div class="sidemenu-wrapper sidemenu-info d-none d-lg-block">
            <div class="sidemenu-content">
                <button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
                <div class="widget">
                    <div class="th-widget-about">
                        <div class="about-logo">
                            <a href="index.html"><img src="assets/img/logo.svg" alt="Rest Developments"></a>
                        </div>
                        <p class="about-text"> Rapidiously myocardinate cross-platform intellectual capital model. Appropriately create interactive infrastructures</p>
                    </div>
                </div>
                <div class="widget">
                    <h3 class="widget_title">Get In Touch</h3>
                    <div class="th-widget-contact">
                        <div class="info-box_text">
                            <div class="icon"><img src="assets/img/icon/location-dot.svg" alt="img"></div>
                            <div class="details">
                                <p>789 Inner Lane, Holy park,</p>
                                <p>California, USA</p>
                            </div>
                        </div>
                        <div class="info-box_text">
                            <div class="icon">
                                <img src="assets/img/icon/phone.svg" alt="img">
                            </div>
                            <div class="details">
                                <p><a href="tel:+0123456789" class="info-box_link">+01 234 567 890</a></p>
                                <p><a href="tel:+09876543210" class="info-box_link">+09 876 543 210</a></p>
                            </div>
                        </div>
                        <div class="info-box_text">
                            <div class="icon">
                                <img src="assets/img/icon/envelope.svg" alt="img">
                            </div>
                            <div class="details">
                                <p><a href="mailto:mailinfo00@Rest Developments.com" class="info-box_link">mailinfo00@Rest Developments.com</a></p>
                                <p><a href="mailto:support24@Rest Developments.com" class="info-box_link">support24@Rest Developments.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget newsletter-widget">
                    <h3 class="widget_title">Subscribe Now</h3>
                    <form class="newsletter-form">
                        <div class="form-group">
                            <input class="form-control" type="email" placeholder="Email Address" required="">
                            <button type="submit" class="th-btn"><i class="far fa-paper-plane text-theme"></i></button>
                        </div>
                    </form>
                    <div class="th-social style2">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.behance.com/"><i class="fab fa-behance"></i></a>
                        <a href="https://www.vimeo.com/"><i class="fab fa-vimeo-v"></i></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <!--==============================
 Header Area
==============================-->
        @include('partials.header')

        @yield('content')
        <!--==============================
 Footer Area
==============================-->
        @include('partials.footer')

        <div class="modal fade" id="socialRedirectModal" tabindex="-1" aria-labelledby="socialRedirectLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rest-welcome">
                    <div class="rest-welcome__header">
                        <button type="button" class="rest-welcome__close" data-welcome-dismiss aria-label="{{ __('Close') }}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h2 id="socialRedirectLabel">{{ __('Connect with Rest Developments') }}</h2>
                    </div>
                    <div class="modal-body rest-welcome__body">
                        <button type="button" class="rest-welcome__primary" data-welcome-dismiss>
                            <i class="fas fa-globe" aria-hidden="true"></i>
                            {{ __('Continue to website') }}
                        </button>
                        <p class="rest-welcome__label">{{ __('Choose where you’d like to go:') }}</p>
                        <div class="rest-welcome__links">
                            <a class="rest-welcome__facebook" href="https://www.facebook.com/share/1Bc9FitgQD/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                <span>Facebook</span>
                            </a>
                            <a class="rest-welcome__instagram" href="https://www.instagram.com/restdevelopments?igsh=MTBibzh5NHVnbTZnaw%3D%3D&amp;utm_source=qr" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-instagram" aria-hidden="true"></i>
                                <span>Instagram</span>
                            </a>
                            <a class="rest-welcome__youtube" href="https://youtu.be/x4Fibo6XObs?si=PJWWInMGtG8Z6IPc" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-youtube" aria-hidden="true"></i>
                                <span>YouTube</span>
                            </a>
                            <a class="rest-welcome__telegram" href="https://t.me/rest_developments" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-telegram-plane" aria-hidden="true"></i>
                                <span>Telegram</span>
                            </a>
                            <a class="rest-welcome__whatsapp" href="https://api.whatsapp.com/send?phone=+93777578657&amp;text=Hello%20Rest%20Developments" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const popup = document.getElementById('socialRedirectModal');
                const popupKey = 'restWelcomeShown';
                if (!popup || !window.bootstrap || !window.bootstrap.Modal) return;

                try {
                    if (sessionStorage.getItem(popupKey)) return;
                } catch (error) {
                    return;
                }

                const modal = new bootstrap.Modal(popup);
                popup.querySelectorAll('[data-welcome-dismiss]').forEach(function (button) {
                    button.addEventListener('click', function () { modal.hide(); });
                });
                modal.show();
                try { sessionStorage.setItem(popupKey, 'yes'); } catch (error) {}
            });
        </script>

        <!--********************************
   Code End  Here
 ******************************** -->

        <!-- Scroll To Top -->
        <div class="scroll-top">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                    style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
            </svg>
        </div>

        <!--==============================
    All Js File
============================== -->
        <!-- Jquery -->
        <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>

        <!-- Swiper Js -->
        <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <!-- Magnific Popup -->
        <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
        <!-- Counter Up -->
        <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
        <!-- Range Slider -->
        <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
        <!-- Isotope Filter -->
        <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
        <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
        <!-- Gsap -->
        <script src="{{ asset('assets/js/gsap.min.js') }}"></script>
        <!-- DateTime JS -->
        <script src="{{ asset('assets/js/jquery.datetimepicker.min.js') }}"></script>

        <!-- 360 degree Js -->
        <script src="{{ asset('assets/js/threesixty.min.js') }}"></script>
        <script src="{{ asset('assets/js/panolens.min.js') }}"></script>

        <!-- Main Js File -->
        <script src="{{ asset('assets/js/main.js') }}"></script>
        @stack('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const counters = document.querySelectorAll('.counter-number');
                const speed = 200; // lower = faster

                const animateCounters = (entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const counter = entry.target;
                            const updateCount = () => {
                                const target = +counter.getAttribute('data-target');
                                const count = +counter.innerText.replace(/,/g, '');
                                const increment = Math.ceil(target / speed);

                                if (count < target) {
                                    // Force English digits
                                    counter.innerText = new Intl.NumberFormat('en-US').format(count + increment);
                                    setTimeout(updateCount, 30);
                                } else {
                                    counter.innerText = new Intl.NumberFormat('en-US').format(target);
                                }
                            };
                            updateCount();
                            observer.unobserve(counter); // run once
                        }
                    });
                };

                const observer = new IntersectionObserver(animateCounters, {
                    threshold: 0.5
                });
                counters.forEach(counter => observer.observe(counter));
            });
        </script>

    </body>

</html>
