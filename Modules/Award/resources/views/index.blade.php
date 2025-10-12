@extends('admin.master_layout')
@section('title')
    <title>{{ __('Timeline Section') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Timeline Section') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Timeline Section') => '#',
            ]" />
            <!-- <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.award.index', ['code' => $language->code]) }}"><i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-2 alert alert-danger" role="alert">
                                @php
                                    $current_language = $languages->where('code', request()->get('code'))->first();
                                @endphp
                                <p>{{ __('Your editing mode') }}:<b> {{ $current_language?->name }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="section-body">
                <div class="mt-4 row">
                    @if (checkAdminHasPermission('award.view'))
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('Items') }}</h4>
                                    @if (checkAdminHasPermission('award.management'))
                                        <div>
                                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                                class="btn btn-primary"><i class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="10%">{{ __('SN') }}</th>
                                                    <th width="5%">{{ __('Year') }}</th>
                                                    <th width="10%">{{ __('Title') }}</th>
                                                    <th width="20%">{{ __('Sub Title') }}</th>
                                                    <th width="10%">{{ __('URL') }}</th>
                                                    <th width="5%">{{ __('Tag') }}</th>
                                                    <th width="10%">{{ __('Status') }}</th>
                                                    <th width="10%">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($awards as $item)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $item?->getTranslation($code)?->year }}</td>
                                                        <td>{{ $item?->getTranslation($code)?->title }}</td>
                                                        <td>{{ $item?->getTranslation($code)?->sub_title }}</td>
                                                        <td>{{ $item?->url }}</td>
                                                        <td>{{ $item?->getTranslation($code)?->tag }}</td>
                                                        <td>
                                                            <input class="change-status"
                                                                data-href="{{ route('admin.award.status-update', $item->id) }}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-on="{{ __('Active') }}"
                                                                data-off="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                        <td>
                                                            @if (checkAdminHasPermission('award.management'))
                                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                                    data-url="{{ $item?->url }}"
                                                                    data-year="{{ $item?->getTranslation($code)?->year }}"
                                                                    data-title="{{ $item?->getTranslation($code)?->title }}"
                                                                    data-subtitle="{{ $item?->getTranslation($code)?->sub_title }}"
                                                                    data-tag="{{ $item?->getTranslation($code)?->tag }}"
                                                                    class="btn btn-warning btn-sm editItemData"><i
                                                                        class="fa fa-edit" aria-hidden="true"></i></a>
                                                                <a href="{{ route('admin.award.destroy', $item->id) }}"
                                                                    data-modal="#deleteModal"
                                                                    class="delete-btn btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i></a>
                                                            @endif
                                                    </tr>
                                                @empty
                                                    <x-empty-table :name="__('Item')" route="" create="no"
                                                        :message="__('No data found!')" colspan="8" />
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-right">
                                        {{ $awards->onEachSide(0)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('award.management'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.award.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Item') }}</h5>
                        <button type="button" class="btn-close itemModalClose"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="code" value="{{ request('code') ?? getSessionLanguage() }}">
                        <div class="form-group col-12">
                            <x-admin.form-input id="itemYear" data-translate="true" name="year"
                                label="{{ __('Year') }}" placeholder="{{ __('Enter Year') }}" required="true" />
                        </div>
                        <div class="form-group col-12">
                            <div class="form-group col-12">
                                <x-admin.form-input id="itemTitle" data-translate="true" name="title"
                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}" required="true" />
                            </div>
                        </div>
                        <div class="form-group col-12">
                            <x-admin.form-textarea id="itemSubTitle" name="sub_title" label="{{ __('Sub Title') }}"
                                placeholder="{{ __('Enter Sub Title') }}" maxlength="255" required="true" />
                        </div>
                        <div class="form-group col-12 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                            <x-admin.form-input id="itemUrl" name="url" label="{{ __('URL') }}"
                                placeholder="{{ __('Enter URL') }}" />
                        </div>
                        <div class="form-group col-12">
                            <x-admin.form-input id="itemTag" data-translate="true" name="tag"
                                label="{{ __('Tag') }}" placeholder="{{ __('Enter Tag') }}" required="true" />
                        </div>

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-danger itemModalClose">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <x-admin.delete-modal />
@endsection
@push('js')
    <script>
        "use strict"
        $(document).ready(function() {
            $(document).on("click", ".editItemData", function(e) {
                $("#ItemForm").attr("action", "{{ url('/admin/award') }}" + '/' + $(this).data('id'));
                $("#itemModal .modal-title").text("{{ __('Edit Item') }}");
                $("#itemUrl").val($(this).data('url'));
                $("#itemYear").val($(this).data('year'));
                $("#itemTitle").val($(this).data('title'));
                $("#itemSubTitle").val($(this).data('subtitle'));
                $("#itemTag").val($(this).data('tag'));
                var itemModal = new bootstrap.Modal(document.getElementById('itemModal'), {
                    backdrop: 'static',
                    keyboard: false
                });

                // Show the modal
                itemModal.show();
            });
            
            $('.itemModalClose').on('click', function() {
                $("#ItemForm").attr("action", "{{ url('/admin/award') }}");
                $("#itemModal .modal-title").text("{{ __('Add New Item') }}");
                $("#itemUrl").val('');
                $("#itemYear").val('');
                $("#itemTitle").val('');
                $("#itemSubTitle").val('');
                $("#itemTag").val('');
                $('#itemModal').modal('hide');
            });
        });
    </script>
@endpush
