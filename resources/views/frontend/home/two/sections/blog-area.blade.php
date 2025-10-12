<section class="blog-area space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-6 col-lg-8">
                <div class="title-area text-center">
                    <h2 class="sec-title">{{ __('Artikel dan Berita Terbaru dari Kami') }}</h2>
                </div>
            </div>
        </div>
        <div class="row gy-30 justify-content-center">
            @foreach ($latest_blogs as $blog)
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('single.blog', $blog?->slug) }}" class="blog-card style2">
                    <div class="blog-img">
                        <img src="{{ asset($blog?->image) }}" alt="{{ $blog?->title }}">
                    </div>
                    <div class="blog-content">
                        <div class="post-meta-item blog-meta">
                            <span>{{ formattedDate($blog?->created_at) }}</span>
                            <span>{{ $blog?->category?->title }}</span>
                        </div>
                        <h3 class="blog-title">{{ $blog?->title }}</h3>
                        <span class="link-btn">
                            <span class="link-effect">
                                <span class="effect-1">{{ __('Read More') }}</span>
                                <span class="effect-1">{{ __('Read More') }}</span>
                            </span>
                            <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="arrow-left">
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>