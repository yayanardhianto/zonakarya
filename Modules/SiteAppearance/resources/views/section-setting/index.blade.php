@extends('admin.master_layout')
@section('title')
    <title>{{ __('Section Settings') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Section Settings') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Section Settings') => '#',
            ]" />

            <div class="section-body">

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.section-setting.update', 1) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Hero Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->hero_section) type="checkbox" name="hero_section"
                                                value="1" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('About Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->about_section) type="checkbox" name="about_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Project Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->project_section) type="checkbox" name="project_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Service Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->service_section) type="checkbox" name="service_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Service Feature Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->service_feature_section) type="checkbox" name="service_feature_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Faq Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->faq_section) type="checkbox" name="faq_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Team Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->team_section) type="checkbox" name="team_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Award Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->award_section) type="checkbox" name="award_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Brands Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->brands_section) type="checkbox" name="brands_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Marquee Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->marquee_section) type="checkbox" name="marquee_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Call To Action Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->call_to_action_section) type="checkbox" name="call_to_action_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Counter Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->counter_section) type="checkbox" name="counter_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Choose Us Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->choose_us_section) type="checkbox" name="choose_us_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Pricing Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->pricing_section) type="checkbox" name="pricing_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Contact Us Section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->contact_us_section) type="checkbox" name="contact_us_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Banner section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->banner_section) type="checkbox" name="banner_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Latest Blog section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->latest_blog_section) type="checkbox" name="latest_blog_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="control-label">{{ __('Testimonial section') }}</div>
                                        <label class="custom-switch mt-2">
                                            <input @checked($sectionSetting?->testimonial_section) type="checkbox" name="testimonial_section" value="1"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
@endpush
