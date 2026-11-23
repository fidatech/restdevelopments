@extends('layouts.heritage')
@section('title', __('Careers'))
@section('description', __('Explore career opportunities with Rest Developments.'))
@section('content')
@include('partials.page-head', ['image' => asset('assets/img/brand/towers-park.webp'), 'title' => __('Build your career with Rest'), 'current' => __('Careers'), 'kicker' => __('Careers'), 'lead' => __('Open roles in engineering, sales and project management.')])
<section class="section">
    <div class="wrap">
        <div class="roles">
            @forelse($vacancies as $job)
                @php($tr = $job->translation())
                <article class="role">
                    <div>
                        <p class="role-meta">{{ collect([$tr?->location, $tr?->type])->filter()->implode(', ') }}</p>
                        <h2><a href="{{ route('careers.show', ['locale' => app()->getLocale(), 'slug' => $job->slug]) }}">{{ $tr?->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($tr?->description ?? '')), 150) }}</p>
                    </div>
                    <span class="btn btn--line" aria-hidden="true">{{ __('View role') }}</span>
                </article>
            @empty
                <p class="empty">{{ __('There are no open roles right now. Send your CV to careers@restdevelopments.com and we will keep it on file.') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
