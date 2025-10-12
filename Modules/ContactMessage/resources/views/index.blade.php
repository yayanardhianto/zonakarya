@extends('admin.master_layout')
@section('title')
    <title>{{ __('Contact Message') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Contact Message') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Contact Message') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-general-setting') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-5">
                                            <x-admin.form-input type="email" id="contact_message_receiver_mail"
                                                name="contact_message_receiver_mail"
                                                label="{{ __('Contact Message Receiver Email') }}"
                                                placeholder="{{ __('Enter Email') }}" class="mb-2"
                                                value="{{ $setting->contact_message_receiver_mail }}" required="true" />
                                            <x-admin.update-button :text="__('Update')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-invoice">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('SN') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($messages as $index => $message)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ html_decode($message->name) }}</td>
                                            <td><a
                                                    href="mailto:{{ html_decode($message->email) }}">{{ html_decode($message->email) }}</a>
                                            </td>
                                            <td>{{ formattedDateTime($message->created_at) }}</td>
                                            <td>
                                                <a href="{{ route('admin.contact-message', $message->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                        aria-hidden="true"></i></a>
                                                @adminCan('contact.message.delete')
                                                    <a href="{{ route('admin.contact-message-delete', $message->id) }}"
                                                        data-modal="#deleteModal" class="delete-btn btn btn-danger btn-sm"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                @endadminCan
                                            </td>
                                        </tr>
                                    @empty
                                        <x-empty-table :name="__('')" route="" create="no" :message="__('No data found!')"
                                            colspan="5"></x-empty-table>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('contact.message.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
