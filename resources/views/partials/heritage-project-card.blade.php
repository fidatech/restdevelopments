@php($tr = $project->t())
<article class="card">
    <a href="{{ route('projects.show', ['locale'=>app()->getLocale(), 'slug'=>$project->slug]) }}" aria-label="{{ $tr?->title ?? __('View project') }}">
        <div class="card-media">@if($project->image)<img src="{{ asset('storage/'.$project->image) }}" alt="{{ $tr?->title ?? __('Project') }}" loading="lazy" decoding="async" width="640" height="480">@endif</div>
        <div class="card-body"><span class="eyebrow">{{ $project->location ?: __('Development') }}</span><h3>{{ $tr?->title ?? __('Project') }}</h3><p>{{ \Illuminate\Support\Str::limit(strip_tags($tr?->description ?? ''), 125) }}</p><span class="text-link">{{ __('Explore project') }} ↗</span></div>
    </a>
</article>
