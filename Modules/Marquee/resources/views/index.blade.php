@extends('admin.master_layout')
@section('title')
    <title>{{ __('Marquee Section') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Text Slider') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Text Slider') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    @if (checkAdminHasPermission('marquee.view'))
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h4>{{ __('Items') }}</h4>
                                    @if (checkAdminHasPermission('marquee.management'))
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
                                                    <th width="30%">{{ __('Title') }}</th>
                                                    <th width="20%">{{ __('Status') }}</th>
                                                    <th width="20%">{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($marquees as $item)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $item?->getTranslation($code)?->title }}</td>
                                                        <td>
                                                            <input class="change-status"
                                                                data-href="{{ route('admin.marquee.status-update', $item->id) }}"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-on="{{ __('Active') }}"
                                                                data-off="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                        <td>
                                                            @if (checkAdminHasPermission('marquee.management'))
                                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                                    data-title="{{ $item?->getTranslation($code)?->title }}"
                                                                    class="btn btn-warning btn-sm editItemData"><i
                                                                        class="fa fa-edit" aria-hidden="true"></i></a>
                                                                <a href="{{ route('admin.marquee.destroy', $item->id) }}"
                                                                    data-modal="#deleteModal"
                                                                    class="delete-btn btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i></a>
                                                            @endif
                                                    </tr>
                                                @empty
                                                    <x-empty-table :name="__('Item')" route="" create="no"
                                                        :message="__('No data found!')" colspan="7"></x-empty-table>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-right">
                                        {{ $marquees->onEachSide(0)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('marquee.management'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.marquee.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Item') }}</h5>
                        <button type="button" class="btn-close itemModalClose"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="code" value="{{ request('code') ?? getSessionLanguage() }}">
                        <div class="form-group col-12">
                            <label>{{ __('Title') }} <span class="text-danger">*</span></label>
                            <input data-translate="true" type="text" id="itemTitle" class="form-control" name="title">
                            @error('title')
                                <span class="text-danger error-message">{{ $message }}</span>
                            @enderror
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
                $("#ItemForm").attr("action", "{{ url('/admin/marquee') }}" + '/' + $(this).data('id'));
                $("#itemModal .modal-title").text("{{ __('Edit Item') }}");
                $("#itemTitle").val($(this).data('title'));
                // Show the modal
                var itemModal = new bootstrap.Modal(document.getElementById('itemModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                itemModal.show();
            });
            $('.itemModalClose').on('click', function() {
                $("#ItemForm").attr("action", "{{ url('/admin/marquee') }}");
                $("#itemModal .modal-title").text("{{ __('Add New Item') }}");
                $("#itemTitle").val('');
                $('#itemModal').modal('hide');
            });
        });
    </script>
@endpush
