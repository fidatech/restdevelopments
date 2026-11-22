@extends('layouts.base')

@section('title', $blog->t()?->title)

@section('extra-css')
<style>
    /* 1. CKEditor Content Reset & Image Handling */
    .blog-details-content {
        color: #333;
        line-height: 1.8;
        font-size: 17px;
    }

    /* Fix for Base64/Blob Images and Resizing */
    .blog-details-content figure.image {
        margin: 20px auto;
        display: table;
        clear: both;
        max-width: 100%;
    }

    .blog-details-content figure.image img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        height: auto;
        border-radius: 8px; /* Optional: matches your luxury theme */
    }

    /* Support for Alignment (Side images) */
    .blog-details-content figure.image-style-side {
        float: right;
        margin-left: 20px;
        max-width: 50%;
    }

    .blog-details-content figure.image-style-align-left {
        float: left;
        margin-right: 20px;
    }

    /* Support for Resizing (CKEditor adds width style to the figure) */
    .blog-details-content figure[style] {
        max-width: 100%;
    }

    /* Clean up floats */
    .blog-details-content::after {
        content: "";
        display: block;
        clear: both;
    }
</style>
@endsection

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ asset('storage/'.$blog->thumb_large) }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">{{ $blog->t()?->title }}</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Blog Details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="th-blog-wrapper blog-details overflow-hidden space-top space-extra-bottom bg-luxury">
    <div class="container">
        <div class="row gx-30">
            <div class="col-xxl-8 col-lg-7">
                <div class="th-blog blog-single mb-0">
                    <div class="blog-img">
                        <img src="{{ asset('storage/'.$blog->thumb_large) }}" alt="{{ $blog->t()?->title }}">
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="far fa-clock"></i> {{ $blog->created_at->format('d M, Y') }}</span>
                        </div>
                        <h2 class="blog-title">{{ $blog->t()?->title }}</h2>

                        {{-- FIX: Removed e() and nl2br to allow HTML rendering --}}
                        <div class="blog-details-content">
                            {!! $blog->t()?->content !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 col-lg-5">
                @include('blog.blog-sidebar', ['recent' => $recent])
            </div>
        </div>
    </div>
</section>
@endsection
