@extends('admin.master_layout')
@section('title')
    <title>{{ __('Customizable Page List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Customizable Page List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Customizable Page List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Customizable Page List')" />
                                @adminCan('page.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.custom-pages.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="15%">{{ __('Slug') }}</th>
                                                @adminCan('page.update')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pages as $page)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><a target="_blank" href="">{{ $page->title }}</a>
                                                    </td>
                                                    <td>{{ $page->slug }}</td>
                                                    @adminCan('page.update')
                                                        <td>
                                                            <input class="change-status" data-href="{{route('admin.custom-pages.update-status',$page->id)}}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $page->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                                                        <td>
                                                            @adminCan('page.edit')
                                                                <x-admin.edit-button :href="route('admin.custom-pages.edit', [
                                                                    'page' => $page->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('page.delete')
                                                                @if (!in_array($page->slug, ['terms-conditions', 'privacy-policy']))
                                                                    <a href="{{ route('admin.custom-pages.destroy', $page->id) }}"
                                                                        data-modal="#deleteModal"
                                                                        class="delete-btn btn btn-danger btn-sm"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                                @endif
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Customizable Page')" route="admin.custom-pages.index"
                                                    create="no" :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $pages->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('page.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection