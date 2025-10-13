<div class="mobile-menu-wrapper">
    <div class="mobile-menu-area">
        <button class="menu-toggle"><i class="fas fa-times"></i></button>
        <div class="mobile-logo" style="max-width: 200px;">
            <a href="{{ route('home') }}"><img src="{{ asset($setting?->logo) }}" alt="{{ $setting?->app_name }}"></a>
        </div>
        <div class="mobile-menu">
            <ul>
                @foreach (mainMenu() as $menu)
                    @php
                        $is_child = !empty($menu['child']);
                        $is_home = $menu['link'] == '/';
                    @endphp

                    @if ($is_home && $setting?->show_all_homepage == 1)
                        <li class="menu-item-has-children">
                            <a href="{{ route('home') }}" class="text-uppercase">{{ __('Home') }}</a>
                            @php
                                $is_homepage = url()->current() == url('/');
                            @endphp

                            <ul class="sub-menu">
                                @foreach (App\Enums\ThemeList::themes() as $theme)
                                    <li
                                        class="{{ session()->get('demo_theme', DEFAULT_HOMEPAGE) == $theme?->name && $is_homepage ? 'active' : '' }}">
                                        <a href="{{ route('change-theme', $theme?->name) }}">{{ $theme?->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ $is_child ? 'menu-item-has-children' : '' }}">
                            <a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                                {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }} class="text-uppercase">
                                {{ $menu['label'] }}
                            </a>

                            @if ($is_child)
                                <ul class="sub-menu">
                                    @foreach ($menu['child'] as $child)
                                        <x-child-menu :menu="$child" />
                                    @endforeach
                                </ul>
                            @endif
                            
                        </li>
                    @endif
                @endforeach
            </ul>

            @auth('web')
                @php
                    $user = auth('web')->user();
                    $hasApplications = $user->applications()->exists();
                @endphp
            
                @if($hasApplications)
                <a href="{{ route('applicant.status') }}" class="btn btn-primary w-75">
                    <span class="link-effect text-uppercase">
                        <span class="effect-1">{{ __('LamaranKerja Saya') }}</span>
                        <span class="effect-1">{{ __('LamaranKerja Saya') }}</span>
                    </span>
                </a>
                <br>
                <a href="{{ route('dashboard') }}" class="btn mt-3 w-75">
                    <span class="link-effect text-uppercase">
                        <span class="effect-1">{{ __('Dashboard') }}</span>
                        <span class="effect-1">{{ __('Dashboard') }}</span>
                    </span>
                </a>

                @else
                <a href="{{ route('dashboard') }}" class="btn">
                    <span class="link-effect text-uppercase">
                        <span class="effect-1">{{ __('Dashboard') }}</span>
                        <span class="effect-1">{{ __('Dashboard') }}</span>
                    </span>
                </a>
                @endif
            @else
                <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <span class="link-effect text-uppercase">
                        <span class="effect-1">{{ __('Sign In') }}</span>
                        <span class="effect-1">{{ __('Sign In') }}</span>
                    </span>
                </button>
            @endauth
        </div>
        <div class="sidebar-wrap">
            <h6>{{ $contactSection?->address }}</h6>
        </div>
        <div class="sidebar-wrap">
            <h6><a href="tel:{{ $contactSection?->phone }}">{{ $contactSection?->phone }} </a></h6>
            <h6><a href="mailto:{{ $contactSection?->email }}">{{ $contactSection?->email }}</a></h6>
        </div>
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
</div>
