@extends('admin.master_layout')
@section('title')
    <title>{{ __('Email Configuration') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Email Configuration') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Email Configuration') => '#',
            ]" />
            <div class="section-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="emailTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" id="setting-tab" data-bs-toggle="tab"
                                            href="#setting_tab" role="tab" aria-controls="setting"
                                            aria-selected="true">{{ __('Setting') }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="email-template-tab" data-bs-toggle="tab"
                                            href="#email_template_tab" role="tab" aria-controls="email-template"
                                            aria-selected="false">{{ __('Email Template') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="myTabContent2">
                            <div class="tab-pane fade active show pt-0" id="setting_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="on-change-submit"
                                            action="{{ route('admin.update-general-setting') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <x-admin.form-switch name="is_queable" label="{{ __('Send Mails In Queue') }}"
                                                active_value="active" inactive_value="inactive" :checked="$setting->is_queable == 'active'" />

                                            @if ($setting->is_queable == 'active')
                                                <div class="pt-1 text-info"><span
                                                        class="text-success ">{{ __('Copy and Run This Command') }}:
                                                    </span>
                                                    <strong role="button" id="copyCronText"
                                                        title="{{ __('Click to copy') }}">php artisan schedule:run
                                                        >>
                                                        /dev/null
                                                        2>&1</strong>
                                                </div>
                                                <div class="pt-1 text-warning">
                                                    <b>{{ __('If enabled, you must setup cron job in your server. otherwise it will not work and no mail will be sent') }}</b>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('admin.update-email-configuration') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @if (config('app.app_mode') == 'DEMO')
                                                            <x-admin.form-input id="mail_host" name="mail_host"
                                                                label="{{ __('Mail Host') }}" value="TEST.MAIL-HOST" />
                                                        @else
                                                            <x-admin.form-input id="mail_host" name="mail_host"
                                                                label="{{ __('Mail Host') }}"
                                                                value="{{ $setting->mail_host }}" />
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-admin.form-input type="number" id="mail_port" name="mail_port"
                                                            label="{{ __('Mail Port') }}"
                                                            value="{{ $setting->mail_port }}" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @if (config('app.app_mode') == 'DEMO')
                                                            <x-admin.form-input id="mail_username" name="mail_username"
                                                                label="{{ __('SMTP User Name') }}" value="TEST-JOHN-DOE" />
                                                        @else
                                                            <x-admin.form-input id="mail_username" name="mail_username"
                                                                label="{{ __('SMTP User Name') }}"
                                                                value="{{ $setting->mail_username }}" />
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @if (config('app.app_mode') == 'DEMO')
                                                            <x-admin.form-input id="mail_password" name="mail_password"
                                                                label="{{ __('SMTP Password') }}" value="password1234" />
                                                        @else
                                                            <x-admin.form-input id="mail_password" name="mail_password"
                                                                label="{{ __('SMTP Password') }}"
                                                                value="{{ $setting->mail_password }}" />
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <x-admin.form-input id="mail_sender_name" name="mail_sender_name"
                                                            label="{{ __('Sender Name') }}"
                                                            value="{{ $setting->mail_sender_name }}" />
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        @if (config('app.app_mode') == 'DEMO')
                                                            <x-admin.form-input type="email" id="mail_sender_email"
                                                                name="mail_sender_email" label="{{ __('Email') }}"
                                                                value="sender@gmail.com" />
                                                        @else
                                                            <x-admin.form-input type="email" id="mail_sender_email"
                                                                name="mail_sender_email" label="{{ __('Email') }}"
                                                                value="{{ $setting->mail_sender_email }}" />
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-12 col-lg-4">
                                                    <div class="form-group">
                                                        <x-admin.form-select id="mail_encryption" name="mail_encryption"
                                                            label="{{ __('Mail Encryption') }}" class="form-select">
                                                            <x-admin.select-option :selected="$setting->mail_encryption == 'tls'" value="tls"
                                                                text="{{ __('TLS') }}" />
                                                            <x-admin.select-option :selected="$setting->mail_encryption == 'ssl'" value="ssl"
                                                                text="{{ __('SSL') }}" />
                                                        </x-admin.form-select>
                                                    </div>
                                                </div>
                                            </div>
                                            <x-admin.update-button :text="__('Update')" />
                                            @if (
                                                $setting->mail_username != 'mail_username' &&
                                                    $setting->mail_password != 'mail_password' &&
                                                    $setting->mail_port != 'mail_port')
                                                @php($test_email = true)
                                                <button class="btn btn-primary" data-bs-toggle="modal" type="button"
                                                    data-bs-target="#testEmail">{{ __('Test Mail Credentials') }}</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade pt-0" id="email_template_tab" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive table-invoice">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('SN') }}</th>
                                                        <th>{{ __('Email Template') }}</th>
                                                        <th>{{ __('Subject') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($templates as $index => $item)
                                                        <tr>
                                                            <td>{{ ++$index }}</td>
                                                            <td>{{ ucwords(str_replace('_', ' ', $item->name)) }}</td>
                                                            <td>{{ $item->subject }}</td>
                                                            <td>
                                                                <x-admin.edit-button :href="route(
                                                                    'admin.edit-email-template',
                                                                    $item->id,
                                                                )" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- Test Email Modal --}}
    @if ($test_email ?? false)
        <div class="modal fade" tabindex="-1" role="dialog" id="testEmail">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Test Mail Credentials') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are You sure want to test your mail Credentials?') }}</p>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <form action="{{ route('admin.test-mail-credentials') }}" action="" method="POST">
                            @csrf
                            <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                            <x-admin.button type="submit" text="{{ __('Yes') }}" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script>
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('emailTab')
        });
    </script>
@endpush
