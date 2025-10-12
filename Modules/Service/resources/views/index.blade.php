@extends('admin.master_layout')
@section('title')
    <title>{{ __('Service List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Service List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.service.index') }}" method="GET"
                                    class="on-change-submit card-body">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                    class="form-control" placeholder="{{ __('Search') }}">
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <select name="status" id="status" class="form-control form-select">
                                                <option value="">{{ __('Select Status') }}</option>
                                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                                    {{ __('Active') }}
                                                </option>
                                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                                    {{ __('In-Active') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <select name="order_by" id="order_by" class="form-control form-select">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="1" {{ request('order_by') == '1' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="0" {{ request('order_by') == '0' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <select name="par-page" id="par-page" class="form-control form-select">
                                                <option value="">{{ __('Per Page') }}</option>
                                                <option value="10" {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('10') }}
                                                </option>
                                                <option value="50" {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('50') }}
                                                </option>
                                                <option value="100"
                                                    {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('100') }}
                                                </option>
                                                <option value="all"
                                                    {{ 'all' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('All') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Service List')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.service.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="10%">{{ __('Image') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                @adminCan('service.management')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($services as $service)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><img src="{{ asset($service?->icon) }}"
                                                            class="rounded-circle my-2"></td>
                                                    <td>{{ $service->title }}</td>
                                                    @adminCan('service.management')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.service.status-update',$service->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $service->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-on="{{ __('Active') }}" data-off="{{ __('Inactive') }}"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        </td>
                                                        <td>
                                                            <x-admin.edit-button :href="route('admin.service.edit', [
                                                                'service' => $service->id,
                                                                'code' => getSessionLanguage(),
                                                            ])" />
                                                            <a href="{{ route('admin.service.destroy', $service->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a>
                                                        </td>
                                                    @endadminCan
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Service')" route="admin.service.create" create="yes"
                                                    :message="__('No data found!')" colspan="7"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $services->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('service.management')
        <x-admin.delete-modal />
    @endadminCan
@endsection
