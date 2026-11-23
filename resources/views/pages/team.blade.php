@extends('layouts.heritage')
@section('title', __('Our people'))
@section('description', __('Meet the people behind Rest Developments and our projects.'))
@section('content')
@include('partials.page-head', ['title' => __('The team behind our projects.'), 'current' => __('Our people'), 'kicker' => __('Our people'), 'lead' => __('Engineers, architects, sales and site teams working across Kabul, Termiz and Dubai.')])
<section class="section">
    <div class="wrap">
        <div class="people">
            @forelse($team as $member)
                <article class="person"><div class="person-photo">@if($member->image)<img src="{{ asset('storage/'.$member->image) }}" alt="{{ $member->t()?->name }}" loading="lazy" width="480" height="600">@endif</div><h2 class="h3" style="font-size:1.45rem;margin-bottom:.2rem">{{ $member->t()?->name }}</h2><p>{{ $member->t()?->position }}</p></article>
            @empty
                <p class="empty">{{ __('Team profiles will be added soon.') }}</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
