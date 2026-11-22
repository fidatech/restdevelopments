@extends('layouts.base')
@section('title', __('Contact'))

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.2.0/css/intlTelInput.min.css"/>

<style>
/* ======== Contact Page Styles ======== */
.contact-form-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 40px;
    height: 100%;
}

.contact-form-title {
    color: #2a4365;
    font-weight: 700;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
}

.contact-form-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #4299e1;
}

/* Improved phone input styling */
.phone-input-container {
    position: relative;
    margin-bottom: 20px;
}

.phone-input-wrapper {
    display: flex;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.phone-input-wrapper:focus-within {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
}

.country-code-selector {
    padding: 12px 15px;
    background: #f7fafc;
    border-right: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;
    min-width: 120px;
}

.country-flag {
    width: 20px;
    height: 15px;
    margin-right: 8px;
}

.country-code {
    font-weight: 500;
    color: #4a5568;
}

.phone-number-input {
    flex: 1;
    border: none;
    padding: 12px 15px;
    font-size: 16px;
}

.phone-number-input:focus {
    outline: none;
}

/* Country dropdown styles */
.country-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.country-dropdown.show {
    display: block;
}

.country-option {
    padding: 10px 15px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: background 0.2s;
}

.country-option:hover {
    background: #f7fafc;
}

.country-option-flag {
    width: 20px;
    height: 15px;
    margin-right: 10px;
}

/* Form element styles */
.form-group {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    outline: none;
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.submit-btn {
    background: #4299e1;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.submit-btn:hover {
    background: #3182ce;
    transform: translateY(-2px);
}

.submit-btn i {
    margin-left: 10px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .contact-form-container {
        padding: 25px;
    }

    .phone-input-wrapper {
        flex-direction: column;
    }

    .country-code-selector {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }
}
</style>
@endpush

@section('content')
    <!--============================== Breadcumb ==============================-->
    <div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="breadcumb-content">
                        <h1 class="breadcumb-title">{{ __('Contact') }}</h1>
                        <ul class="breadcumb-menu">
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li>{{ __('Contact') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--============================== Contact Info ==============================-->
    <div class="space bg-luxury">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">{{ __('Get In Touch') }}</span>
                <h2 class="sec-title text-theme">{{ __('Our Contact Information') }}</h2>
            </div>
            <div class="row gy-4 justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid style2">
                        <div class="about-contact-icon"><i class="fal fa-location-dot"></i></div>
                        <div class="about-contact-details">
                            <h6 class="about-contact-details-title text-white">{{ __('Our Address') }}</h6>
                            <span class="text-white">{!! nl2br(e($setting->t()?->address)) !!}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid style2 text-white">
                        <div class="about-contact-icon"><i class="fal fa-phone"></i></div>
                        <div class="about-contact-details">
                            <h6 class="about-contact-details-title">{{ __('Phone Number') }}</h6>
                            @if ($setting->phone_1)
                                <p><a class="text-white" href="tel:{{ $setting->phone_1 }}">{{ $setting->phone_1 }}</a></p>
                            @endif
                            @if ($setting->phone_2)
                                <p><a class="text-white" href="tel:{{ $setting->phone_2 }}">{{ $setting->phone_2 }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid style2 text-white">
                        <div class="about-contact-icon"><i class="fal fa-envelope"></i></div>
                        <div class="about-contact-details">
                            <h6 class="about-contact-details-title">{{ __('Email Address') }}</h6>
                            @if ($setting->email_1)
                                <p><a class="text-white" href="mailto:{{ $setting->email_1 }}">{{ $setting->email_1 }}</a></p>
                            @endif
                            @if ($setting->email_2)
                                <p><a class="text-white" href="mailto:{{ $setting->email_2 }}">{{ $setting->email_2 }}</a></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--============================== Contact Form + Map ==============================-->
    <div class="space contact-area-3 z-index-common"
         data-bg-src="{{ asset('assets/img/bg/contact.webp') }}"
         data-overlay="title" data-opacity="3" id="contact-sec">

        <div class="container">
            <div class="row gx-35 align-items-stretch">
                {{-- Form --}}
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-form-container">
                        <h2 class="contact-form-title">{{ __('Schedule a Visit') }}</h2>
                        <form action="{{ route('contact.send') }}" method="POST" class="appointment-form">
                            <input type="hidden" name="country_code" id="country_code_input" value="+93">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Your Name*') }}" required>
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="{{ __('Your Email*') }}" required>
                            </div>

                            <!-- Improved Phone Input -->
                            <div class="phone-input-container">
                                <div class="phone-input-wrapper">
                                    <div class="country-code-selector" id="country-code-trigger">
                                        <img src="https://flagcdn.com/w20/us.png" class="country-flag" id="selected-flag">
                                        <span class="country-code" id="selected-code">+1</span>
                                        <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
                                    </div>
                                    <input type="tel" name="phone" id="phone" class="phone-number-input" placeholder="123 456 7890" required>
                                </div>
                                <div class="country-dropdown" id="country-dropdown">
                                    <!-- Countries will be populated by JavaScript -->
                                </div>
                            </div>

                            <div class="form-group">
                                <select name="subject" class="form-control" required>
                                    <option value="" disabled selected>{{ __('Select Service Type') }}</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Apartment">Apartment</option>
                                    <option value="Residential">Residential</option>
                                    <option value="Deluxe">Deluxe</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <textarea name="message" class="form-control" placeholder="{{ __('Your Message') }}"></textarea>
                            </div>

                            <button type="submit" class="submit-btn">
                                {{ __('Submit Message') }}
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Map --}}
                <div class="col-lg-6">
                    @if ($setting->map_iframe)
                        <div class="h-100">
                            <iframe src="{{ $setting->map_iframe }}"
                                    class="w-100 h-100 border-0"
                                    allowfullscreen=""
                                    loading="lazy">
                            </iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countries = [
        { code: 'AF', name: 'Afghanistan', dialCode: '+93', flag:'https://flagcdn.com/w20/af.png' },
        { code: 'UZ', name: 'Uzbekistan', dialCode: '+998', flag:'https://flagcdn.com/w20/uz.png' },
        { code: 'US', name: 'United States', dialCode: '+1', flag:'https://flagcdn.com/w20/us.png' },
        { code: 'CA', name: 'Canada', dialCode: '+1', flag:'https://flagcdn.com/w20/ca.png' },
        { code: 'AE', name: 'United Arab Emirates', dialCode: '+971', flag:'https://flagcdn.com/w20/ae.png' },
        { code: 'GB', name: 'United Kingdom', dialCode: '+44', flag:'https://flagcdn.com/w20/gb.png' },
        { code: 'PK', name: 'Pakistan', dialCode: '+92', flag:'https://flagcdn.com/w20/pk.png' },
        { code: 'IN', name: 'India', dialCode: '+91', flag:'https://flagcdn.com/w20/in.png' },
        { code: 'SA', name: 'Saudi Arabia', dialCode: '+966', flag:'https://flagcdn.com/w20/sa.png' }
    ];

    const countryCodeTrigger = document.getElementById('country-code-trigger');
    const countryDropdown = document.getElementById('country-dropdown');
    const selectedFlag    = document.getElementById('selected-flag');
    const selectedCode    = document.getElementById('selected-code');
    const phoneInput      = document.getElementById('phone');
    const hiddenInput     = document.getElementById('country_code_input');

    selectedFlag.src = 'https://flagcdn.com/w20/af.png';
    selectedCode.textContent = '+93';
    hiddenInput.value = '+93';

    let dropdownLoaded = false;

    function buildDropdown(){
        countries.forEach(country => {
            const option = document.createElement('div');
            option.className = 'country-option';
            option.innerHTML = `
                <img src="${country.flag}" class="country-option-flag">
                <span>${country.name} (${country.dialCode})</span>
            `;
            option.addEventListener('click', () => {
                selectedFlag.src        = country.flag;
                selectedCode.textContent= country.dialCode;
                hiddenInput.value       = country.dialCode;
                countryDropdown.classList.remove('show');
                phoneInput.focus();
            });
            countryDropdown.appendChild(option);
        });
        dropdownLoaded = true;
    }

    countryCodeTrigger.addEventListener('click', (e) => {
        e.stopPropagation();
        if(!dropdownLoaded){ buildDropdown(); }
        countryDropdown.classList.toggle('show');
    });

    document.addEventListener('click', () => {
        countryDropdown.classList.remove('show');
    });

    phoneInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        const code = selectedCode.textContent;
        if (code === '+1') {
            if (value.length > 3 && value.length <= 6) {
                value = value.replace(/(\d{3})(\d{0,3})/, '$1 $2');
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d{0,4})/, '$1 $2 $3');
            }
        } else {
            value = value.replace(/(\d{3})(?=\d)/g, '$1 ');
        }
        this.value = value;
    });
});
</script>


@if (session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        timer: 1500,
        showConfirmButton: false,
        didClose: () => {
            // Reset the form after the alert closes
            const form = document.querySelector('.appointment-form');
            if (form) {
                form.reset();
                // Reset phone field + country back to default
                document.getElementById('country_code_input').value = '+93';
                document.getElementById('selected-flag').src = 'https://flagcdn.com/w20/af.png';
                document.getElementById('selected-code').textContent = '+93';
            }
        }
    });
});
</script>
@endif

@endpush
