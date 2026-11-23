<section class="cta on-dark">
    <img src="{{ asset('assets/img/brand/towers-park.webp') }}" alt="" width="920" height="496" loading="lazy" decoding="async">
    <div class="wrap">
        <h2>{{ __('Let’s find your new home') }}</h2>
        <p>{{ __('Speak with our sales team in Kabul, Termiz or Dubai.') }}</p>
        <div class="actions">
            <a class="btn btn--whatsapp" href="https://api.whatsapp.com/send?phone=+93777578657&amp;text=Hello%20Rest%20Developments" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) {{ __('WhatsApp us') }}</a>
            <a class="btn btn--white" href="tel:+93777578657"><span dir="ltr">+93 777 578 657</span></a>
            <a class="btn btn--line" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">{{ __('Send a message') }}</a>
        </div>
    </div>
</section>
