@extends('admin.master_layout')
@section('title')
    <title>{{ __('Contact Page Section') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Contact Page Section') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Contact Page Section') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Update Contact Section')" />
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.contact-section.update',) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="phone"  name="phone" label="{{ __('Phone') }}" placeholder="{{ __('Enter Phone') }}" value="{{ $contact?->phone }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="phone_two"  name="phone_two" label="{{ __('Second Phone') }}" placeholder="{{ __('Enter Phone') }}" value="{{ $contact?->phone_two }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input type="email" id="email"  name="email" label="{{ __('Email') }}" placeholder="{{ __('Enter Email') }}" value="{{ $contact?->email }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input type="email" id="email_two"  name="email_two" label="{{ __('Second Email') }}" placeholder="{{ __('Enter Email') }}" value="{{ $contact?->email_two }}"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="address"  name="address" label="{{ __('Address') }}" placeholder="{{ __('Enter Address') }}" value="{{ $contact?->address }}"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="map"  name="map" label="{{ __('Map Link') }}" placeholder="{{ __('Enter Map Link') }}" value="{{ preg_replace('/^https?:\/\//', '', $contact?->map) }}"/>
                                        </div>
                                        
                                        <!-- Wording Fields Section -->
                                        <div class="col-md-12">
                                            <hr>
                                            <h5 class="text-primary mb-3">{{ __('Page Wording Settings') }}</h5>
                                        </div>
                                        
                                        <!-- Section Titles -->
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="headquarters_title" name="headquarters_title" label="{{ __('Headquarters Title') }}" placeholder="{{ __('Enter Headquarters Title') }}" value="{{ $contact?->headquarters_title }}"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="email_title" name="email_title" label="{{ __('Email Title') }}" placeholder="{{ __('Enter Email Title') }}" value="{{ $contact?->email_title }}"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="phone_title" name="phone_title" label="{{ __('Phone Title') }}" placeholder="{{ __('Enter Phone Title') }}" value="{{ $contact?->phone_title }}"/>
                                        </div>
                                        
                                        <!-- Button Texts -->
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="get_direction_text" name="get_direction_text" label="{{ __('Get Direction Text') }}" placeholder="{{ __('Enter Get Direction Text') }}" value="{{ $contact?->get_direction_text }}"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="send_message_text" name="send_message_text" label="{{ __('Send Message Text') }}" placeholder="{{ __('Enter Send Message Text') }}" value="{{ $contact?->send_message_text }}"/>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <x-admin.form-input id="call_anytime_text" name="call_anytime_text" label="{{ __('Call Anytime Text') }}" placeholder="{{ __('Enter Call Anytime Text') }}" value="{{ $contact?->call_anytime_text }}"/>
                                        </div>
                                        
                                        <!-- Page Title Settings -->
                                        <div class="col-md-12">
                                            <hr>
                                            <h5 class="text-primary mb-3">{{ __('Page Title Settings') }}</h5>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="page_title" name="page_title" label="{{ __('Page Title') }}" placeholder="{{ __('Enter Page Title') }}" value="{{ $contact?->page_title }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="breadcrumb_title" name="breadcrumb_title" label="{{ __('Breadcrumb Title') }}" placeholder="{{ __('Enter Breadcrumb Title') }}" value="{{ $contact?->breadcrumb_title }}"/>
                                        </div>
                                        
                                        <!-- Form Settings -->
                                        <div class="col-md-12">
                                            <hr>
                                            <h5 class="text-primary mb-3">{{ __('Contact Form Settings') }}</h5>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="form_title" name="form_title" label="{{ __('Form Title') }}" placeholder="{{ __('Enter Form Title') }}" value="{{ $contact?->form_title }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="form_subtitle" name="form_subtitle" label="{{ __('Form Subtitle') }}" placeholder="{{ __('Enter Form Subtitle') }}" value="{{ $contact?->form_subtitle }}"/>
                                        </div>
                                        
                                        <!-- Form Labels -->
                                        <div class="form-group col-md-3">
                                            <x-admin.form-input id="full_name_label" name="full_name_label" label="{{ __('Full Name Label') }}" placeholder="{{ __('Enter Full Name Label') }}" value="{{ $contact?->full_name_label }}"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <x-admin.form-input id="email_label" name="email_label" label="{{ __('Email Label') }}" placeholder="{{ __('Enter Email Label') }}" value="{{ $contact?->email_label }}"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <x-admin.form-input id="subject_label" name="subject_label" label="{{ __('Subject Label') }}" placeholder="{{ __('Enter Subject Label') }}" value="{{ $contact?->subject_label }}"/>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <x-admin.form-input id="message_label" name="message_label" label="{{ __('Message Label') }}" placeholder="{{ __('Enter Message Label') }}" value="{{ $contact?->message_label }}"/>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="website_label" name="website_label" label="{{ __('Website Label') }}" placeholder="{{ __('Enter Website Label') }}" value="{{ $contact?->website_label }}"/>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <x-admin.form-input id="submit_button_text" name="submit_button_text" label="{{ __('Submit Button Text') }}" placeholder="{{ __('Enter Submit Button Text') }}" value="{{ $contact?->submit_button_text }}"/>
                                        </div>
                                        
                                        <!-- Visibility Settings -->
                                        <div class="col-md-12">
                                            <hr>
                                            <h5 class="text-primary mb-3">{{ __('Field Visibility Settings') }}</h5>
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show_website_field" name="show_website_field" {{ $contact?->show_website_field ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_website_field">
                                                    {{ __('Show Website Field') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show_second_phone" name="show_second_phone" {{ $contact?->show_second_phone ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_second_phone">
                                                    {{ __('Show Second Phone') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="show_second_email" name="show_second_email" {{ $contact?->show_second_email ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_second_email">
                                                    {{ __('Show Second Email') }}
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <x-admin.save-button :text="__('Save')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        function cleanUrl() {
            let url = $('#map').val();
            if (url.startsWith('https://')) {
                url = url.replace('https://', '');
            } else if (url.startsWith('http://')) {
                url = url.replace('http://', '');
            }
            $('#map').val(url);
        }

        // Handle input event (typing)
        $('#map').on('input', cleanUrl);

        // Handle paste event
        $('#map').on('paste', function() {
            setTimeout(cleanUrl, 0);
        });
    </script>
@endpush
