@if (Route::has('admin.award.index'))
    <li class="{{ isRoute('admin.award.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.award.index', ['code' => getSessionLanguage()]) }}">
            {{ __('Timeline Section') }}
        </a>
    </li>
@endif
