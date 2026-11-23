@extends('layouts.heritage')
@section('title', __('News'))
@section('description', __('Company news, project updates and stories from Rest Developments.'))
@section('content')
@include('partials.page-head', ['image' => asset('assets/img/brand/courtyard.webp'), 'title' => __('News'), 'kicker' => __('The latest from Rest'), 'lead' => __('Construction progress, launches and events from our projects.')])
<section class="section">
    <div class="wrap">
        <div class="news-list">
            @forelse($blogs as $post)
                <article class="news-item">
                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->translatedFormat('d M Y') }}</time>
                    <div>
                        <h2><a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}">{{ $post->t()?->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit(trim(strip_tags($post->t()?->content ?? '')), 160) }}</p>
                    </div>
                    <div class="news-thumb">@if($post->thumb_large)<img src="{{ asset('storage/'.$post->thumb_large) }}" alt="" loading="lazy" width="640" height="400">@endif</div>
                </article>
            @empty
                <p class="empty">{{ __('No news yet. Follow us on social media for the latest updates.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $blogs->links() }}</div>
    </div>
</section>
@endsection
