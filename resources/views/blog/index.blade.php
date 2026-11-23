@extends('layouts.heritage')
@section('title', __('News'))
@section('description', __('Company news, project updates and stories from Rest Developments.'))
@section('content')
@include('partials.page-head', ['title' => __('News and project updates'), 'current' => __('News'), 'kicker' => __('News'), 'lead' => __('Construction progress, launches and stories from our developments.')])
<section class="section">
    <div class="wrap">
        <div class="journal">
            @forelse($blogs as $post)
                <article class="journal-item">
                    <time datetime="{{ $post->created_at?->toDateString() }}">{{ $post->created_at?->translatedFormat('d M Y') }}</time>
                    <div>
                        <h2 style="font-size:clamp(1.35rem,2vw,1.75rem)"><a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'slug' => $post->slug]) }}">{{ $post->t()?->title }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->t()?->content ?? ''), 160) }}</p>
                    </div>
                    <div class="journal-thumb">@if($post->thumb_large)<img src="{{ asset('storage/'.$post->thumb_large) }}" alt="" loading="lazy" width="400" height="267">@endif</div>
                </article>
            @empty
                <p class="empty">{{ __('No news yet. Follow us on social media for the latest updates.') }}</p>
            @endforelse
        </div>
        <div class="pagination">{{ $blogs->links() }}</div>
    </div>
</section>
@endsection
