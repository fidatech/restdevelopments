@extends('layouts.heritage')
@section('title', __('Careers'))
@section('description', __('Explore career opportunities with Rest Developments.'))
@section('content')
@php($openCount = $vacancies->filter(fn ($v) => ! $v->deadline || ! $v->deadline->endOfDay()->isPast())->count())
@include('partials.page-head', ['image' => asset('assets/img/brand/towers-park.webp'), 'title' => __('Build your career with Rest'), 'current' => __('Careers'), 'kicker' => __('Careers'), 'lead' => $openCount ? __('Open roles in engineering, sales and project management.') : __('There are no open roles right now. Send your CV to careers@restdevelopments.com and we will keep it on file.')])
<section class="section">
    <div class="wrap">
        <div class="roles">
            @forelse($vacancies as $job)
                @php($tr = $job->translation())
                @php($closed = $job->deadline && $job->deadline->endOfDay()->isPast())
                <article class="role{{ $closed ? ' is-closed' : '' }}">
                    <div>
                        <p class="role-meta">@if($closed)<span class="role-status">{{ __('Closed') }}</span>@endif{{ collect([$tr?->department, $tr?->location, $tr?->type])->filter()->implode(', ') }}</p>
                        <h2><a href="{{ route('careers.show', ['locale' => app()->getLocale(), 'slug' => $job->slug]) }}">{{ $tr?->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($tr?->description ?? '')), 150) }}</p>
                    </div>
                    <div class="role-side">
                        @if($job->deadline)<small>{{ $closed ? __('Closed on') : __('Apply by') }} {{ $job->deadline->translatedFormat('d M Y') }}</small>@endif
                        <span class="btn btn--line" aria-hidden="true">{{ __('View role') }}</span>
                    </div>
                </article>
            @empty
                <p class="empty">{{ __('There are no open roles right now. Send your CV to careers@restdevelopments.com and we will keep it on file.') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
