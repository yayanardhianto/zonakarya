<footer class="footer-wrapper footer-layout1 overflow-hidden bg-smoke">
    <div class="container">
        <div class="footer-top space">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6">
                    <h2 class="footer-top-title">
                        {{ $setting->footer_title ?? __('Have Any Thing in your mind?') }}
                    </h2>
                </div>
                <div class="col-lg-5">
                    <div class="footer-top-wrap">
                        <p class="mb-30">
                            {{ $setting->footer_description ?? __('We are retail company engaged in sports equipment and gear, more commonly known by their store names, Sneakerzone and Jerseyzone') }}</p>
                        <a href="{{ route('contact') }}" class="btn">
                            <span class="link-effect text-uppercase">
                                <span class="effect-1">{{ $setting->footer_button_text ?? __('Contact Us') }}</span>
                                <span class="effect-1">{{ $setting->footer_button_text ?? __('Contact Us') }}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-menu-area">
            <div class="row gy-3 justify-content-between">
                <div class="col-xxl-6 col-lg-7">
                    <ul class="footer-menu-list">
                        @foreach (footerMenu() as $menu)
                            <li><a @if ($menu['open_new_tab']) target="_blank" @endif
                                    href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">
                                    <span class="link-effect">
                                        <span class="effect-1">{{ $menu['label'] }}</span>
                                        <span class="effect-1">{{ $menu['label'] }}</span>
                                    </span>
                                </a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-xxl-6 col-lg-5 text-lg-end">
                    <ul class="footer-menu-list">
                        @foreach (footerSecondMenu() as $menu)
                            <li><a @if ($menu['open_new_tab']) target="_blank" @endif
                                    href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">
                                    <span class="link-effect">
                                        <span class="effect-1">{{ $menu['label'] }}</span>
                                        <span class="effect-1">{{ $menu['label'] }}</span>
                                    </span>
                                </a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-wrap">
        <div class="container">
            <div class="row gy-3 justify-content-between align-items-center">
                <div class="col-md-6">
                    <div class="social-btn style3">
                        @foreach (socialLinks() as $social)
                            <a href="{{ $social?->link }}">
                                <span class="link-effect">
                                    <span class="effect-1"><img class="social-icon" src="{{ asset($social?->icon) }}" alt="{{$social?->link}}"></span>
                                    <span class="effect-1"><img class="social-icon" src="{{ asset($social?->icon) }}" alt="{{$social?->link}}"></span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 align-self-center text-lg-end">
                    <p class="copyright-text">{{ $setting?->copyright_text }}
                        <a href="{{ route('home') }}">{{ $setting?->app_name }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
