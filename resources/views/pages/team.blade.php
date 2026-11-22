@extends('layouts.base')
@section('title', __('Team'))

@section('content')
<!--============================== Breadcumb ==============================-->
<div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/about-us-breadcrumb.webp') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">{{ __('Team Members') }}</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li>{{ __('Team Members') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!--============================== Team Area ==============================-->
<section class="space">
    <div class="container z-index-common">
        <div class="row gy-30">
            @foreach($team as $member)
                <div class="col-lg-4 col-md-6">
                    <div class="th-team team-card style4">
                        <div class="img-wrap">
                            <div class="team-img">
                                <img src="{{ asset('storage/'.$member->image) }}" alt="{{ $member->t()?->name }}">
                            </div>
                            <div class="th-social-wrap">
                                <div class="th-social">
                                    @if($member->facebook) <a target="_blank" href="{{ $member->facebook }}"><i class="fab fa-facebook-f"></i></a>@endif
                                    @if($member->twitter)  <a target="_blank" href="{{ $member->twitter }}"><i class="fab fa-twitter"></i></a>@endif
                                    @if($member->linkedin) <a target="_blank" href="{{ $member->linkedin }}"><i class="fab fa-linkedin-in"></i></a>@endif
                                    @if($member->instagram)<a target="_blank" href="{{ $member->instagram }}"><i class="fab fa-instagram"></i></a>@endif
                                </div>
                                {{-- team-details route not implemented so just remove the arrow or keep anchor --}}
                                <a class="icon-btn" href="#"><img src="{{ asset('assets/img/icon/arrow-right.svg') }}" alt="img"></a>
                            </div>
                        </div>
                        <div class="team-card-content">
                            <div class="media-left">
                                <h3 class="box-title">{{ $member->t()?->name }}</h3>
                                <span class="team-desig">{{ $member->t()?->position }}</span>
                            </div>
                            @if($member->phone)
                                <a class="icon-btn" href="tel:{{ $member->phone }}"><img src="{{ asset('assets/img/icon/phone.svg') }}" alt="img"></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
