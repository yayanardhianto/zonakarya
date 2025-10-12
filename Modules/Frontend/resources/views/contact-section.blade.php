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
