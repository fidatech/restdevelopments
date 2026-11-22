@extends('layouts.heritage')
@section('title', __('Latest launches'))
@section('description', __('Explore newly launched Rest Developments projects and apartments.'))
@section('content')
<div class="page-intro"><div class="wrap"><span class="eyebrow">{{ __('New places') }}</span><h1>{{ __('Latest launches') }}</h1><p class="lead">{{ __('Discover the next chapter in our developments.') }}</p></div></div>
<section class="section"><div class="wrap"><div class="cards">@forelse($projects as $project)@include('partials.heritage-project-card')@empty <p class="empty">{{ __('No launches are listed at the moment.') }}</p>@endforelse</div><div class="pagination">{{ $projects->links() }}</div></div></section>
@endsection
