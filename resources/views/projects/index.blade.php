@extends('layouts.heritage')
@section('title', __('Projects and apartments'))
@section('description', __('Discover Rest Developments projects and apartments in Afghanistan and Uzbekistan. Filter developments by location and status.'))
@section('content')
@include('partials.page-head', ['title' => __('Places to call your own.'), 'current' => __('Projects'), 'kicker' => __('Our projects'), 'lead' => __('Residential and mixed-use developments for living, working and meeting the neighbours.')])
<section class="section">
    <div class="wrap">
        <form class="filters" method="get" action="{{ route('projects.index', ['locale' => app()->getLocale()]) }}" role="search" aria-label="{{ __('Filter projects') }}">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search by project name') }}" aria-label="{{ __('Search by project name') }}">
            <input type="text" name="location" value="{{ request('location') }}" placeholder="{{ __('City or area') }}" aria-label="{{ __('City or area') }}">
            <select name="state" aria-label="{{ __('Project status') }}">
                <option value="">{{ __('All projects') }}</option>
                <option value="ongoing" @selected(request('state') === 'ongoing')>{{ __('Under construction') }}</option>
                <option value="completed" @selected(request('state') === 'completed')>{{ __('Completed') }}</option>
            </select>
            <button class="btn" type="submit">{{ __('Show projects') }}</button>
        </form>
        <div class="project-grid">
            @forelse($projects as $project)
                @include('partials.heritage-project-card')
            @empty
                <p class="empty">{{ __('No projects match these filters. Try another city or clear the status.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $projects->links() }}</div>
    </div>
</section>
@include('partials.contact-band')
@endsection
