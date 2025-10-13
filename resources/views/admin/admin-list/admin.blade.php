@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Admin') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Admin') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Admin') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Admin')" />
                                <div>
                                    @adminCan('admin.create')
                                        <x-admin.add-button :href="route('admin.admin.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Roles') }}</th>
                                                @adminCan('admin.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($admins as $index => $admin)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $admin->name }}</td>
                                                    <td>{{ $admin->email }}</td>
                                                    <td>
                                                        {{ $admin->getRoleNames() }}
                                                    </td>
                                                    @adminCan('admin.update')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.admin.status',$admin->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $admin->status == 'active' ? 'checked' : '' }}
                                                                data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                        <td>
                                                            @adminCan('admin.edit')
                                                                <x-admin.edit-button :href="route('admin.admin.edit', $admin->id)" />
                                                            @endadminCan
                                                            @adminCan('admin.delete')
                                                                <a href="{{ route('admin.admin.destroy', $admin->id) }}"
                                                                    data-modal="#deleteModal"
                                                                    class="delete-btn btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i></a>
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Admin')" route="admin.admin.create" create="yes"
                                                    :message="__('No data found!')" colspan="6" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right">
                                        {{ $admins->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('admin.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection