@extends('layouts.heritage')
@section('title', $blog->t()?->title ?? __('News'))
@section('og_type', 'article')
@section('description', \Illuminate\Support\Str::limit(strip_tags($blog->t()?->content ?? ''), 155))
@section('og_image', $blog->thumb_large ? asset('storage/'.$blog->thumb_large) : '')
@section('content')
@include('partials.page-head', ['title' => $blog->t()?->title ?? __('News'), 'kicker' => $blog->created_at?->translatedFormat('d F Y'), 'crumbs' => [[__('News'), route('blog.index', ['locale' => app()->getLocale()])]]])
<article class="section">
    <div class="wrap">
        <div class="prose" style="margin-inline:auto">
            @if($blog->thumb_large)<figure class="article-cover" style="margin-inline:0"><img src="{{ asset('storage/'.$blog->thumb_large) }}" width="1200" height="675" alt="" decoding="async"></figure>@endif
            {!! $blog->t()?->content !!}
            <p style="margin-top:3rem"><a class="link" href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}">{{ __('Back to all news') }}</a></p>
        </div>
    </div>
</article>
@endsection
