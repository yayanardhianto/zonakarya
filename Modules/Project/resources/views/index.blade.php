@extends('admin.master_layout')
@section('title')
    <title>{{ __('Project List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Project List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Project List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.project.index') }}" method="GET"
                                    class="on-change-submit card-body">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                    class="form-control" placeholder="{{ __('Search') }}">
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
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
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
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
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
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
                                <x-admin.form-title :text="__('Project List')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.project.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                <th>{{ __('Title') }}</th>
                                                @adminCan('project.management')
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($projects as $project)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><img src="{{ asset($project->image) }}"
                                                            alt="{{ $project->title }}" class="rounded-circle my-2"></td>
                                                    <td>{{ $project->title }}</td>
                                                    @adminCan('project.management')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.project.status-update',$project->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $project->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-on="{{ __('Active') }}" data-off="{{ __('Inactive') }}"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        </td>
                                                        <td>
                                                            <x-admin.edit-button :href="route('admin.project.edit', [
                                                                'project' => $project->id,
                                                                'code' => getSessionLanguage(),
                                                            ])" />
                                                                <a href="{{ route('admin.project.destroy', $project->id) }}"
                                                                    data-modal="#deleteModal"
                                                                    class="delete-btn btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i></a>
                                                        </td>
                                                    @endadminCan

                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Project')" route="admin.project.create" create="yes"
                                                    :message="__('No data found!')" colspan="5"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $projects->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('project.management')
        <x-admin.delete-modal />
    @endadminCan
@endsection
