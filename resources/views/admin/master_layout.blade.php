@php
    $header_admin = Auth::guard('admin')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    <link rel="icon" href="{{ asset($setting->favicon) }}">
    @include('admin.partials.styles')
    @stack('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar ps-3 pe-4 py-2">
                <div class="me-2 form-inline">
                    <ul class="me-0 navbar-nav d-flex align-items-center">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg d-flex align-items-center"><i
                                    class="ki-outline ki-burger-menu fs-3"></i></a></li>
                        <!-- @if (Module::isEnabled('Language') && Route::has('set-language') && allLanguages()?->where('status', 1)->count() > 1)
                            <li class="setLanguageHeader dropdown border rounded-2"><a href="javascript:;" data-bs-toggle="dropdown"
                                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                    <div class="d-sm-none d-lg-inline-block">
                                        {{ allLanguages()?->firstWhere('code', getSessionLanguage())?->name ?? __('Select language') }}
                                    </div>
                                </a>
                                <div class="dropdown-menu py-0 dropdown-menu-left">
                                    @forelse (allLanguages()?->where('status', 1) as $language)
                                        <a href="{{ getSessionLanguage() == $language->code ? 'javascript:;' : route('set-language', ['code' => $language->code]) }}"
                                            class="dropdown-item has-icon {{ getSessionLanguage() == $language->code ? 'bg-light' : '' }}">
                                            {{ $language->name }}
                                        </a>
                                    @empty
                                        <a href="javascript:;"
                                            class="dropdown-item has-icon {{ getSessionLanguage() == 'en' ? 'bg-light' : '' }}">
                                            {{ __('English') }}
                                        </a>
                                    @endforelse
                                </div>
                            </li>
                        @endif -->
                    </ul>
                </div>
                <div class="mr-auto me-md-auto search-box position-relative w-25">
                    <x-admin.form-input id="search_menu" :placeholder="__('Search option')" />
                    <div id="admin_menu_list" class="position-absolute d-none rounded-2">
                        @foreach (App\Enums\RouteList::getAll() as $route_item)
                            @if (checkAdminHasPermission($route_item?->permission) || empty($route_item?->permission))
                                <a @isset($route_item->tab) 
                                        data-active-tab="{{ $route_item->tab }}" class="border-bottom search-menu-item" 
                                    @else 
                                        class="border-bottom" 
                                    @endisset
                                    href="{{ $route_item?->route }}">{{ $route_item?->name }}</a>
                            @endif
                        @endforeach
                        <a class="not-found-message d-none" href="javascript:;">{{ __('Not Found!') }}</a>
                    </div>
                </div>

                <ul class="navbar-nav">
                    <li class="dropdown border rounded-2 mx-2 dropdown-list-toggle">
                        <a target="_blank" href="{{ route('home') }}" class="nav-link nav-link-lg d-flex align-items-center bg-white text-primary">
                            <i class="ki-solid ki-home ps-1"></i> {{ __('Visit Website') }}</i>
                        </a>
                    </li>

                    <li class="dropdown border rounded-2 bg-white text-primary"><a href="javascript:;" data-bs-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center">
                            @if ($header_admin->image)
                                <img alt="image" src="{{ asset($header_admin->image) }}"
                                    class="me-2 rounded-circle">
                            @else
                                <img alt="image" src="{{ asset($setting->default_avatar) }}"
                                    class="me-2 rounded-circle">
                            @endif

                            <div class="d-sm-none d-lg-inline-block text-primary mt-0">{{ $header_admin->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @adminCan('admin.profile.view')
                                <a href="{{ route('admin.edit-profile') }}"
                                    class="dropdown-item has-icon d-flex align-items-center {{ isRoute('admin.edit-profile', 'text-primary') }}">
                                    <i class="far fa-user"></i> {{ __('Profile') }}
                                </a>
                            @endadminCan
                            @adminCan('setting.view')
                                <a href="{{ route('admin.settings') }}"
                                    class="dropdown-item has-icon d-flex align-items-center {{ isRoute('admin.settings', 'text-primary') }}">
                                    <i class="fas fa-cog"></i> {{ __('Setting') }}
                                </a>
                            @endadminCan
                            <a href="javascript:;" class="logout-button dropdown-item has-icon d-flex align-items-center">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>

            @if (request()->routeIs(
                    'admin.general-setting',
                    'admin.crediential-setting',
                    'admin.email-configuration',
                    'admin.edit-email-template',
                    'admin.currency.*',
                    'admin.tax.*',
                    'admin.seo-setting',
                    'admin.custom-code',
                    'admin.cache-clear',
                    'admin.database-clear',
                    'admin.system-update.index',
                    'admin.admin.*',
                    'admin.languages.*',
                    'admin.basicpayment',
                    'admin.addons.*',
                    'admin.sitemap.*',
                    'admin.role.*'
                    ))
                @include('admin.settings.sidebar')
            @else
                @include('admin.sidebar')
            @endif
            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-right">
                    {{ $setting->copyright_text }}
                </div>
            </footer>

        </div>
    </div>

    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    @include('admin.partials.javascripts')
    @include('admin.js-variables')

    @stack('js')

</body>

</html>
