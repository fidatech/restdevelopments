@extends('layouts.heritage')
@section('title', __('Careers'))
@section('description', __('Explore career opportunities with Rest Developments.'))
@section('content')
@include('partials.page-head', ['title' => __('Build your career with Rest.'), 'current' => __('Careers'), 'kicker' => __('Careers'), 'lead' => __('Open roles in engineering, sales and project management.')])
<section class="section">
    <div class="wrap">
        <div class="roles">
            @forelse($vacancies as $job)
                @php($tr = $job->translation())
                <article class="role">
                    <div>
                        <div class="role-meta">{{ collect([$tr?->location, $tr?->type])->filter()->implode(', ') }}</div>
                        <h2 style="font-size:clamp(1.5rem,2.4vw,2rem)"><a href="{{ route('careers.show', ['locale' => app()->getLocale(), 'slug' => $job->slug]) }}">{{ $tr?->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($tr?->description ?? ''), 150) }}</p>
                    </div>
                    <span class="project-more" aria-hidden="true">{{ __('View role') }}</span>
                </article>
            @empty
                <p class="empty">{{ __('There are no open roles right now. Send your CV to careers@restdevelopments.com and we will keep it on file.') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
