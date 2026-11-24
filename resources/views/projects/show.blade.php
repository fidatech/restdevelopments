@extends('layouts.heritage')
@section('title', $project->t()?->title ?? __('Project'))
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags($project->t()?->description ?? '')), 155))
@section('og_image', $project->image ? media_url($project->image) : '')
@section('content')
@php
    $locale = app()->getLocale();
    $tr = $project->t();
    $storageUrl = fn ($path) => $path ? (preg_match('#^(https?:)?//#', $path) ? $path : asset(preg_replace('#^/?(storage/)?#', 'storage/', $path))) : null;
    $facts = array_filter([
        __('Bedrooms') => $project->beds,
        __('Bathrooms') => $project->baths,
    ]);
    $documents = collect($docs['floor_plan'] ?? [])->merge($docs['master_plan'] ?? [])->merge($docs['brochure'] ?? []);
    $description = trim(preg_replace('/\n\s*\n+/', "\n", str_replace("\r", '', strip_tags($tr?->description ?? ''))));
    $status = $project->state ? ($project->state === 'ongoing' ? __('Under construction') : __(ucfirst($project->state))) : null;
    $bedRange = $units->pluck('bedrooms')->filter()->unique()->sort()->values();
    $keyfacts = [
        __('Starting price') => $project->price_starts_from ?: __('On request'),
        __('Unit types') => $bedRange->count() ? $bedRange->implode(', ').' '.($bedRange->count() === 1 && (int) $bedRange->first() === 1 ? __('bedroom') : __('bedrooms')) : ($project->beds ? $project->beds.' '.__('bedrooms') : __('On request')),
        __('Size') => $project->area ? number_format((float) $project->area).' m²' : __('On request'),
        __('Status') => $status ?: __('On request'),
        __('Location') => $project->location ?: __('On request'),
    ];
    $mapQuery = trim(($tr?->title ?? '').', '.($project->location ?? ''), ', ');
    $highlights = $project->features->filter(fn ($f) => $f->image)->values();
    $places = $places ?? collect();
    $placeName = ($shortName ?? '') ?: ($tr?->title ?? '');
    $galleryGroups = ['exterior' => __('Exterior'), 'interior' => __('Interiors')];
    $galleryTypes = $project->galleries->pluck('type')->unique()->filter()->values();
    $whatsapp = 'https://api.whatsapp.com/send?phone=+93777578657&text='.rawurlencode('Hello, I would like to know more about '.($tr?->title ?? 'your project'));
@endphp
@include('partials.page-head', ['image' => $storageUrl($project->image) ?: asset('assets/img/brand/towers.webp'), 'title' => $tr?->title ?? __('Project'), 'kicker' => $project->location, 'crumbs' => [[__('Projects'), route('projects.index', ['locale' => $locale])]]])

<nav class="subnav" aria-label="{{ __('On this page') }}" data-subnav>
    <div class="wrap">
        <a href="#overview">{{ __('Overview') }}</a>
        @if($highlights->isNotEmpty() || $places->isNotEmpty())<a href="#highlights">{{ __('Highlights') }}</a>@endif
        @if($units->isNotEmpty())<a href="#units">{{ __('Units') }}</a>@endif
        @if($project->galleries->isNotEmpty())<a href="#gallery">{{ __('Gallery') }}</a>@endif
        <a href="#payment">{{ __('Payment plan') }}</a>
        @if($project->location)<a href="#location">{{ __('Location') }}</a>@endif
        @if($documents->isNotEmpty())<a href="#downloads">{{ __('Downloads') }}</a>@endif
        <a class="btn btn--green" href="#register">{{ __('Register interest') }}</a>
    </div>
</nav>

<div class="wrap">
    <dl class="keyfacts">@foreach($keyfacts as $label => $value)<div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
</div>

<section class="section" id="overview">
    <div class="wrap project-intro">
        <div>
            <span class="eyebrow">{{ __('About the project') }}</span>
            <div class="prose">{!! nl2br(e($description)) !!}</div>
        </div>
        <aside class="facts-wrap">
            <h2>{{ __('Interested?') }}</h2>
            <p>{{ __('Our sales team can share prices, floor plans and the payment schedule.') }}</p>
            @if($facts)
                <dl class="facts">@foreach($facts as $label => $value)<div class="fact"><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
            @endif
            <div class="actions">
                <a class="btn btn--green" href="#register">{{ __('Register interest') }}</a>
                <a class="btn btn--whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) WhatsApp</a>
            </div>
        </aside>
    </div>
</section>

