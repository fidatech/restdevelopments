@extends('layouts.heritage')
@section('title', __('Latest launches'))
@section('description', __('Explore newly launched Rest Developments projects and apartments.'))
@section('content')
@include('partials.page-head', ['title' => __('Latest launches'), 'kicker' => __('New projects'), 'lead' => __('The newest developments now open for enquiries.')])
<section class="section">
    <div class="wrap">
        <div class="project-grid">
            @forelse($projects as $project)
                @include('partials.heritage-project-card')
            @empty
                <p class="empty">{{ __('No new launches right now. Contact us to hear first about the next one.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $projects->links() }}</div>
    </div>
</section>
@include('partials.contact-band')
@endsection
