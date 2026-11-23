@php
    $heroImage = $image ?? asset('assets/img/brand/towers.webp');
@endphp
<header class="page-hero">
    <img src="{{ $heroImage }}" alt="" width="1920" height="1080" fetchpriority="high" decoding="async">
    <div class="wrap">
        <nav class="crumbs" aria-label="{{ __('Breadcrumb') }}">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('Home') }}</a>
            @foreach(($crumbs ?? []) as [$crumbLabel, $crumbUrl])
                <span aria-hidden="true">/</span><a href="{{ $crumbUrl }}">{{ $crumbLabel }}</a>
            @endforeach
            <span aria-hidden="true">/</span><span aria-current="page">{{ \Illuminate\Support\Str::limit($current ?? $title, 48) }}</span>
        </nav>
        @if(!empty($kicker))<span class="eyebrow">{{ $kicker }}</span>@endif
        <h1>{{ $title }}</h1>
        @if(!empty($lead))<p class="lead">{{ $lead }}</p>@endif
    </div>
</header>
