@extends('layouts.heritage')
@section('title', $blog->t()?->title ?? __('Journal'))
@section('og_type', 'article')
@section('description', \Illuminate\Support\Str::limit(strip_tags($blog->t()?->content ?? ''), 155))
@section('og_image', $blog->thumb_large ? asset('storage/'.$blog->thumb_large) : '')
@section('content')
<div class="page-intro"><div class="wrap"><span class="eyebrow">{{ $blog->created_at?->format('d M Y') }}</span><h1>{{ $blog->t()?->title }}</h1></div></div><article class="section"><div class="wrap prose">@if($blog->thumb_large)<img src="{{ asset('storage/'.$blog->thumb_large) }}" width="900" height="600" alt="{{ $blog->t()?->title }}" decoding="async">@endif<div>{!! $blog->t()?->content !!}</div><hr class="rule"><a class="text-link" href="{{ route('blog.index', ['locale'=>app()->getLocale()]) }}">← {{ __('Back to journal') }}</a></div></article>
@endsection
