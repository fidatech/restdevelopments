<aside class="sidebar-area">

    {{-- Categories --}}
    <div class="widget widget_categories">
        <h3 class="widget_title">Post Categories</h3>
        <ul>
            @foreach($categories as $cat)
                <li>
                    <a href="{{ route('blog.index', [app()->getLocale(), 'category'=>$cat->id]) }}">
                        {{ $cat->t()?->title }} <span>({{ $cat->blogs_count }})</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Recent Posts --}}
    <div class="widget">
        <h3 class="widget_title">Recent Posts</h3>
        <div class="recent-post-wrap">
            @foreach($recent as $post)
                <div class="recent-post">
                    <div class="media-img">
                        <a href="{{ route('blog.show',[app()->getLocale(),$post->slug]) }}">
                            <img src="{{ asset('storage/'.$post->thumb_small) }}" alt="{{ $post->t()?->title }}">
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="post-title">
                            <a href="{{ route('blog.show',[app()->getLocale(),$post->slug]) }}" class="text-inherit">
                                {{ $post->t()?->title }}
                            </a>
                        </h4>
                        <div class="recent-post-meta">
                            <span><i class="far fa-calendar"></i>{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Tags (optional / static for now) --}}
    <div class="widget widget_tag_cloud">
        <h3 class="widget_title">Popular Tags</h3>
        <div class="tagcloud">
            <a href="#">Business</a>
            <a href="#">Modern</a>
            <a href="#">Luxury</a>
            <a href="#">Tips</a>
        </div>
    </div>

    {{-- Banner --}}
    <div class="widget widget_banner" data-bg-src="{{ asset('assets/img/widget/widget-banner.png') }}">
        <div class="widget-banner text-center">
            <h3 class="title">Need Help? We Are Here To Help You</h3>
            <div class="logo"><img src="{{ asset('assets/img/rest_logo.png') }}" style="height: 80px;" alt="logo"></div>
            <h4 class="subtitle">You Get Online support</h4>
            <h5 class="link"><a href="tel:256214203215">+256 214 203 215</a></h5>
        </div>
    </div>

</aside>
