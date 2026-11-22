@extends('layouts.heritage')
@section('title', __('Our people'))
@section('description', __('Meet the people behind Rest Developments and our projects.'))
@section('content')
<div class="page-intro"><div class="wrap"><span class="eyebrow">{{ __('Our people') }}</span><h1>{{ __('The team behind the places.') }}</h1><p class="lead">{{ __('Meet the people shaping our developments.') }}</p></div></div><section class="section"><div class="wrap"><div class="cards">@forelse($team as $member)<article class="card"><div class="card-media">@if($member->image)<img src="{{ asset('storage/'.$member->image) }}" alt="{{ $member->t()?->name }}" loading="lazy" width="640" height="480">@endif</div><div class="card-body"><h2 style="font-size:1.6rem">{{ $member->t()?->name }}</h2><p>{{ $member->t()?->position }}</p></div></article>@empty<p class="empty">{{ __('Team information is coming soon.') }}</p>@endforelse</div></div></section>
@endsection
