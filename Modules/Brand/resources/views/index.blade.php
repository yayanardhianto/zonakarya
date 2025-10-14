@extends('admin.master_layout')
@section('title')
    <title>{{ __('Brand List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Brand List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Brand List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Brand List') }}</h4>
                                <div>
                                    <a href="{{ route('admin.brand.create') }}" class="btn btn-primary"><i
                                            class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400 brand">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Short Description') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($brands as $brand)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="bg-transparent-black"><img src="{{ asset($brand->image) }}"
                                                            class="w_60px" alt=""></td>
                                                    <td>{{ $brand->name }}</td>
                                                    <td>
                                                        <input class="change-status" data-href="{{route('admin.brand.status-update',$brand->id)}}"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $brand->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-on="{{ __('Active') }}" data-off="{{ __('Inactive') }}"
                                                            data-onstyle="success" data-offstyle="danger">
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            <x-admin.edit-button :href="route('admin.brand.edit', $brand->id)" />
                                                            <a href="{{ route('admin.brand.destroy', $brand->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Brand')" route="admin.brand.create" create="yes"
                                                    :message="__('No data found!')" colspan="5"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $brands->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
@endsection
