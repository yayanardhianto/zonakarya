<li class="nav-item {{ isRoute('admin.country.index', 'active') }}">
    <a href="{{ route('admin.country.index',['code' => getSessionLanguage()]) }}" class="nav-link">
        <i class="fas fa-map-marker-alt"></i><span>{{ __('Country') }}</span>
    </a>
</li>