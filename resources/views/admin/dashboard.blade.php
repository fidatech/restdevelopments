@extends('admin.layouts.master')
@section('title', 'Dashboard')

@php
    $me = auth()->user();
    $now = now();
    $hour = (int) $now->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $initials = collect(preg_split('/\s+/', trim($me->name ?? 'A')))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    $count = fn (string $model, ?callable $scope = null) => rescue(fn () => ($scope ? $scope($model::query()) : $model::query())->count(), 0, false);

    $tiles = [
        ['Projects', 'building', $count(\App\Models\Project::class), route('admin.projects.index'), 'primary'],
        ['Live sliders', 'images', $count(\App\Models\Slider::class, fn ($q) => $q->where('status', 'published')), route('admin.sliders.index'), 'success'],
        ['Blog posts', 'newspaper', $count(\App\Models\Blog::class), route('admin.blogs.index'), 'warning'],
        ['Open vacancies', 'user-tie', $count(\App\Models\Vacancy::class), route('admin.careers.index'), 'danger'],
        ['Team members', 'users', $count(\App\Models\Team::class), route('admin.team.index'), 'primary'],
        ['Client logos', 'handshake', $count(\App\Models\Client::class), route('admin.clients.index'), 'success'],
    ];
    $projects = rescue(fn () => \App\Models\Project::with('translations')->orderByDesc('created_at')->take(6)->get(), collect(), false);
    $posts = rescue(fn () => \App\Models\Blog::with('translations')->orderByDesc('created_at')->take(5)->get(), collect(), false);
    $checks = collect();
    foreach ($projects as $p) {
        if (! $p->image) $checks->push(['Add a main image to “'.($p->t()?->title ?? 'a project').'”', url('admin/projects'), 'warning']);
        if (! $p->price_starts_from) $checks->push(['Add “Prices from” to “'.($p->t()?->title ?? 'a project').'”', url('admin/projects'), 'info']);
    }
    $checks = $checks->take(5);
@endphp

@section('content')
<x-page-header title="Dashboard" icon="gauge-high" subtitle="Rest Developments website">
    <a class="btn btn-outline-soft" href="{{ url('/') }}" target="_blank" rel="noopener"><x-icon name="globe" class="me-1" /> View website</a>
    <a class="btn btn-primary" href="{{ route('admin.projects.index') }}"><x-icon name="plus" class="me-1" /> New project</a>
</x-page-header>

<section class="dash-top" aria-label="Rest Developments">
    <div class="dash-id">
        <span class="dash-id-avatar" aria-hidden="true">{{ $initials }}</span>
        <div class="min-w-0">
            <p class="dash-id-greeting">{{ $greeting }},</p>
            <h2 class="dash-id-user">{{ $me->name }}</h2>
            <ul class="dash-id-facts">
                <li><x-icon name="envelope" /> {{ $me->email }}</li>
            </ul>
        </div>
    </div>
    <div class="dash-cal">
        <div class="dash-cal-now">
            <div class="dash-cal-date">
                <strong>{{ $now->format('j F Y') }}</strong>
                <span>{{ $now->format('l') }}</span>
            </div>
            <div class="dash-clock" dir="ltr"><x-icon name="clock" set="regular" /> <span>{{ $now->format('h:i') }}</span><span class="dash-clock-rest">{{ $now->format('A') }}</span></div>
        </div>
        <ol class="dash-week" aria-label="This week">
            @php $monday = $now->copy()->startOfWeek(\Carbon\Carbon::SATURDAY); @endphp
            @for ($i = 0; $i < 7; $i++)
                @php $day = $monday->copy()->addDays($i); @endphp
                <li class="{{ $day->isToday() ? 'is-today' : '' }} {{ $day->isFriday() ? 'is-off' : '' }}" @if ($day->isToday()) aria-current="date" @endif><span>{{ $day->format('D') }}</span><b>{{ $day->format('j') }}</b></li>
            @endfor
        </ol>
    </div>
    <div class="dash-site">
        <div class="dash-plan-head"><span>Website</span><span class="dash-plan-state">Online</span></div>
        <div class="dash-plan-days"><strong>restdevelopments.com</strong></div>
        <div class="dash-plan-foot"><span>English, Dari, Pashto, Uzbek</span><a href="{{ url('/') }}" target="_blank" rel="noopener">Open <x-icon name="arrow-up-right-from-square" /></a></div>
    </div>
</section>

