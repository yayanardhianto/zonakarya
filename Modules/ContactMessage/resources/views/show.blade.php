@extends('admin.master_layout')
@section('title')
    <title>{{ __('Message Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Message Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Contact Message') => route('admin.contact-messages'),
                __('Message Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tr>
                                        <td>{{ __('Name') }}</td>
                                        <td>{{ html_decode($message->name) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Email') }}</td>
                                        <td><a
                                                href="mailto:{{ html_decode($message->email) }}">{{ html_decode($message->email) }}</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Website') }}</td>
                                        <td>{{ html_decode($message?->website) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Subject') }}</td>
                                        <td>{{ html_decode($message->subject) }}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Message') }}</td>
                                        <td>{!! clean($message->message) !!}</td>
                                    </tr>

                                    <tr>
                                        <td>{{ __('Created at') }}</td>
                                        <td>{{ $message->created_at->format('h:iA, d M Y') }}</td>
                                    </tr>
                                    @adminCan('contact.message.delete')
                                        <tr>
                                            <td>{{ __('Action') }}</td>
                                            <td>
                                                <a href="{{ route('admin.contact-message-delete', $message->id) }}"
                                                        data-modal="#deleteModal" class="delete-btn btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                    {{ __('Delete') }}</a>
                                            </td>
                                        </tr>
                                    @endadminCan


                                </table>
                            </div>
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
