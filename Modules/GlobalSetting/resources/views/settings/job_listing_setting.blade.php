@extends('admin.master_layout')
@section('title')
    <title>{{ __('Job Listing Settings') }}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Job Listing Settings') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Job Listing Settings') => '#',
        ]" />
        <div class="section-body">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Job Listing Settings') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.update-job-listing-setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <x-admin.form-input type="text" id="job_listing_title" name="job_listing_title"
                                label="{{ __('Job Listing Title') }}" placeholder="{{ __('Enter Job Listing Title') }}"
                                value="{{ $setting->job_listing_title ?? 'Rise Together' }}" required="true" />
                        </div>

                        <div class="form-group">
                            <x-admin.form-textarea id="job_listing_description" name="job_listing_description"
                                label="{{ __('Job Listing Description') }}" placeholder="{{ __('Enter Job Listing Description') }}"
                                value="{{ $setting->job_listing_description ?? 'Mulai perjalanan Anda dengan perusahaan kami, mari bergabung bersama kami.' }}"
                                maxlength="1000" required="true" />
                        </div>

                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
