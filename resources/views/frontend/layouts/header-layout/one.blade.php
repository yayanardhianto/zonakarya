@if (!$setting?->is_shop)
    <!-- Sidebar  -->
    @include('frontend.partials.sidebar-info')
@endif

<!-- Header Area -->
<header class="nav-header header-layout1">
    <div class="sticky-wrapper">
        <!-- Main Menu Area -->
        <div class="menu-area">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-between">
                    <div class="col-auto">
                        <div class="header-logo">
                            <a href="{{ route('home') }}"><img src="{{ asset($setting?->logo) }}"
                                    alt="{{ $setting?->app_name }}"></a>
                        </div>
                    </div>
                    <div class="col-auto ms-auto">
                        <nav class="main-menu d-none d-lg-inline-block">
                            @include('frontend.partials.main-menu')
                        </nav>
                        <div class="navbar-right d-inline-flex d-lg-none">


                            <button type="button" class="menu-toggle sidebar-btn" aria-label="hamburger">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </button>
                        </div>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <div class="header-button">


                            @auth('web')
                                <a href="{{ route('dashboard') }}" class="btn">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Dashboard') }}</span>
                                        <span class="effect-1">{{ __('Dashboard') }}</span>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ __('Sign In') }}</span>
                                        <span class="effect-1">{{ __('Sign In') }}</span>
                                    </span>
                                </a>
                            @endauth
                            @if (!$setting?->is_shop)
                                <button type="button" class="sidebar-btn sideMenuToggler">
                                    <span class="line"></span>
                                    <span class="line"></span>
                                    <span class="line"></span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
