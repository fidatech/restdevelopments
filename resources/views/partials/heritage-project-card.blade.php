@php
    $tr = $project->t();
    $title = $tr?->title ?? __('Project');
    $state = $project->state;
    $headingTag = $headingTag ?? 'h3';
@endphp
<article class="card">
    <div class="card-media">
        @if($project->image)<img src="{{ media_url($project->image) }}" alt="" loading="lazy" decoding="async" width="800" height="600">@endif
        @if($state)<span class="badge {{ $state === 'ongoing' ? 'badge--gold' : '' }}">{{ $state === 'ongoing' ? __('Under construction') : __(ucfirst($state)) }}</span>@endif
    </div>
    @if($project->location)<p class="card-place">{{ $project->location }}</p>@endif
    <{{ $headingTag }}><a href="{{ route('projects.show', ['locale' => app()->getLocale(), 'slug' => $project->slug]) }}">{{ $title }}</a></{{ $headingTag }}>
    @if($excerpt = \Illuminate\Support\Str::limit(trim(strip_tags($tr?->description ?? '')), 120))<p>{{ $excerpt }}</p>@endif
    <span class="text-link" aria-hidden="true">{{ __('Explore') }}</span>
</article>
