@extends('layouts.heritage')
@section('title', __('Projects and apartments'))
@section('description', __('Discover Rest Developments projects and apartments in Afghanistan and Uzbekistan. Filter developments by location and status.'))
@section('content')
@include('partials.page-head', ['image' => asset('assets/img/brand/aerial.webp'), 'title' => __('Our projects'), 'kicker' => __('Kabul and Termiz'), 'lead' => __('Residences, offices, retail and lifestyle destinations, built to last.')])
<section class="section">
    <div class="wrap">
        <form class="filters" method="get" action="{{ route('projects.index', ['locale' => app()->getLocale()]) }}" role="search" aria-label="{{ __('Filter projects') }}">
            <label>{{ __('Keyword') }}<input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Project name') }}"></label>
            <label>{{ __('City') }}
                <select name="location">
                    <option value="">{{ __('All cities') }}</option>
                    @foreach(['Kabul', 'Termiz'] as $city)<option value="{{ $city }}" @selected(request('location') === $city)>{{ __($city) }}</option>@endforeach
                </select>
            </label>
            <label>{{ __('Status') }}
                <select name="state">
                    <option value="">{{ __('Any status') }}</option>
                    <option value="ongoing" @selected(request('state') === 'ongoing')>{{ __('Under construction') }}</option>
                    <option value="completed" @selected(request('state') === 'completed')>{{ __('Completed') }}</option>
                </select>
            </label>
            <button type="submit">{{ __('Show results') }}</button>
        </form>
        <div class="cards">
            @forelse($projects as $project)
                @include('partials.heritage-project-card', ['headingTag' => 'h2'])
            @empty
                <p class="empty">{{ __('No projects match these filters. Try another city or status.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $projects->links() }}</div>
    </div>
</section>
@include('partials.contact-band')
@endsection
