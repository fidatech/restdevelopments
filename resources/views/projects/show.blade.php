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
        __('Location') => $project->location,
        __('Status') => $project->state ? ($project->state === 'ongoing' ? __('Under construction') : __(ucfirst($project->state))) : null,
        __('Area') => $project->area ? number_format((float) $project->area).' m²' : null,
        __('Bedrooms') => $project->beds,
        __('Bathrooms') => $project->baths,
        __('Prices from') => $project->price_starts_from,
    ]);
    $documents = collect($docs['floor_plan'] ?? [])->merge($docs['master_plan'] ?? [])->merge($docs['brochure'] ?? []);
    $description = trim(preg_replace('/\n\s*\n+/', "\n", str_replace("\r", '', strip_tags($tr?->description ?? ''))));
    $whatsapp = 'https://api.whatsapp.com/send?phone=+93777578657&text='.rawurlencode('Hello, I would like to know more about '.($tr?->title ?? 'your project'));
@endphp
@include('partials.page-head', ['image' => $storageUrl($project->image) ?: asset('assets/img/brand/towers.webp'), 'title' => $tr?->title ?? __('Project'), 'kicker' => $project->location, 'crumbs' => [[__('Projects'), route('projects.index', ['locale' => $locale])]]])

<section class="section">
    <div class="wrap project-intro">
        <div>
            <span class="eyebrow">{{ __('About the project') }}</span>
            <div class="prose">{!! nl2br(e($description)) !!}</div>
        </div>
        <aside class="facts-wrap">
            <h2>{{ __('Key facts') }}</h2>
            @if($facts)
                <dl class="facts">@foreach($facts as $label => $value)<div class="fact"><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>@endforeach</dl>
            @endif
            <div class="actions">
                <a class="btn btn--green" href="{{ route('contact', ['locale' => $locale]) }}">{{ __('Register interest') }}</a>
                <a class="btn btn--whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer">@include('partials.social-icon', ['name' => 'whatsapp']) WhatsApp</a>
            </div>
        </aside>
    </div>
</section>

@if($project->galleries->isNotEmpty())
<section class="section section--mist">
    <div class="wrap">
        <div class="section-head"><div><span class="eyebrow">{{ __('Gallery') }}</span><h2>{{ __('A closer look') }}</h2></div></div>
        <div class="gallery">
            @foreach($project->galleries->take(9) as $image)
                @php($src = $storageUrl($image->image))
                <a href="{{ $src }}" target="_blank" rel="noopener"><img src="{{ $src }}" alt="{{ $tr?->title }}, {{ __('photo') }} {{ $loop->iteration }}" loading="lazy" decoding="async" width="800" height="600"></a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($units->isNotEmpty())
<section class="section">
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

@if($documents->isNotEmpty())
<section class="section section--mist">
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

@if($featuredProjects->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="section-head"><h2>{{ __('More projects') }}</h2><a class="text-link" href="{{ route('projects.index', ['locale' => $locale]) }}">{{ __('View all projects') }} @include('partials.arrow')</a></div>
        <div class="cards">@foreach($featuredProjects->take(3) as $project)@include('partials.heritage-project-card')@endforeach</div>
    </div>
</section>
@endif
@include('partials.contact-band')
@endsection