<div class="dc-stats mb-3">
    @foreach ($tiles as [$label, $icon, $value, $url, $tone])
        <a class="dc-stat is-link" href="{{ $url }}">
            <span class="icon-soft {{ $tone }}"><x-icon :name="$icon" /></span>
            <div class="min-w-0"><div class="dc-stat-value">{{ number_format($value) }}</div><div class="dc-stat-label">{{ $label }}</div></div>
            <span class="dc-stat-go" aria-hidden="true"><x-icon name="arrow-right" /></span>
        </a>
    @endforeach
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-xl-7">
        <div class="card h-100 mb-0">
            <div class="card-header"><h2 class="h5 card-title mb-0">Latest projects</h2><a class="btn btn-sm btn-light-secondary" href="{{ route('admin.projects.index') }}">All projects</a></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 keep-table">
                    <thead><tr><th>Project</th><th>Location</th><th>Status</th><th class="text-end">Open</th></tr></thead>
                    <tbody>
                        @forelse ($projects as $p)
                            <tr>
                                <td><div class="d-flex align-items-center gap-2">@if ($p->image)<img class="recent-thumb" src="{{ asset(str_starts_with($p->image, 'storage/') ? $p->image : 'storage/'.$p->image) }}" alt="">@else<span class="recent-thumb d-inline-block"></span>@endif<span class="fw-semibold">{{ \Illuminate\Support\Str::limit($p->t()?->title, 48) }}</span></div></td>
                                <td>{{ $p->location ?: '—' }}</td>
                                <td>@if ($p->status)<span class="badge bg-success-subtle text-success-emphasis">{{ $p->state === 'ongoing' ? 'Under construction' : ucfirst($p->state ?? '') }}</span>@else<span class="badge bg-secondary-subtle text-secondary-emphasis">Hidden</span>@endif</td>
                                <td class="text-end"><div class="act-group"><x-act kind="view" :href="route('projects.show', ['locale' => 'en', 'slug' => $p->slug])" blank /><a class="btn btn-sm btn-light-secondary" href="{{ url('admin/projects/'.$p->id.'/gallery') }}">Gallery</a></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No projects yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="card h-100 mb-0 dc-wait">
            <div class="card-header"><h2 class="h5 card-title mb-0">To finish</h2>@if ($checks->count())<span class="badge bg-warning text-dark">{{ $checks->count() }}</span>@endif</div>
            <div class="card-body">
                @forelse ($checks as [$text, $url, $tone])
                    <a class="dc-wait-item" href="{{ $url }}"><span class="dc-wait-dot bg-{{ $tone }}"></span><span class="flex-grow-1">{{ $text }}</span><x-icon name="chevron-right" /></a>
                @empty
                    <div class="dc-clear"><span class="icon-soft success"><x-icon name="circle-check" /></span><div><strong>All clear</strong><span>Every recent project has an image and a price.</span></div></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="card h-100 mb-0">
            <div class="card-header"><h2 class="h5 card-title mb-0">Latest news</h2><a class="btn btn-sm btn-light-secondary" href="{{ route('admin.blogs.index') }}">All posts</a></div>
            <div class="card-body">
                @forelse ($posts as $post)
                    <div class="d-flex align-items-center gap-3 py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                        @if ($post->thumb_small || $post->thumb_large)<img class="recent-thumb" src="{{ asset('storage/'.($post->thumb_small ?: $post->thumb_large)) }}" alt="">@endif
                        <div class="min-w-0 flex-grow-1"><div class="fw-semibold text-truncate">{{ $post->t()?->title }}</div><small class="text-muted">{{ $post->created_at?->format('j M Y') }}</small></div>
                        <x-act kind="view" :href="route('blog.show', ['locale' => 'en', 'slug' => $post->slug])" blank />
                    </div>
                @empty
                    <div class="text-muted">No posts yet.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-5">
        <div class="card h-100 mb-0">
            <div class="card-header"><h2 class="h5 card-title mb-0">Quick actions</h2></div>
            <div class="card-body d-grid gap-2">
                <a class="quick-link" href="{{ route('admin.sliders.create') }}"><x-icon name="images" /> Add a home slider</a>
                <a class="quick-link" href="{{ route('admin.projects.index') }}"><x-icon name="building" /> Add or edit a project</a>
                <a class="quick-link" href="{{ route('admin.blogs.index') }}"><x-icon name="newspaper" /> Write a news post</a>
                <a class="quick-link" href="{{ route('admin.careers.index') }}"><x-icon name="user-tie" /> Post a vacancy</a>
                <a class="quick-link" href="{{ route('admin.contact.edit') }}"><x-icon name="address-book" /> Update contact details</a>
            </div>
        </div>
    </div>
</div>
@endsection
