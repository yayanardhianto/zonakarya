@extends('admin.master_layout')
@section('title')
    <title>{{ __('Footer Sections') }}</title>
@endsection
@section('admin-content')
    <!-- DataTales Example -->
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Footer Sections') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Footer Sections') => '#',
        ]" />
        <div class="section-body">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Footer Sections') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-footer-setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <x-admin.form-input type="text" id="footer_title" name="footer_title"
                                label="{{ __('Footer Title') }}" placeholder="{{ __('Enter Footer Title') }}"
                                value="{{ $setting->footer_title ?? 'Have Any Thing in your mind?' }}" required="true" />
                        </div>

                        <div class="form-group">
                            <x-admin.form-textarea id="footer_description" name="footer_description"
                                label="{{ __('Footer Description') }}" placeholder="{{ __('Enter Footer Description') }}"
                                value="{{ $setting->footer_description ?? 'We are retail company engaged in sports equipment and gear, more commonly known by their store names, Sneakerzone and Jerseyzone' }}"
                                maxlength="1000" required="true" />
                        </div>

                        <div class="form-group">
                            <x-admin.form-input type="text" id="footer_button_text" name="footer_button_text"
                                label="{{ __('Footer Button Text') }}" placeholder="{{ __('Enter Footer Button Text') }}"
                                value="{{ $setting->footer_button_text ?? 'Contact Us' }}" required="true" />
                        </div>

                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
    
@endsection
