@extends('frontend.layouts.master')

@section('meta_title', $blog?->title . ' || ' . $setting->app_name)
@section('meta_description', $blog?->seo_description)

@push('custom_meta')
    <meta property="og:title" content="{{ $blog?->seo_title }}" />
    <meta property="og:description" content="{{ $blog?->seo_description }}" />
    <meta property="og:image" content="{{ asset($blog?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endpush

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- breadcrumb-area -->
    <x-breadcrumb-two :title="$blog?->title" :links="[['url' => route('home'), 'text' => __('Home')], ['url' => route('blogs'), 'text' => __('Blog')]]" />

    <!-- Main Area -->
    <section class="blog__details-area space">
        <div class="container">
            <div class="blog__inner-wrap">
                <div class="row">
                    <div class="col-70">
                        <div class="blog__details-wrap">
                            <div class="blog__details-thumb">
                                <img src="{{ asset($blog?->image) }}" alt="{{ $blog?->title }}">
                            </div>
                            <div class="blog__details-content details-text">
                                <div class="blog-post-meta">
                                    <ul class="list-wrap p-0">
                                        <li>{{ formattedDate($blog?->created_at) }}</li>
                                        <li>
                                            <a
                                                href="{{ route('blogs', ['category' => $blog?->category?->slug]) }}">{{ $blog?->category?->title }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:;">{{ __('by') }} {{ $blog?->admin?->name }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <h2 class="title">{{ $blog?->title }}</h2>
                                <div class="blog-post-description">
                                    {!! clean(replaceImageSources($blog?->description)) !!}
                                </div>

                                <div class="blog__details-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="post-tags">
                                                <ul class="list-wrap p-0">
                                                    {!! $tagString !!}
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="post-share">
                                                <h5 class="title">{{ __('Share') }}:</h5>
                                                <div class="social-btn style3 justify-content-md-end">
                                                    <a class="share-social" href="{{ route('single.blog', $blog?->slug) }}"
                                                        data-platform="facebook">
                                                        <span class="link-effect">
                                                            <span class="effect-1"><i class="fab fa-facebook"></i></span>
                                                            <span class="effect-1"><i class="fab fa-facebook"></i></span>
                                                        </span>
                                                    </a>
                                                    <a class="share-social" href="{{ route('single.blog', $blog?->slug) }}"
                                                        data-platform="linkedin">
                                                        <span class="link-effect">
                                                            <span class="effect-1"><i class="fab fa-linkedin"></i></span>
                                                            <span class="effect-1"><i class="fab fa-linkedin"></i></span>
                                                        </span>
                                                    </a>
                                                    <a class="share-social" href="{{ route('single.blog', $blog?->slug) }}"
                                                        data-platform="twitter">
                                                        <span class="link-effect">
                                                            <span class="effect-1"><i class="fab fa-twitter"></i></span>
                                                            <span class="effect-1"><i class="fab fa-twitter"></i></span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inner__page-nav mt-20 mb-n1">
                                    <a href="{{ $prevPost ? route('single.blog', $prevPost?->slug) : 'javascript:;' }}"
                                        class="nav-btn {{ $prevPost ? '' : 'disabled' }}">
                                        <i class="fa fa-arrow-left"></i> <span><span class="link-effect">
                                                <span class="effect-1">{{ __('Previous Post') }}</span>
                                                <span class="effect-1">{{ __('Previous Post') }}</span>
                                            </span></span>
                                    </a>
                                    <a href="{{ $nextPost ? route('single.blog', $nextPost?->slug) : 'javascript:;' }}"
                                        class="nav-btn {{ $nextPost ? '' : 'disabled' }}"><span><span class="link-effect">
                                                <span class="effect-1">{{ __('Next Post') }}</span>
                                                <span class="effect-1">{{ __('Next Post') }}</span>
                                            </span></span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="comments-wrap space-top">
                                <h3 class="comments-wrap-title">({{ $blog?->comments_count }}) {{ __('Comments') }}</h3>
                                <div class="latest-comments">
                                    <ul class="list-wrap">
                                        @foreach ($comments as $comment)
                                            <x-blog-comment :comment="$comment" :slug="$blog->slug" />
                                        @endforeach
                                        @if ($comments->hasPages())
                                            {{ $comments->onEachSide(0)->links('frontend.pagination.custom') }}
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @auth('web')
                                <div class="comment-respond">
                                    <h3 class="comment-reply-title">{{ __('Leave a Reply') }}</h3>
                                    <form action="{{ route('blog.comment.store', $blog->slug) }}" method="post"
                                        class="comment-form">
                                        <p class="comment-notes">
                                            {{ __('Share your thoughts on this post! Your insights and feedback are welcome.') }}
                                        </p>
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <textarea name="comment" placeholder="{{ __('Write your comment') }}*" class="form-control style-border style2"></textarea>
                                                </div>
                                            </div>
                                            @if ($setting->recaptcha_status == 'active')
                                                <div class="col-lg-12">
                                                    <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-btn col-12">
                                            <button type="submit" class="btn mt-25">
                                                <span class="link-effect text-uppercase">
                                                    <span class="effect-1">{{ __('Post Comment') }}</span>
                                                    <span class="effect-1">{{ __('Post Comment') }}</span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <div class="col-30">
                        <aside class="blog__sidebar">
                            <div class="sidebar__widget sidebar__widget-two">
                                <div class="sidebar__search">
                                    <form action="{{ route('blogs') }}">
                                        <input name="search" value="{{ request('search') ?? '' }}"
                                            placeholder="{{ __('Search') }}">
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M19.0002 19.0002L14.6572 14.6572M14.6572 14.6572C15.4001 13.9143 15.9894 13.0324 16.3914 12.0618C16.7935 11.0911 17.0004 10.0508 17.0004 9.00021C17.0004 7.9496 16.7935 6.90929 16.3914 5.93866C15.9894 4.96803 15.4001 4.08609 14.6572 3.34321C13.9143 2.60032 13.0324 2.01103 12.0618 1.60898C11.0911 1.20693 10.0508 1 9.00021 1C7.9496 1 6.90929 1.20693 5.93866 1.60898C4.96803 2.01103 4.08609 2.60032 3.34321 3.34321C1.84288 4.84354 1 6.87842 1 9.00021C1 11.122 1.84288 13.1569 3.34321 14.6572C4.84354 16.1575 6.87842 17.0004 9.00021 17.0004C11.122 17.0004 13.1569 16.1575 14.6572 14.6572Z"
                                                    stroke="currentcolor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="sidebar__widget">
                                <h4 class="sidebar__widget-title">{{ __('Categories') }}</h4>
                                <div class="sidebar__cat-list">
                                    <ul class="list-wrap">
                                        @foreach ($categories as $category)
                                            <li><a href="{{ route('blogs', ['category' => $category?->slug]) }}">{{ $category?->translation?->title }}
                                                    ({{ $category->posts_count ?? 0 }})
                                                </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="sidebar__widget">
                                <h4 class="sidebar__widget-title">{{ __('Popular Posts') }}</h4>
                                <div class="sidebar__post-list">
                                    @foreach ($popular_blogs as $popular)
                                        @continue($blog?->slug == $popular?->slug)
                                        <div class="sidebar__post-item">
                                            <div class="sidebar__post-thumb">
                                                <a href="{{ route('single.blog', $popular?->slug) }}"><img
                                                        src="{{ asset($popular?->image) }}"
                                                        alt="{{ $popular?->title }}"></a>
                                            </div>
                                            <div class="sidebar__post-content">
                                                <h5 class="title"><a
                                                        href="{{ route('single.blog', $popular?->slug) }}">{{ Str::limit($popular?->title, 30, '...') }}</a>
                                                </h5>
                                                <span class="date"><i
                                                        class="flaticon-time"></i>{{ formattedDate($popular?->created_at) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($topTags)
                                <div class="sidebar__widget">
                                    <h4 class="sidebar__widget-title">{{ __('Tags') }}</h4>
                                    <div class="sidebar__tag-list">
                                        <ul class="list-wrap">
                                            @foreach ($topTags as $key => $tag)
                                                <li class="text-capitalize"><a
                                                        href="{{ route('blogs', ['tag' => $key]) }}">{{ $key }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
