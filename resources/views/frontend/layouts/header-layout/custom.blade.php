@if (!$setting?->is_shop)
    <!-- Sidebar  -->
    @include('frontend.partials.sidebar-info')
@endif

<!-- Header Area -->
<header class="header-with-topbar">
    <div class="header-top-bar top-bar-dark bg-dark">
        <div class="container-fluid">
            <div class="row h-45px align-items-center m-0">
                <div class="col-12 col-lg-7 fw-500 justify-content-lg-start justify-content-center">
                    <span class="me-25px fs-15 md-m-0">
                        <i class="feather icon-feather-phone-call text-base-color me-10px"></i><span class="text-light-gray">Phone: 1 800 222 000 - Any time 24/7</span>
                    </span>
                    <span class="d-xl-inline-block d-none fs-15"><i class="feather icon-feather-mail text-base-color me-10px"></i><a href="mailto:no-reply@domain.com" class="widget text-light-gray text-white-hover">no-reply@domain.com</a></span>
                </div>
                <div class="col-md-5 text-end d-none d-lg-flex fs-15">
                    <a href="http://www.facebook.com" target="_blank" class="me-25px lg-me-15px">Facebook</a>
                    <a href="http://www.twitter.com" target="_blank" class="me-25px lg-me-15px">Twitter</a>
                    <a href="https://in.pinterest.com/" target="_blank" class="me-25px lg-me-15px">Pinterest</a>
                    <a href="https://www.instagram.com" target="_blank">Instagram</a>
                </div>
            </div>
        </div>
    </div>
    <!-- start navigation -->
    <nav class="navbar navbar-expand-lg header-light bg-white header-reverse" data-header-hover="light">
        <div class="container-fluid"> 
            <div class="col-auto">
                <a class="navbar-brand" href="{{ route('home') }}"><img src="{{ asset($setting?->logo) }}"
                alt="{{ $setting?->app_name }}"></a>
                    <!-- <img src="images/demo-logistics-logo-black.png" data-at2x="images/demo-logistics-logo-black@2x.png" alt="" class="default-logo">
                    <img src="images/demo-logistics-logo-black.png" data-at2x="images/demo-logistics-logo-black@2x.png" alt="" class="alt-logo">
                    <img src="images/demo-logistics-logo-black.png" data-at2x="images/demo-logistics-logo-black@2x.png" alt="" class="mobile-logo"> -->
                </a>
            </div>
            <div class="col-auto menu-order left-nav">
                <button class="navbar-toggler float-start" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="demo-logistics.html" class="nav-link">Home</a></li> 
                        <li class="nav-item"><a href="demo-logistics-about-us.html" class="nav-link">About us</a></li>
                        <li class="nav-item dropdown dropdown-with-icon-style02"><a href="demo-logistics-our-services.html" class="nav-link">Our services</a>
                            <i class="fa-solid fa-angle-down dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"> 
                                <li><a href="demo-logistics-services-details.html"><i class="line-icon-Plane-2 align-middle text-base-color"></i>Air freight</a></li>
                                <li><a href="demo-logistics-services-details.html"><i class="line-icon-Road-3 align-middle text-base-color"></i>Road freight</a></li>
                                <li><a href="demo-logistics-services-details.html"><i class="line-icon-Ship-2 align-middle text-base-color"></i>Ocean freight</a></li>
                                <li><a href="demo-logistics-services-details.html"><i class="line-icon-Tram align-middle text-base-color"></i>Train freight</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a href="demo-logistics-latest-blog.html" class="nav-link">Latest blog</a></li>
                        <li class="nav-item"><a href="demo-logistics-contact-us.html" class="nav-link">Contact us</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-auto ms-auto ps-lg-0 d-none d-sm-flex"> 
                <div class="header-icon">
                    <div class="d-none d-xl-inline-block"><div class="fw-600"><a href="tel:1800222000" class="widget-text"><i class="feather icon-feather-phone-call me-10px"></i>1 800 222 000</a></div></div>
                    <div class="header-button ms-25px">
                        <a href="demo-logistics-contact-us.html" class="btn btn-small btn-base-color btn-hover-animation-switch btn-round-edge btn-box-shadow fw-700 ls-0px btn-icon-left">
                            <span> 
                                <span class="btn-text">Get a quote</span>
                                <span class="btn-icon"><i class="feather icon-feather-mail"></i></span>
                                <span class="btn-icon"><i class="feather icon-feather-mail"></i></span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- end navigation -->
</header>



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
