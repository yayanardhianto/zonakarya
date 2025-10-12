<div class="sidemenu-wrapper">
    <div class="sidemenu-content">
        <button class="closeButton sideMenuCls"><img src="{{ asset('frontend/images/close.svg') }}"></button>
        <div class="widget footer-widget">
            <div class="widget-about">
                <div class="footer-logo sidebar-logo">
                    <a href="{{route('home')}}"><img src="{{ asset($setting?->logo_white) }}" alt="{{$setting?->app_name}}"></a>
                </div>
                <p class="about-text">{{__('We are digital agency that helps businesses develop immersive and engaging')}}</p>
                <div class="sidebar-wrap">
                    <h6>{{$contactSection?->address}}</h6>
                </div>
                <div class="sidebar-wrap">
                    <h6><a href="tel:{{$contactSection?->phone}}">{{$contactSection?->phone}} </a></h6>
                    <h6><a href="mailto:{{$contactSection?->email}}">{{$contactSection?->email}}</a></h6>
                </div>
                <div class="social-btn style2">
                    @foreach (socialLinks() as $social)
                <a href="{{ $social?->link }}">
                    <span class="link-effect">
                        <span class="effect-1"><img class="social-icon color-white" src="{{ asset($social?->icon) }}"></span>
                        <span class="effect-1"><img class="social-icon color-white" src="{{ asset($social?->icon) }}"></span>
                    </span>
                </a>
            @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <a href="{{url('contact')}}" class="chat-btn gsap-magnetic">{{__("Let’s Talk with us")}}</a>
        </div>
    </div>
</div>