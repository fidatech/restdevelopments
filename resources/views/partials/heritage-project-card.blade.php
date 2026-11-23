@php
    $tr = $project->t();
    $title = $tr?->title ?? __('Project');
    $url = route('projects.show', ['locale' => app()->getLocale(), 'slug' => $project->slug]);
    $state = $project->state;
@endphp
<article class="project">
    <div class="project-media">
        @if($project->image)
            <img src="{{ asset('storage/'.$project->image) }}" alt="" loading="lazy" decoding="async" width="800" height="600">
        @endif
        @if($state)<span class="project-status {{ $state === 'ongoing' ? 'is-ongoing' : '' }}">{{ $state === 'ongoing' ? __('Under construction') : __(ucfirst($state)) }}</span>@endif
    </div>
    <div class="project-body">
        @if($project->location)<span class="project-location">{{ $project->location }}</span>@endif
        <h3><a href="{{ $url }}">{{ $title }}</a></h3>
        @if($excerpt = \Illuminate\Support\Str::limit(strip_tags($tr?->description ?? ''), 120))<p>{{ $excerpt }}</p>@endif
        <span class="project-more" aria-hidden="true">{{ __('View project') }}</span>
    </div>
</article>