@if($highlights->isNotEmpty())
<section class="section section--ink on-dark" id="highlights">
    <div class="wrap">
        <div class="section-head">
            <div><span class="eyebrow">{{ __('Highlights') }}</span><h2>{{ __('Life at') }} {{ $placeName }}</h2></div>
            <p>{{ __('Everything a family needs, planned inside the community.') }}</p>
        </div>
        <div class="highlights">
            @foreach($highlights as $feature)
                @php($ft = $feature->t())
                <figure class="highlight">
                    <div class="highlight-img"><img src="{{ media_url($feature->image) }}" alt="{{ $ft?->title }}" loading="lazy" decoding="async" width="1440" height="1080"></div>
                    <figcaption>
                        <h3>{{ $ft?->title }}</h3>
                        @if($ft?->description)<p>{{ $ft->description }}</p>@endif
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($places->isNotEmpty())
<section class="section"@if($highlights->isEmpty()) id="highlights"@endif>
    <div class="wrap">
        <div class="section-head">
            <div><span class="eyebrow">{{ __('Inside') }} {{ $placeName }}</span><h2>{{ __('Shops, services and leisure') }}</h2></div>
        </div>
        <div class="life-grid">
            @foreach($places as $place)
                @php($pname = preg_replace('/\s+at\s+.*$/i', '', $place->t()?->title ?? ''))
                <a class="life-tile" href="{{ route('projects.show', ['locale' => $locale, 'slug' => $place->slug]) }}">
                    @if($place->image)<img src="{{ media_url($place->image) }}" alt="" loading="lazy" decoding="async" width="600" height="760">@endif
                    <span class="life-label"><strong>{{ $pname }}</strong><small>{{ \Illuminate\Support\Str::limit(trim(strip_tags($place->t()?->description ?? '')), 80) }}</small></span>
                    <span class="life-go">@include('partials.arrow')</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($project->galleries->isNotEmpty())
<section class="section section--mist" id="gallery">
    <div class="wrap">
        <div class="section-head">
            <div><span class="eyebrow">{{ __('Gallery') }}</span><h2>{{ __('A closer look') }}</h2></div>
            @if($galleryTypes->count() > 1)
                <div class="gallery-tabs" role="group" aria-label="{{ __('Filter photos') }}">
                    <button type="button" class="is-active" data-gallery-filter="all" aria-pressed="true">{{ __('All') }} <span>{{ $project->galleries->count() }}</span></button>
                    @foreach($galleryGroups as $type => $label)
                        @php($n = $project->galleries->where('type', $type)->count())
                        @if($n)<button type="button" data-gallery-filter="{{ $type }}" aria-pressed="false">{{ $label }} <span>{{ $n }}</span></button>@endif
                    @endforeach
                </div>
            @endif
        </div>
        <div class="gallery" data-gallery>
            @foreach($project->galleries as $image)
                @php($src = $storageUrl($image->image))
                <a href="{{ $src }}" data-type="{{ $image->type }}" data-lightbox @if($loop->index >= 9) class="is-extra" @endif><img src="{{ $src }}" alt="{{ $tr?->title }}, {{ $galleryGroups[$image->type] ?? __('photo') }} {{ $loop->iteration }}" loading="lazy" decoding="async" width="800" height="600"></a>
            @endforeach
        </div>
        @if($project->galleries->count() > 9)
            <p class="gallery-more"><button class="btn btn--line" type="button" data-gallery-more>{{ __('Show all photos') }} ({{ $project->galleries->count() }})</button></p>
        @endif
    </div>
</section>
@endif

@if($units->isNotEmpty())
<section class="section" id="units">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Apartments') }}</span><h2>{{ __('Choose your layout') }}</h2></div></div>
        <table class="units">
            <thead><tr><th scope="col">{{ __('Layout') }}</th><th scope="col">{{ __('Bedrooms') }}</th><th scope="col">{{ __('Size') }}</th></tr></thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $unit->name ?: __('Apartment') }}@if($unit->description)<small>{{ $unit->description }}</small>@endif</td>
                        <td>{{ $unit->bedrooms ? $unit->bedrooms.' '.($unit->bedrooms == 1 ? __('bedroom') : __('bedrooms')) : '—' }}</td>
                        <td>@if($unit->min_area){{ $unit->min_area }}@if($unit->max_area && $unit->max_area != $unit->min_area)–{{ $unit->max_area }}@endif m²@else — @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

