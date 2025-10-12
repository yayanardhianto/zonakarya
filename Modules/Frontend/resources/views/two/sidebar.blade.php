<li class="{{ isRoute('admin.hero-section.index', 'active') }}">
    <a class="nav-link" href="{{ route('admin.hero-section.index', ['code' => 'en']) }}">
        {{ __('Hero Section') }}
    </a>
</li>
<li class="{{ isRoute('admin.about-section.index', 'active') }}">
    <a class="nav-link" href="{{ route('admin.about-section.index', ['code' => 'en']) }}">
        {{ __('About Section') }}
    </a>
</li>
<!-- <li class="{{ isRoute('admin.service-features-section.index', 'active') }}">
    <a class="nav-link" href="{{ route('admin.service-features-section.index', ['code' => 'en']) }}">
        {{ __('Service Feature Section') }}
    </a>
</li> -->