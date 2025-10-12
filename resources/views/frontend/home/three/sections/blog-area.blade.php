<section class="blog-area-3 space">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xxl-4 col-xl-5 position-relative">
                <div class="sec_title_static">
                    <div class="sec_title_wrap">
                        <div class="title-area">
                            <h2 class="sec-title">{{ __('Read Our Articles and News') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-7 col-xl-7">
                <div class="blog-grid-static-wrap">
                    @foreach ($latest_blogs as $blog)
                        <div class="blog-grid-static">
                            <div class="blog-grid">
                                <div class="blog-img">
                                    <a href="{{ route('single.blog', $blog?->slug) }}">
                                        <img src="{{ asset($blog?->image) }}" alt="{{ $blog?->title }}">
                                    </a>
                                </div>
                                <div class="blog-content">
                                    <div class="post-meta-item blog-meta">
                                        <a href="javascript:;">{{ formattedDate($blog?->created_at) }}</a>
                                        <a
                                            href="{{ route('blogs', ['category' => $blog?->category?->slug]) }}">{{ $blog?->category?->title }}</a>
                                    </div>
                                    <h3 class="blog-title"><a
                                            href="{{ route('single.blog', $blog?->slug) }}">{{ $blog?->title }}</a></h3>
                                    <a href="{{ route('single.blog', $blog?->slug) }}" class="link-btn">
                                        <span class="link-effect">
                                            <span class="effect-1">{{ __('Read More') }}</span>
                                            <span class="effect-1">{{ __('Read More') }}</span>
                                        </span>
                                        <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="arrow-left">
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
