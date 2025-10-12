<!DOCTYPE html>
@if (session()->get('text_direction') == 'rtl')
    <html class="no-js" lang="en" dir="rtl">
@else
    <html class="no-js" lang="en">
@endif

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('meta_title', $setting?->app_name)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="robots"
        content="{{ $setting?->search_engine_indexing === 'inactive' ? 'noindex, nofollow' : 'index, follow' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset($setting?->favicon) }}">
    <meta name="theme-color" content="#ffffff">
    @stack('custom_meta')

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset($setting?->favicon) }}">
    @include('frontend.layouts.styles02')
    @include('frontend.layouts.header-scripts')
</head>

@php
    $theme_name = session()->has('demo_theme') ? session()->get('demo_theme') : DEFAULT_HOMEPAGE;
@endphp

<body class="{{ isRoute('home', "home_{$theme_name}") }}" data-mobile-nav-style="classic">
    @if ($setting?->googel_tag_status == 'active')
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting?->googel_tag_id }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    
    <!-- Mobile Menu  -->
    @include('frontend.partials.mobile-menu')

    <!-- header-top-area start-->
    <!-- <div class="wsus_topbar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 col-xl-6 d-none d-lg-block">
                    @if($contactSection?->address)
                    <div class="tg-header-top__info">
                        <ul>
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <a class="my-2" href="javascript::void">{{ $contactSection?->address }}</a>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="col-lg-5 col-xl-6">
                    <ul class="tg-header-top__social text-end header_language">
                        @if (allLanguages()?->where('status', 1)->count() > 1)
                            <li class="language-select-item select_item">
                                <form id="setLanguageHeader" action="{{ route('set-language') }}" method="get">
                                    <select class="select_js" name="code">
                                        @forelse (allLanguages()?->where('status', 1) as $language)
                                            <option value="{{ $language->code }}"
                                                {{ getSessionLanguage() == $language->code ? 'selected' : '' }}>
                                                {{ $language->name }}
                                            </option>
                                        @empty
                                            <option value="en"
                                                {{ getSessionLanguage() == 'en' ? 'selected' : '' }}>
                                                {{ __('English') }}
                                            </option>
                                        @endforelse
                                    </select>
                                </form>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div> -->
    <!-- header-top-area end-->
    
    <!-- header-area -->
    @yield('header')
    <!-- main-area -->
    @yield('contents')
    <!-- footer-area -->
    @yield('footer')
    <!--Preloader-->
    @if ($setting?->preloader_status == 1)
        <div class="preloader">
            <div class="preloader-inner"><span></span><span></span><span></span><span></span></div>
        </div>
    @endif
    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <!-- JS here -->
    @include('frontend.layouts.scripts02')
</body>

</html>
