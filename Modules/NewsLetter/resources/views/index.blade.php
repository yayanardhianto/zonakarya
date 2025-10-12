@extends('admin.master_layout')
@section('title')
    <title>{{ __('Subscriber List') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Subscriber List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Subscriber List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Subscribed at') }}</th>
                                                @adminCan('newsletter.delete')
                                                    <th>{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($newsletters as $index => $item)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ html_decode($item->email) }}</td>
                                                    <td>{{ formattedDateTime($item->created_at) }}</td>
                                                    @adminCan('newsletter.delete')
                                                        <td>
                                                            <a href="{{ route('admin.subscriber-delete', $item->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a>
                                                        </td>
                                                    @endadminCan
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="4" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
    @adminCan('newsletter.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
