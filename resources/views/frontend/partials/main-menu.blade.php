<ul>
    @forelse (mainMenu() as $menu)
        @php
            $has_child = !empty($menu['child']);
            $is_home = $menu['link'] == '/';
        @endphp

        @if ($is_home)
            <li class="active">
                <a href="{{ route('home') }}" class="text-uppercase">
                    <span class="link-effect">
                        <span class="effect-1">{{ __('Home') }}</span>
                        <span class="effect-1">{{ __('Home') }}</span>
                    </span>
                </a>
                <!-- @php
                    $is_homepage = url()->current() == url('/');
                @endphp

                <ul class="sub-menu">
                    @foreach (App\Enums\ThemeList::themes() as $theme)
                        <li
                            class="{{ session()->get('demo_theme', DEFAULT_HOMEPAGE) == $theme?->name && $is_homepage ? 'active' : '' }}">
                            <a href="{{ route('change-theme', $theme?->name) }}">{{ $theme?->title }}</a>
                        </li>
                    @endforeach
                </ul> -->
            </li>
        @else
            <li
                class="{{ $has_child ? 'menu-item-has-children' : '' }} {{ url()->current() == url($menu['link']) ? 'active' : '' }}">
                <a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
                    class="text-uppercase" {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>
                    <span class="link-effect">
                        <span class="effect-1">{{ $menu['label'] }}</span>
                        <span class="effect-1">{{ $menu['label'] }}</span>
                    </span>
                </a>

                @if ($has_child)
                    <ul class="sub-menu">
                        @foreach ($menu['child'] as $child)
                            <x-child-menu :menu="$child" />
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @empty
        <li class="menu-item-has-children">
            <a href="{{ route('home') }}" class="text-uppercase">
                <span class="link-effect">
                    <span class="effect-1">{{ __('Home') }}</span>
                    <span class="effect-1">{{ __('Home') }}</span>
                </span>
            </a>
        </li>
    @endforelse
    
    <!-- Job Vacancies Link -->
    <!-- <li class="{{ isRoute('jobs.*', 'active') }}">
        <a href="{{ route('jobs.index') }}" class="text-uppercase">
            <span class="link-effect">
                <span class="effect-1">{{ __('Jobs') }}</span>
                <span class="effect-1">{{ __('Jobs') }}</span>
            </span>
        </a>
    </li> -->
</ul>
