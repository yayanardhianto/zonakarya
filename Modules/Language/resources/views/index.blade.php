@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Language') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Language')" />
                                <div>
                                    @adminCan('language.create')
                                        <x-admin.add-button :href="route('admin.languages.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Code') }}</th>
                                                <th>{{ __('Direction') }}</th>
                                                @adminCan('language.update')
                                                    <th>{{ __('Default') }}</th>
                                                    <th>{{ __('Translations') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('language.edit') || checkAdminHasPermission('language.delete'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($languages as $language)
                                                <tr>
                                                    <td>
                                                        {{ $loop->index + 1 }}</td>
                                                    <td>
                                                        {{ $language->name }}
                                                    </td>
                                                    <td>
                                                        {{ $language->code }}
                                                    </td>
                                                    <td>
                                                        {{ $language->direction == 'ltr' ? __('Left to right') : __('Right to left') }}
                                                    </td>
                                                    @adminCan('language.update')
                                                        <td>
                                                            <a class="change-language-status" data-column="is_default" href="{{route('admin.languages.update-status',$language->id)}}">
                                                                <input class="self-default-{{ $language->id }} default-status"
                                                                    id="status_toggle" type="checkbox"
                                                                    {{ $language->is_default ? 'checked' : '' }}
                                                                    data-toggle="toggle" data-onlabel="{{ __('Yes') }}"
                                                                    data-offlabel="{{ __('No') }}" data-onstyle="success"
                                                                    data-offstyle="danger">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <a class="btn btn-primary"
                                                                    href="{{ route('admin.languages.edit-static-languages', $language->code) }}"
                                                                    title="{{ __('Edit Language') }}">
                                                                    <i class="fas fa-language"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a class="change-language-status" data-column="status" href="{{route('admin.languages.update-status',$language->id)}}">
                                                                <input id="status_toggle" type="checkbox"
                                                                    {{ $language->status ? 'checked' : '' }}
                                                                    data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                    data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                    data-offstyle="danger">
                                                            </a>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('language.edit') || checkAdminHasPermission('language.delete'))
                                                        <td class="text-center">
                                                            <div>
                                                                @adminCan('language.edit')
                                                                    <x-admin.edit-button :href="route(
                                                                        'admin.languages.edit',
                                                                        $language->id,
                                                                    )" />
                                                                @endadminCan
                                                                @adminCan('language.delete')
                                                                    <a href="{{ route('admin.languages.destroy', $language->id) }}"
                                                                        data-modal="#deleteModal"
                                                                        class="delete-btn btn btn-danger btn-sm"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                                @endadminCan
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Language')" route="admin.languages.create"
                                                    create="yes" :message="__('No data found!')" colspan="8" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $languages->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('language.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('language.update')
        "use strict"
        $(document).ready(function() {
            $(document).on("change", ".change-language-status", function(e) {
                e.preventDefault();
                var url = $(this).prop("href");
                var type = $(this).data("column");
                $.ajax({
                    type: "put",
                    data: {
                        _token: '{{ csrf_token() }}',
                        column: type
                    },
                    url: url,
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            if (type == 'is_default') {
                                window.location.reload();
                            }
                        } else {
                            toastr.warning(response.message);
                            if (!response.status) {
                                window.location.reload();
                            }
                        }
                    },
                    error: function(err) {
                        if (err.responseJSON && err.responseJSON.message) {
                            toastr.error(err.responseJSON.message);
                        } else {
                            toastr.error("{{ __('Something went wrong, please try again') }}");
                        }
                    }
                })
            });
        });
        @endadminCan
    </script>
@endpush
