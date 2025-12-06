@extends('admin.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@use('Carbon\Carbon', 'Carbon')
@section('admin-content')
    <div class="main-content">
        {{-- Show Credentials Setup Alert --}}
        <div class="row position-relative">
            @if (Route::is('admin.dashboard') && ($checkCrentials = checkCrentials()))
                @foreach ($checkCrentials as $checkCrential)
                    @if ($checkCrential->status)
                        <div
                            class="alert alert-danger alert-has-icon alert-dismissible position-absolute w-100 missingCrentialsAlert">
                            <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">{{ $checkCrential->message }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                {{ $checkCrential->description }} <b><a class="btn btn-sm btn-outline-warning"
                                        href="{{ !empty($checkCrential->route) ? route($checkCrential->route) : url($checkCrential->url) }}">{{ __('Update') }}</a></b>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        @if ($setting?->is_queable == 'active' && Cache::get('corn_working') !== 'working')
            <!-- <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                <div class="alert-icon"><i class="fas fa-sync"></i></div>
                <div class="alert-body">
                    <div class="alert-title"><a href="{{ route('admin.general-setting') }}" target="_blank"
                            rel="noopener noreferrer">{{ __('Corn Job Is Not Running! Many features will be disabled and face errors') }}</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div> -->
        @endif

        <section class="section">
            <x-admin.breadcrumb title="{{ __('Dashboard') }}" />
            @if (checkAdminHasPermission('dashboard.view'))
                <div class="section-body">
                    <div class="row">
                        
                        <!-- Total Users Card -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Users') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_user ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Newsletter Subscribers Card -->
                        <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Newsletter Subscribers') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_newsletter ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        @if (checkAdminHasPermission('blog.view') && isset($total_blog_posts))
                        <!-- Total Blog Posts Card -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-blog"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Blog Posts') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_blog_posts ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (checkAdminHasPermission('service.view') && isset($total_services))
                        <!-- Total Services Card -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Services') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_services ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (checkAdminHasPermission('project.view') && isset($total_projects))
                        <!-- Total Projects Card -->
                        <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Projects') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_projects ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @endif

                        @if (checkAdminHasPermission('our.team.view') && isset($total_team_members))
                        <!-- Total Team Members Card -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-secondary">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Team Members') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_team_members ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (checkAdminHasPermission('testimonial.view') && isset($total_testimonials))
                        <!-- Total Testimonials Card -->
                        <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Testimonials') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_testimonials ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @endif

                        @if (checkAdminHasPermission('contact.message.view') && isset($total_contact_messages))
                        <!-- Total Contact Messages Card -->
                        <!-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Contact Messages') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($total_contact_messages ?? []) }}
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        @endif

                    </div>

                    
                </div>
            @endif
        </section>
    </div>
@endsection
