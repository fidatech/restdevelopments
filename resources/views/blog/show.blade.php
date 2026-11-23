@extends('layouts.heritage')
@section('title', $blog->t()?->title ?? __('News'))
@section('og_type', 'article')
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags($blog->t()?->content ?? '')), 155))
@section('og_image', $blog->thumb_large ? asset('storage/'.$blog->thumb_large) : '')
@section('content')
@include('partials.page-head', ['image' => $blog->thumb_large ? asset('storage/'.$blog->thumb_large) : asset('assets/img/brand/courtyard.webp'), 'title' => $blog->t()?->title ?? __('News'), 'kicker' => $blog->created_at?->translatedFormat('d F Y'), 'crumbs' => [[__('News'), route('blog.index', ['locale' => app()->getLocale()])]]])
<article class="section">
    <div class="wrap">
        <div class="prose" style="margin-inline:auto">
            {!! $blog->t()?->content !!}
            <p style="margin-top:3rem"><a class="text-link" href="{{ route('blog.index', ['locale' => app()->getLocale()]) }}">{{ __('Back to all news') }}</a></p>
        </div>
    </div>
</article>
@endsection
