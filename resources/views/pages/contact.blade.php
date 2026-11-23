@extends('layouts.heritage')
@section('title', __('Contact us'))
@section('description', __('Contact Rest Developments about our projects, apartments and company in Kabul, Termiz and Dubai.'))
@section('content')
@php
    $phone = $setting?->phone_1 ?: '+93 777 578 657';
    $email = $setting?->email_1 ?: 'info@restdevelopments.com';
@endphp
@include('partials.page-head', ['image' => asset('assets/img/brand/towers-park.webp'), 'title' => __('Get in touch'), 'current' => __('Contact'), 'kicker' => __('Contact us'), 'lead' => $setting?->t()?->company_intro ?? __('Ask about a project, book a site visit or request a price list.')])
<section class="section">
    <div class="wrap contact-grid">
        <div>
            <h2>{{ __('Speak with our sales team') }}</h2>
            <dl class="contact-lines">
                <div><dt>{{ __('Phone') }}</dt><dd><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" dir="ltr">{{ $phone }}</a></dd></div>
                <div><dt>{{ __('Email') }}</dt><dd><a href="mailto:{{ $email }}">{{ $email }}</a></dd></div>
                <div><dt>{{ __('Offices') }}</dt><dd>{{ $setting?->t()?->address ?: 'Kabul, Termiz, Dubai' }}</dd></div>
            </dl>
            <div class="actions">
                <a class="btn btn--whatsapp" href="https://api.whatsapp.com/send?phone=+93777578657&amp;text=Hello%20Rest%20Developments" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) WhatsApp</a>
                <a class="btn btn--line" href="https://t.me/rest_developments" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'telegram']) Telegram</a>
            </div>
        </div>
        <div class="form-card">
            <h2>{{ __('Send us a message') }}</h2>
            @if(session('success'))<p class="notice" role="status">{{ session('success') }}</p>@endif
            @if($errors->any())<div class="notice notice--error" role="alert">{{ $errors->first() }}</div>@endif
            <form method="post" action="{{ route('contact.send', ['locale' => app()->getLocale()]) }}" class="form-grid">
                @csrf
                <div class="field"><label for="name">{{ __('Full name') }}</label><input id="name" name="name" required autocomplete="name" value="{{ old('name') }}"></div>
                <div class="field"><label for="phone">{{ __('Phone') }}</label><input id="phone" name="phone" type="tel" required autocomplete="tel" value="{{ old('phone') }}" dir="ltr"></div>
                <div class="field"><label for="email">{{ __('Email') }}</label><input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"></div>
                <div class="field"><label for="subject">{{ __('Subject') }}</label><input id="subject" name="subject" required value="{{ old('subject') }}"></div>
                <div class="field field--full"><label for="message">{{ __('Message') }}</label><textarea id="message" name="message">{{ old('message') }}</textarea></div>
                <div class="field field--full"><button class="btn btn--green" type="submit" style="align-self:flex-start">{{ __('Send message') }}</button></div>
            </form>
        </div>
    </div>
</section>
@endsection
