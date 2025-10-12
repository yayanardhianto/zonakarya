@extends('admin.master_layout')
@section('title')
    <title>{{ __('Country') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Country') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Country') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.country.index', ['code' => $language->code]) }}"><i
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
            </div>
            {{-- Search filter --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.country.index') }}" method="GET" class="on-change-submit card-body">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3 mb-md-0">
                                    <div class="input-group">
                                        <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                            class="form-control" placeholder="{{ __('Search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                    <input type="hidden" name="code" value="{{ $current_language->code }}">
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
                                        <option value="100" {{ '100' == request('par-page') ? 'selected' : '' }}>
                                            {{ __('100') }}
                                        </option>
                                        <option value="all" {{ 'all' == request('par-page') ? 'selected' : '' }}>
                                            {{ __('All') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Country List')" />
                                @adminCan('country.management')
                                    <div>
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                            class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Name') }}</th>
                                                @adminCan('country.management')
                                                    <th width="20%">{{ __('Status') }}</th>
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endadminCan
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($countries as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item?->getTranslation($code)?->name }}</td>
                                                    @adminCan('country.management')
                                                        <td>
                                                            <input class="change-status"
                                                                data-href="{{ route('admin.country.status-update', $item->id) }}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-on="{{ __('Active') }}" data-off="{{ __('Inactive') }}"
                                                                data-onstyle="success" data-offstyle="danger">
                                                        </td>
                                                        <td>
                                                            <a href="javascript:;" data-id="{{ $item->id }}"
                                                                data-name="{{ $item?->getTranslation($code)?->name }}"
                                                                data-slug="{{ $item->slug }}"
                                                                class="btn btn-warning btn-sm editItemData"><i
                                                                    class="fa fa-edit" aria-hidden="true"></i></a>


                                                            <a href="{{ route('admin.country.destroy', $item->id) }}"
                                                                data-modal="#deleteModal"
                                                                class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                    aria-hidden="true"></i></a>

                                                        </td>
                                                    @endadminCan
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Item')" route="admin.herosection.create"
                                                    create="no" :message="__('No data found!')" colspan="7"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $countries->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('country.management')
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.country.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New') }}</h5>
                        <button type="button" class="btn-close itemModalClose"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="code" value="{{ request('code') ?? getSessionLanguage() }}">
                        <div class="form-group col-12">
                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input data-translate="true" type="text" id="itemName" class="form-control" name="name">
                        </div>
                        @if ($code == $languages->first()->code)
                            <div class="form-group col-12 slug-box">
                                <label>{{ __('Slug') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="itemSlug" name="slug">
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-danger itemModalClose">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <x-admin.delete-modal />
    @endadminCan
@endsection
@push('js')
    <script>
        @adminCan('country.management')
        "use strict"
        $(document).ready(function() {
            $(document).on("click", ".editItemData", function(e) {
                var lang = "{{ $languages->first()->code }}";
                $("#ItemForm").attr("action", "{{ url('/admin/country') }}" + '/' + $(this).data('id'));
                $("#itemModal .modal-title").text("{{ __('Edit') }}");
                $("#itemName").val($(this).data('name'));
                if (lang) {
                    $("#itemSlug").val($(this).data('slug'));
                } else {
                    $(".slug-box").remove();
                }
                // Show the modal
                var itemModal = new bootstrap.Modal(document.getElementById('itemModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                itemModal.show();
            });

            $('.itemModalClose').on('click', function() {
                $("#ItemForm").attr("action", "{{ url('/admin/country') }}");
                $("#itemModal .modal-title").text("{{ __('Add New Item') }}");
                $("#itemName").val('');
                $("#itemSlug").val('');
                $('#itemModal').modal('hide');
            });
        });
        @endadminCan

        if ("{{ $languages->first()->code }}") {
            (function($) {
                "use strict";
                $(document).ready(function() {
                    $("#itemName").on("keyup", function(e) {
                        $("#itemSlug").val(convertToSlug($(this).val()));
                    })
                });
            })(jQuery);

            function convertToSlug(Text) {
                return Text
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        }
    </script>
@endpush
