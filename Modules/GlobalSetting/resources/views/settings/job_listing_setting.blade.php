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
                            <div class="form-check">
                                <input type="hidden" name="require_screening_test" value="0">
                                <input class="form-check-input" type="checkbox" id="require_screening_test" name="require_screening_test" 
                                    value="1" {{ ($setting->require_screening_test == '1' || $setting->require_screening_test === 1 || ($setting->require_screening_test === null)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_screening_test">
                                    {{ __('Require Screening Test for Applicants') }}
                                </label>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                {{ __('If checked: Applicants must complete screening test before filling profile. If unchecked: Applicants can directly fill profile.') }}
                            </small>
                        </div>
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

                        <hr class="my-4">
                        <h5 class="mb-3">{{ __('Job Detail Page Labels') }}</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_location" name="label_location"
                                        label="{{ __('Location Label') }}" placeholder="{{ __('Enter Location Label') }}"
                                        value="{{ $setting->label_location ?? 'Lokasi' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_work_type" name="label_work_type"
                                        label="{{ __('Work Type Label') }}" placeholder="{{ __('Enter Work Type Label') }}"
                                        value="{{ $setting->label_work_type ?? 'Jenis Pekerjaan' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_salary_range" name="label_salary_range"
                                        label="{{ __('Salary Range Label') }}" placeholder="{{ __('Enter Salary Range Label') }}"
                                        value="{{ $setting->label_salary_range ?? 'Range Gaji' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_age_range" name="label_age_range"
                                        label="{{ __('Age Range Label') }}" placeholder="{{ __('Enter Age Range Label') }}"
                                        value="{{ $setting->label_age_range ?? 'Range Usia' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_gender" name="label_gender"
                                        label="{{ __('Gender Label') }}" placeholder="{{ __('Enter Gender Label') }}"
                                        value="{{ $setting->label_gender ?? 'Jenis Kelamin' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_deadline" name="label_deadline"
                                        label="{{ __('Application Deadline Label') }}" placeholder="{{ __('Enter Application Deadline Label') }}"
                                        value="{{ $setting->label_deadline ?? 'Deadline Pendaftaran' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">{{ __('Section Headers') }}</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_job_description" name="label_job_description"
                                        label="{{ __('Job Description Header') }}" placeholder="{{ __('Enter Job Description Header') }}"
                                        value="{{ $setting->label_job_description ?? 'Deskripsi Pekerjaan' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_responsibilities" name="label_responsibilities"
                                        label="{{ __('Responsibilities Header') }}" placeholder="{{ __('Enter Responsibilities Header') }}"
                                        value="{{ $setting->label_responsibilities ?? 'Tanggung Jawab' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_requirements" name="label_requirements"
                                        label="{{ __('Requirements Header') }}" placeholder="{{ __('Enter Requirements Header') }}"
                                        value="{{ $setting->label_requirements ?? 'Persyaratan Khusus' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_benefits" name="label_benefits"
                                        label="{{ __('Benefits Header') }}" placeholder="{{ __('Enter Benefits Header') }}"
                                        value="{{ $setting->label_benefits ?? 'Keuntungan' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">{{ __('Application Section') }}</h5>

                        

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_company_info" name="label_company_info"
                                        label="{{ __('Company Information Header') }}" placeholder="{{ __('Enter Company Information Header') }}"
                                        value="{{ $setting->label_company_info ?? 'Informasi Perusahaan' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_apply_position" name="label_apply_position"
                                        label="{{ __('Apply Position Header') }}" placeholder="{{ __('Enter Apply Position Header') }}"
                                        value="{{ $setting->label_apply_position ?? 'Lamar Posisi Ini' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_send_application" name="label_send_application"
                                        label="{{ __('Send Application Button') }}" placeholder="{{ __('Enter Send Application Button Text') }}"
                                        value="{{ $setting->label_send_application ?? 'Kirim Lamaran' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_send_email" name="label_send_email"
                                        label="{{ __('Send Email Button') }}" placeholder="{{ __('Enter Send Email Button Text') }}"
                                        value="{{ $setting->label_send_email ?? 'Kirim Email' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_call_company" name="label_call_company"
                                        label="{{ __('Call Company Button') }}" placeholder="{{ __('Enter Call Company Button Text') }}"
                                        value="{{ $setting->label_call_company ?? 'Telepon Perusahaan' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_job_stats" name="label_job_stats"
                                        label="{{ __('Job Statistics Header') }}" placeholder="{{ __('Enter Job Statistics Header') }}"
                                        value="{{ $setting->label_job_stats ?? 'Statistik Lowongan' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_views" name="label_views"
                                        label="{{ __('Views Label') }}" placeholder="{{ __('Enter Views Label') }}"
                                        value="{{ $setting->label_views ?? 'Dilihat' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_posted" name="label_posted"
                                        label="{{ __('Posted Label') }}" placeholder="{{ __('Enter Posted Label') }}"
                                        value="{{ $setting->label_posted ?? 'Diposting' }}" required="true" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-admin.form-input type="text" id="label_last_updated" name="label_last_updated"
                                        label="{{ __('Last Updated Label') }}" placeholder="{{ __('Enter Last Updated Label') }}"
                                        value="{{ $setting->label_last_updated ?? 'Terakhir Diupdate' }}" required="true" />
                                </div>
                            </div>
                        </div>

                        <x-admin.update-button :text="__('Update')" />
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
