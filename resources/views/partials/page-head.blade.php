<header class="page-head">
    @include('partials.brand-mark')
    <div class="wrap">
        <nav class="breadcrumb" aria-label="{{ __('Breadcrumb') }}">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('Home') }}</a>
            @foreach(($crumbs ?? []) as [$crumbLabel, $crumbUrl])
                <span aria-hidden="true">/</span><a href="{{ $crumbUrl }}">{{ $crumbLabel }}</a>
            @endforeach
            <span aria-hidden="true">/</span><span aria-current="page">{{ \Illuminate\Support\Str::limit($current ?? $title, 48) }}</span>
        </nav>
        @isset($kicker)<span class="kicker">{{ $kicker }}</span>@endisset
        <h1>{{ $title }}</h1>
        @if(!empty($lead))<p class="lead">{{ $lead }}</p>@endif
    </div>
</header>
