@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['blog_page']['seo_title'])
@section('meta_description', $seo_setting['blog_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->blog_page_breadcrumb_image" :title="__('Blog')" />

    <!-- Main Area -->
    <section class="blog__area space">
        <div class="container">
            <div class="blog__inner-wrap">
                <div class="row">
                    <div class="col-70">
                        <div class="blog-post-wrap">
                            <div class="row gy-50 gutter-24">
                                @include('frontend.pages.blog.layouts.' . ($setting?->blog_layout ?? 'standard'))
                            </div>
                            @if ($blogs->hasPages())
                                {{ $blogs->onEachSide(0)->links('frontend.pagination.custom') }}
                            @endif
                        </div>
                    </div>
                    <div class="col-30">
                        <aside class="blog__sidebar">
                            <div class="sidebar__widget sidebar__widget-two">
                                <div class="sidebar__search">
                                    <form action="{{ route('blogs') }}">
                                        <input type="text" name="search" value="{{ request('search') ?? '' }}"
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
                                        <div class="sidebar__post-item">
                                            <div class="sidebar__post-thumb">
                                                <a href="{{ route('single.blog', $popular?->slug) }}"><img
                                                        src="{{ asset($popular?->image) }}" alt="{{ $popular?->title }}"></a>
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
