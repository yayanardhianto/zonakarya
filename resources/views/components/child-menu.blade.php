<li class="{{ !empty($menu['child']) ? 'menu-item-has-children' : '' }} {{ url()->current() == url($menu['link']) || hasActiveChild($menu) ? 'active' : '' }}">
    <a href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}"
        {{ $menu['open_new_tab'] ? 'target="_blank"' : '' }}>
        {{ $menu['label'] }}
    </a>
    @if (!empty($menu['child']))
        <ul class="sub-menu">
            @foreach ($menu['child'] as $child)
                <x-child-menu :menu="$child" />
            @endforeach
        </ul>
    @endif
</li>