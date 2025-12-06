<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            @adminCan('dashboard.view')
                <li class="{{ isRoute('admin.dashboard', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="ki-solid ki-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endadminCan

            @if (checkAdminHasPermission('blog.category.view') ||
                    checkAdminHasPermission('blog.view') ||
                    checkAdminHasPermission('blog.comment.view') ||
                    checkAdminHasPermission('team.management') ||
                    checkAdminHasPermission('job.vacancy.view') ||
                    checkAdminHasPermission('branch.view'))

                <li class="menu-header">{{ __('Manage Contents') }}</li>
                @if (Module::isEnabled('Frontend') &&
                        (checkAdminHasPermission('section.management') ||
                            checkAdminHasPermission('marquee.view') ||
                            checkAdminHasPermission('award.view')))
                    @include('frontend::sidebar')
                @endif

                <li class="{{ isRoute('admin.section-setting.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.section-setting.index') }}"><i class="ki-solid ki-toggle-on"></i><span>{{ __('Home Section Setting') }}</span>

                    </a></li>
                <li class="{{ isRoute('admin.contact-section.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.contact-section.index') }}">
                        <i class="ki-solid ki-phone"></i>
                        <span>{{ __('Contact Page Section') }}</span>
                    </a>
                </li>


                @if (Module::isEnabled('OurTeam') && checkAdminHasPermission('team.management'))
                    @include('ourteam::sidebar')
                @endif
                @if (Module::isEnabled('Service') && checkAdminHasPermission('service.view'))
                @include('service::sidebar')
                @endif
                <!-- @if (Module::isEnabled('Project') && checkAdminHasPermission('project.view'))
                    @include('project::sidebar')
                @endif -->
                @if (Module::isEnabled('Brand') && checkAdminHasPermission('brand.management'))
                    @include('brand::sidebar')
                @endif


                @if (checkAdminHasPermission('branch.view'))
                    <li class="{{ isRoute('admin.branch.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.branch.index') }}">
                            <i class="fas fa-building"></i>
                            <span>{{ __('Branches') }}</span>
                        </a>
                    </li>
                @endif
                <li class="{{ isRoute('admin.marquee.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.marquee.index') }}">
                        <i class="fas fa-play-circle"></i>
                        <span>{{ __('Text Slider') }}</span>
                    </a>
                </li>
                @if (checkAdminHasPermission('whatsapp.template.view'))
                        <li class="{{ isRoute('admin.whatsapp-templates.*', 'active') }}">
                            <a class="nav-link" href="{{ route('admin.whatsapp-templates.index') }}">
                                <i class="fab fa-whatsapp"></i>
                                <span>{{ __('WhatsApp Templates') }}</span>
                            </a>
                        </li>
                 @endif
                <li class="{{ isRoute('admin.about-sections.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.about-sections.index') }}">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ __('About Page Sections') }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('admin.footer-setting') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.footer-setting') }}"><i class="fas fa-th-large"></i>
                        <span>{{ __('Footer Sections') }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('admin.job-listing-setting') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.job-listing-setting') }}"><i class="fas fa-briefcase"></i>
                        <span>{{ __('Job Page/Listing Setting') }}</span>
                    </a>
                </li>

                @if (checkAdminHasPermission('menu.view') ||
                    checkAdminHasPermission('page.view') ||
                    checkAdminHasPermission('faq.view') ||
                    checkAdminHasPermission('social.link.management'))
                <li class="menu-header">{{ __("Site Management") }}</li>
                @if (Module::isEnabled('Blog'))
                    @include('blog::sidebar')
                @endif

                @if (Module::isEnabled('CustomMenu') && checkAdminHasPermission('menu.view'))
                    @include('custommenu::sidebar')
                @endif

                @if (Module::isEnabled('PageBuilder') && checkAdminHasPermission('page.view'))
                    @include('pagebuilder::sidebar')
                @endif
                <!-- @if (Module::isEnabled('Faq') && checkAdminHasPermission('faq.view'))
                    @include('faq::sidebar')
                @endif -->
                @if (Module::isEnabled('SocialLink') && checkAdminHasPermission('social.link.management'))
                    @include('sociallink::sidebar')
                @endif
                <li class="{{ isRoute('admin.contact-messages') || isRoute('admin.contact-message') ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin.contact-messages') }}"><i class="fas fa-envelope"></i> <span>{{ __('Contact Messages') }}</span></a></li>

                @endif
                @if (checkAdminHasPermission('job.vacancy.view'))
                    <li class="menu-header">{{ __('Job Management') }}</li>
                @if (checkAdminHasPermission('location.view'))
                    <li class="{{ isRoute('admin.location.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.location.index') }}">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ __('Locations') }}</span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ isRoute('admin.job-vacancy.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.job-vacancy.index') }}">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ __('Job Vacancies') }}</span>
                        </a>
                    </li>
                @endif

                @if (checkAdminHasPermission('applicant.view'))
                    <li class="menu-header">{{ __('Applicants Management') }}</li>
                    
                    <li class="{{ isRoute('admin.applicants.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.applicants.index') }}">
                            <i class="fas fa-users"></i>
                            <span>{{ __('Applicants') }}</span>
                        </a>
                    </li>
                    <li class="{{ isRoute('admin.talents.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.talents.index') }}">
                            <i class="fas fa-star"></i>
                            <span>{{ __('Talents') }}</span>
                        </a>
                    </li>
                    <li class="{{ isRoute('admin.onboard.*', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.onboard.index') }}">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('Onboard') }}</span>
                        </a>
                    </li>
                @endif

               

                @if (checkAdminHasPermission('test.category.view') || 
                     checkAdminHasPermission('test.package.view') || 
                     checkAdminHasPermission('test.question.view') || 
                     checkAdminHasPermission('test.session.view'))

                     <li class="menu-header">{{ __('Test Management') }}</li>

                     <li
                        class="nav-item dropdown {{ isRoute(['admin.test-category.*', 'admin.test-package.*', 'admin.test-question.*', 'admin.test-session.*'], 'active') }}">
                        <a href="javascript:void()" class="nav-link has-dropdown"><i
                                class="ki-solid ki-questionnaire-tablet"></i><span>{{ __('Manage Test') }}</span></a>

                        <ul class="dropdown-menu">
                            @adminCan('test.category.view')
                                <li class="{{ isRoute('admin.test-category.*', 'active') }}">
                                    <a class="nav-link" href="{{ route('admin.test-category.index') }}">
                                        {{ __('Test Category') }}
                                    </a>
                                </li>
                            @endadminCan
                            @adminCan('test.package.view')
                                <li class="{{ isRoute('admin.test-package.*', 'active') }}">
                                    <a class="nav-link" href="{{ route('admin.test-package.index') }}">
                                        {{ __('Test Package ') }}
                                    </a>
                                </li>
                            @endadminCan
                            @adminCan('test.question.view')
                                <li class="{{ isRoute('admin.test-question.*', 'active') }}">
                                    <a class="nav-link" href="{{ route('admin.test-question.index') }}">
                                        {{ __('Test Question') }}
                                    </a>
                                </li>
                            @endadminCan
                            @adminCan('test.session.view')
                                <li class="{{ isRoute('admin.test-session.*', 'active') }}">
                                    <a class="nav-link" href="{{ route('admin.test-session.index') }}">
                                        {{ __('Test Session') }}
                                    </a>
                                </li>
                            @endadminCan
                        </ul>
                    </li>
                @endif

                @endif


            


           

            @if (checkAdminHasPermission('setting.view') ||
                    checkAdminHasPermission('language.view') ||
                    checkAdminHasPermission('addon.view') ||
                    checkAdminHasPermission('role.view') ||
                    checkAdminHasPermission('admin.view'))
                <li class="menu-header">{{ __('Settings') }}</li>

                @if (Module::isEnabled('GlobalSetting'))
                    <li class="{{ isRoute('admin.settings', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endif
                
                <li class="{{ isRoute('admin.short-urls.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.short-urls.index') }}"><i class="fas fa-link"></i>
                        <span>{{ __('Short URLs') }}</span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="py-3 px-4">
            <div class="btn-sm-group-vertical version_button" role="group" aria-label="Responsive button group">
                <!-- <button class="btn btn-primary logout_btn mt-2" disabled>{{ __('version') }}
                    {{ $setting->version ?? '1.0.0' }}</button> -->
                <button class="logout-button btn btn-danger mt-2"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
            </div>
        </div>
    </aside>
</div>