<section class="section" id="payment">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Payment plan') }}</span><h2>{{ __('A plan that fits you') }}</h2></div><p>{{ __('Payment plans are prepared for each buyer. Speak with our sales team for the current schedule and prices.') }}</p></div>
        <div class="plan-steps">
            <div class="plan-step"><b>1</b><p>{{ __('Choose your unit and reserve it with a booking payment.') }}</p></div>
            <div class="plan-step"><b>2</b><p>{{ __('Pay in instalments during construction, on a schedule agreed with you.') }}</p></div>
            <div class="plan-step"><b>3</b><p>{{ __('Settle the balance at handover and receive your keys.') }}</p></div>
        </div>
    </div>
</section>

@if($project->location)
<section class="section section--mist" id="location">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Location') }}</span><h2>{{ $project->location }}</h2></div><a class="text-link" href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode($mapQuery) }}" target="_blank" rel="noopener">{{ __('Open in Google Maps') }} @include('partials.arrow')</a></div>
        <div class="map-frame"><iframe src="https://www.google.com/maps?q={{ rawurlencode($mapQuery) }}&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="{{ __('Map of') }} {{ $tr?->title }}"></iframe></div>
    </div>
</section>
@endif

@if($documents->isNotEmpty())
<section class="section" id="downloads">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Downloads') }}</span><h2>{{ __('Plans and brochures') }}</h2></div></div>
        <div class="docs">
            @foreach($documents as $document)
                <a class="doc" href="{{ media_url($document->file) }}" target="_blank" rel="noopener"><strong>{{ __(\Illuminate\Support\Str::headline($document->type)) }}</strong><span>{{ __('Open PDF') }}</span></a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section section--green on-dark" id="register">
    <div class="wrap register">
        <div>
            <span class="eyebrow">{{ \Illuminate\Support\Str::limit($tr?->title, 60) }}</span>
            <h2>{{ __('Register your interest') }}</h2>
            <p style="color:rgba(255,255,255,.8);margin-top:20px">{{ __('Leave your details and our sales team will call you with prices, availability and payment options.') }}</p>
            <div class="actions" style="margin-top:28px">
                <a class="btn btn--whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) WhatsApp</a>
                <a class="btn btn--white" href="tel:+93777578657" dir="ltr">+93 777 578 657</a>
            </div>
        </div>
        <div class="register-form">
            @if(session('success'))<p class="notice" role="status">{{ session('success') }}</p>@endif
            @if($errors->any())<div class="notice notice--error" role="alert">{{ $errors->first() }}</div>@endif
            <form method="post" action="{{ route('contact.send', ['locale' => $locale]) }}" class="form-grid">
                @csrf
                <input type="hidden" name="subject" value="{{ __('Interest in') }} {{ $tr?->title }}">
                <div class="field"><label for="ri-name">{{ __('Full name') }}</label><input id="ri-name" name="name" required autocomplete="name" value="{{ old('name') }}"></div>
                <div class="field"><label for="ri-phone">{{ __('Phone') }}</label><input id="ri-phone" name="phone" type="tel" required autocomplete="tel" value="{{ old('phone') }}" dir="ltr"></div>
                <div class="field field--full"><label for="ri-email">{{ __('Email') }}</label><input id="ri-email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"></div>
                <div class="field field--full"><label for="ri-message">{{ __('Unit you are interested in (optional)') }}</label><textarea id="ri-message" name="message" rows="3">{{ old('message') }}</textarea></div>
                <div class="field field--full"><button class="btn btn--green" type="submit">{{ __('Register interest') }}</button></div>
            </form>
            <p class="register-note">{{ __('We use your details only to contact you about this project.') }}</p>
        </div>
    </div>
</section>

@if($featuredProjects->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><h2>{{ __('More projects') }}</h2><a class="text-link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('View all projects') }} @include('partials.arrow')</a></div>
        <div class="cards">@foreach($featuredProjects->take(3) as $project)@include('partials.heritage-project-card')@endforeach</div>
    </div>
</section>
@endif
@include('partials.contact-band')
<div class="lightbox" id="lightbox" hidden>
    <button class="lightbox-close" type="button" data-lightbox-close aria-label="{{ __('Close') }}"><svg viewBox="0 0 16 16" aria-hidden="true"><path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></button>
    <button class="lightbox-nav is-prev" type="button" data-lightbox-prev aria-label="{{ __('Previous') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M11 3L5 9l6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
    <figure><img alt="" data-lightbox-img><figcaption data-lightbox-count></figcaption></figure>
    <button class="lightbox-nav is-next" type="button" data-lightbox-next aria-label="{{ __('Next') }}"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M7 3l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.5"/></svg></button>
</div>
@endsection
