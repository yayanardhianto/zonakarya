@extends('admin.master_layout')
@section('title')
    <title>{{ __('Our Team') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Our Team') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Our Team') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    @adminCan('team.management')
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form class="on-change-submit" action="{{ route('admin.contact.ourteam') }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="d-flex align-items-center mb-0">
                                            <input type="hidden" value="inactive" name="contact_team_member"
                                                class="custom-switch-input">
                                            <input {{ $setting?->contact_team_member == 'active' ? 'checked' : '' }}
                                                type="checkbox" value="active" name="contact_team_member"
                                                class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span
                                                class="custom-switch-description">{{ __('User Can Contact Team Member') }}</span>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endadminCan

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Our Team')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.ourteam.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @adminCan('team.management')
                                                    <th>{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($teams as $index => $team)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $team->name }}</td>
                                                    <td>{{ $team->designation }}</td>
                                                    <td><img src="{{ asset($team->image) }}" alt="{{ $team->name }}"
                                                            class="rounded-circle my-2">
                                                    </td>
                                                    <td>
                                                        <input class="change-status" data-href="{{route('admin.ourteam.status-update',$team->id)}}"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $team->status == 'active' ? 'checked' : '' }}
                                                            data-toggle="toggle" data-on="{{ __('Active') }}"
                                                            data-off="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    @adminCan('team.management')
                                                        <td>
                                                            <x-admin.edit-button :href="route('admin.ourteam.edit', $team->id)" />
                                                            <a href="{{ route('admin.ourteam.destroy', $team->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a>
                                                        </td>
                                                    @endadminCan
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Team')" route="admin.ourteam.create" create="yes"
                                                    :message="__('No data found!')" colspan="6"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('team.management')
        <x-admin.delete-modal />
    @endadminCan
@endsection
