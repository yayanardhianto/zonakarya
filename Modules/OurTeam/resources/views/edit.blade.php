@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Team') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Team') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Our Team') => route('admin.ourteam.index'),
                __('Edit Team') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Team')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.ourteam.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.ourteam.update', $team->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <x-admin.form-image-preview :label="__('Avatar Image')" :image="$team->image"
                                                required="0" />
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                value="{{ old('name',$team->name) }}">
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('Slug') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="slug" class="form-control" name="slug"
                                                value="{{ old('slug',$team->slug) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Designation') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="designation" class="form-control" name="designation"
                                                value="{{ old('designation',$team->designation) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Email') }} <span class="text-danger">*</span></label>
                                            <input type="email" id="name" class="form-control" name="email"
                                                value="{{ old('email',$team->email) }}">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="phone" name="phone" label="{{ __('Phone') }}"
                                                placeholder="{{ __('Enter Phone') }}" value="{{ old('phone',$team->phone) }}" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-editor id="sort_description" name="sort_description"
                                                label="{{ __('Short Description') }}" value="{!! old('sort_description',$team->sort_description) !!}" />
                                            <small>{{ __('use \ for break and {} for bold') }}</small>
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Facebook') }} </label>
                                            <input type="text" class="form-control" name="facebook"
                                                value="{{ old('facebook',$team->facebook) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Twitter') }} </label>
                                            <input type="text" class="form-control" name="twitter"
                                                value="{{ old('twitter',$team->twitter) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Dribbble') }} </label>
                                            <input type="text" class="form-control" name="dribbble"
                                                value="{{ old('dribbble',$team->dribbble) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <label>{{ __('Instagram') }} </label>
                                            <input type="text" class="form-control" name="instagram"
                                                value="{{ old('instagram',$team->instagram) }}">
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-switch name="status" active_value="active" inactive_value="inactive" label="{{ __('Status') }}"
                                                :checked="old('status',$team->status) == 'active'" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
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
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#name").on("keyup", function(e) {
                    $("#slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    </script>
@endpush
