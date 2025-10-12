@extends('admin.master_layout') @section('title')
    <title>{{ __('General Setting') }}</title>
    @endsection @section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('General Setting') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('General Setting') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('globalsetting::settings.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent2">
                                    @include('globalsetting::settings.sections.general')
                                    @include('globalsetting::settings.sections.website')
                                    @include('globalsetting::settings.sections.logo-favicon')
                                    @include('globalsetting::settings.sections.cookie')
                                    @include('globalsetting::settings.sections.custom-paginate')
                                    @include('globalsetting::settings.sections.default-avatar')
                                    @include('globalsetting::settings.sections.breadcrump')
                                    @include('globalsetting::settings.sections.copyright')
                                    @include('globalsetting::settings.sections.search-engine-crawling')
                                    @include('globalsetting::settings.sections.maintenance-mode')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#logo-white-upload",
            preview_box: "#logo-white-preview",
            label_field: "#logo-white-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#favicon-upload",
            preview_box: "#favicon-preview",
            label_field: "#favicon-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#breadcrumb_image_upload",
            preview_box: "#breadcrumb_image_preview",
            label_field: "#breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#contact_page_breadcrumb_image_upload",
            preview_box: "#contact_page_breadcrumb_image_preview",
            label_field: "#contact_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#team_page_breadcrumb_image_upload",
            preview_box: "#team_page_breadcrumb_image_preview",
            label_field: "#team_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#about_page_breadcrumb_image_upload",
            preview_box: "#about_page_breadcrumb_image_preview",
            label_field: "#about_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#faq_page_breadcrumb_image_upload",
            preview_box: "#faq_page_breadcrumb_image_preview",
            label_field: "#faq_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#blog_page_breadcrumb_image_upload",
            preview_box: "#blog_page_breadcrumb_image_preview",
            label_field: "#blog_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#portfolio_page_breadcrumb_image_upload",
            preview_box: "#portfolio_page_breadcrumb_image_preview",
            label_field: "#portfolio_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#service_page_breadcrumb_image_upload",
            preview_box: "#service_page_breadcrumb_image_preview",
            label_field: "#service_page_breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#default_avatar_upload",
            preview_box: "#default_avatar_preview",
            label_field: "#default_avatar_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#maintenance_image_upload",
            preview_box: "#maintenance_image_preview",
            label_field: "#maintenance_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });


        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab')
        });
    </script>
@endpush
