@extends('layouts.heritage')
@section('title', $project->t()?->title ?? __('Project'))
@section('description', \Illuminate\Support\Str::limit(strip_tags($project->t()?->description ?? ''), 155))
@section('og_image', $project->image ? asset('storage/'.$project->image) : '')
@section('content')
@php
    $locale = app()->getLocale();
    $tr = $project->t();
    $facts = array_filter([
        __('Location') => $project->location,
        __('Status') => $project->state ? ($project->state === 'ongoing' ? __('Under construction') : __(ucfirst($project->state))) : null,
        __('Area') => $project->area ? number_format((float) $project->area).' m²' : null,
        __('Bedrooms') => $project->beds,
        __('Bathrooms') => $project->baths,
        __('Prices from') => $project->price_starts_from,
    ]);
    $documents = collect($docs['floor_plan'] ?? [])->merge($docs['master_plan'] ?? [])->merge($docs['brochure'] ?? []);
@endphp
@include('partials.page-head', ['title' => $tr?->title ?? __('Project'), 'kicker' => $project->location ?: __('Project'), 'crumbs' => [[__('Projects'), route('projects.index', ['locale' => $locale])]]])

<section class="section">
    <div class="wrap split split--top">
        <div class="framed">@if($project->image)<img src="{{ asset('storage/'.$project->image) }}" alt="{{ $tr?->title }}" width="960" height="720" decoding="async" fetchpriority="high">@endif</div>
        <div>
            <span class="kicker">{{ __('About the project') }}</span>
            <div class="prose" style="font-size:1.05rem">{!! nl2br(e(trim(preg_replace('/\n\s*\n+/', "\n", str_replace("\r", '', strip_tags($tr?->description ?? '')))))) !!}</div>
            @if($facts)
                <dl class="facts">
                    @foreach($facts as $label => $value)<div class="fact"><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach
                </dl>
            @endif
            <div class="hero-actions" style="margin-top:0">
                <a class="btn" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Ask about this project') }}</a>
                <a class="btn btn--ghost" href="https://api.whatsapp.com/send?phone=+93777578657&amp;text={{ rawurlencode('Hello, I would like to know more about '.($tr?->title ?? 'your project')) }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </div>
        </div>
    </div>
</section>

@if($project->galleries->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Gallery') }}</span><h2>{{ __('A closer look') }}</h2></div></div>
        <div class="gallery">
            @foreach($project->galleries->take(10) as $image)
                <a href="{{ asset('storage/'.$image->image) }}" target="_blank" rel="noopener"><img src="{{ asset('storage/'.$image->image) }}" alt="{{ $tr?->title }} — {{ __('photo') }} {{ $loop->iteration }}" loading="lazy" decoding="async" width="800" height="600"></a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($units->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Apartments') }}</span><h2>{{ __('Choose your layout') }}</h2></div></div>
        <table class="units">
            <thead><tr><th scope="col">{{ __('Apartment') }}</th><th scope="col">{{ __('Bedrooms') }}</th><th scope="col">{{ __('Size') }}</th></tr></thead>
            <tbody>
                @foreach($units as $unit)
                    <tr>
                        <td>{{ $unit->name ?: __('Apartment') }}@if($unit->description)<p>{{ $unit->description }}</p>@endif</td>
                        <td>{{ $unit->bedrooms ? $unit->bedrooms.' '.($unit->bedrooms == 1 ? __('bedroom') : __('bedrooms')) : '—' }}</td>
                        <td>@if($unit->min_area){{ $unit->min_area }}@if($unit->max_area && $unit->max_area != $unit->min_area)–{{ $unit->max_area }}@endif m²@else — @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

@if($documents->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Downloads') }}</span><h2>{{ __('Plans and brochures') }}</h2></div></div>
        <div class="doc-list">
            @foreach($documents as $document)
                <a class="doc" href="{{ asset($document->file) }}" target="_blank" rel="noopener"><strong>{{ __(\Illuminate\Support\Str::headline($document->type)) }}</strong><span>{{ __('Open PDF') }}</span></a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featuredProjects->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><div><span class="kicker">{{ __('Keep exploring') }}</span><h2>{{ __('More projects') }}</h2></div><a class="link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('See all projects') }}</a></div>
        <div class="project-grid">@foreach($featuredProjects->take(3) as $project)@include('partials.heritage-project-card')@endforeach</div>
    </div>
</section>
@endif
@include('partials.contact-band')
@endsection
