@extends('admin.master_layout')
@section('title')
    <title>{{ __('Site Color Settings') }}</title>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-colorpicker.min.css') }}">
@endpush

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Site Color Settings') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Site Color Settings') => '#',
            ]" />

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.site-color-setting.update', 1) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Primary Color') }}</label>
                                        <div class="input-group colorpickerinput colorpicker-element"
                                            data-colorpicker-id="2">
                                            <input type="text" name="primary_color" class="form-control" value="{{ @Cache::get('setting')?->primary_color }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fill-drip"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Secondary Color') }}</label>
                                        <div class="input-group colorpickerinput colorpicker-element"
                                            data-colorpicker-id="2">
                                            <input type="text" class="form-control" name="secondary_color" value="{{ @Cache::get('setting')?->secondary_color }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fill-drip"></i>
                                                </div>
                                            </div>
                                        </div>
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
    <script src="{{ asset('backend/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(".colorpickerinput").colorpicker({
            format: 'hex',
            component: '.input-group-append',
        });
    </script>
@endpush
