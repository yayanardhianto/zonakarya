@if (Route::has('admin.marquee.index'))
    <li class="{{ isRoute('admin.marquee.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.marquee.index', ['code' => getSessionLanguage()]) }}">
            {{ __('Marquee Section') }}
        </a>
    </li>
@endif
