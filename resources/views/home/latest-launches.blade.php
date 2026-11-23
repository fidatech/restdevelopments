@extends('layouts.heritage')
@section('title', __('New launches'))
@section('description', __('Explore newly launched Rest Developments projects and apartments.'))
@section('content')
@include('partials.page-head', ['image' => asset('assets/img/brand/courtyard.webp'), 'title' => __('New launches'), 'kicker' => __('Now open for enquiries'), 'lead' => __('The newest Rest Developments projects in Kabul and Termiz.')])
<section class="section">
    <div class="wrap">
        <div class="cards">
            @forelse($projects as $project)
                @include('partials.heritage-project-card', ['headingTag' => 'h2'])
            @empty
                <p class="empty">{{ __('No new launches right now. Contact us to hear about the next one first.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $projects->links() }}</div>
    </div>
</section>
@include('partials.contact-band')
@endsection
