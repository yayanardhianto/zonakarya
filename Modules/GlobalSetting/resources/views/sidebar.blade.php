<li class="menu-header">Settings</li>
<li class="{{ Route::is('admin.general-setting') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.general-setting') }}"><i class="fas fa-cog"></i>
        <span>{{ __('General Settings') }}</span></a></li>

<li class="{{ Route::is('admin.crediential-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.crediential-setting') }}"><i class="fas fa-key"></i>
        <span>{{ __('Credential Settings') }}</span>
    </a>
</li>

<li class="{{ Route::is('admin.email-configuration') || Route::is('admin.edit-email-template') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.email-configuration') }}"><i class="fas fa-envelope"></i>
        <span>{{ __('Email Configuration') }}</span>
    </a>
</li>

@if (Module::isEnabled('Language') && checkAdminHasPermission('language.view'))
    @include('language::sidebar')
@endif

<!-- <li class="{{ Route::is('admin.seo-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.seo-setting') }}"><i class="fas fa-search"></i>
        <span>{{ __('SEO Setup') }}</span>
    </a>
</li> -->

<li class="{{ Route::is('admin.whatsapp-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.whatsapp-setting') }}"><i class="fab fa-whatsapp"></i>
        <span>{{ __('WhatsApp Settings') }}</span>
    </a>
</li>

<!-- <li class="{{ Route::is('admin.footer-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.footer-setting') }}"><i class="fas fa-window-minimize"></i>
        <span>{{ __('Footer Settings') }}</span>
    </a>
</li> -->

<!-- <li class="{{ Route::is('admin.job-listing-setting') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.job-listing-setting') }}"><i class="fas fa-briefcase"></i>
        <span>{{ __('Job Listing Settings') }}</span>
    </a>
</li> -->
<!-- @if (Module::isEnabled('Sitemap') && checkAdminHasPermission('sitemap.management'))
    @include('sitemap::sidebar')
@endif -->
<li class="menu-header">{{ __('Extra Settings') }}</li>

<li class="{{ Route::is('admin.cache-clear') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.cache-clear') }}"><i class="fas fa-sync"></i>
        <span>{{ __('Clear cache') }}</span>
    </a></li>

<li class="{{ Route::is('admin.database-clear') ? 'active' : '' }}"><a class="nav-link"
        href="{{ route('admin.database-clear') }}"><i class="fas fa-database"></i>
        <span>{{ __('Database Clear') }}</span></a></li>

<!-- <li class="{{ Route::is('admin.system-update.index') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.system-update.index') }}"><i class="fas fa-arrow-circle-up"></i>
        <span>{{ __('System Update') }}</span>
    </a>
</li> -->
<!-- <li class="{{ Route::is('admin.addons.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.addons.view') }}"><i class="fas fa-plug"></i>
        <span>{{ __('Manage Addons') }}</span>
    </a>
</li> -->
