@extends('layouts.base')

@section('title','Blog')

@section('content')
<!--============================== Breadcumb ==============================-->
<div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Blog Post</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li>Blog</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!--============================== Blog Area ==============================-->
<section class="th-blog-wrapper space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-8 col-lg-7">

                @foreach($blogs as $blog)
                <div class="th-blog blog-single has-post-thumbnail">
                    <div class="blog-img">
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <img src="{{ asset('storage/'.$blog->thumb_large) }}" alt="{{ $blog->t()?->title }}">
                        </a>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <span><i class="far fa-clock"></i>{{ $blog->created_at->format('d M, Y') }}</span>
                        </div>
                        <h2 class="blog-title">
                            <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->t()?->title }}</a>
                        </h2>
                        <p class="blog-text">{!! \Str::limit($blog->t()?->content, 170) !!}</p>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="th-btn style-border2 th-btn-icon">Read More</a>
                    </div>
                </div>
                @endforeach

                <div class="th-pagination">
                    {{ $blogs->links() }}
                </div>
            </div>

            <!-- sidebar -->
            <div class="col-xxl-4 col-lg-5">
                @include('blog.blog-sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
