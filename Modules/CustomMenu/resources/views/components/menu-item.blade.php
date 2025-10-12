@php
    $language_code = !empty(request('code')) ? request('code') : getSessionLanguage();
@endphp
<li class="dd-item dd3-item" data-id="{{ $menu->id }}">
    <div class="dd-handle dd3-handle"></div>
    <div class="dd3-content">
        <div class="d-flex justify-content-between align-items-center w-100">
            <span>{{ $menu->getTranslation($language_code)?->label }}</span>
            <div class="dd-item-actions">
                @adminCan('menu.update')
                    <a href="javascript:;" class="m-1 text-white btn btn-sm btn-warning editItemData"
                        data-id="{{ $menu->id }}" data-label="{{ $menu?->getTranslation($language_code)?->label }}"
                        data-link="{{ $menu->link }}" data-custom_item="{{ $menu->custom_item }}"
                        data-open_new_tab="{{ $menu->open_new_tab }}" title="{{ __('Edit') }}"><i class="fa fa-edit"></i></a>
                @endadminCan
                @adminCan('menu.delete')
                    <a href="{{ route('admin.custom-menu.items.delete', $menu->id) }}" data-modal="#deleteModal"
                        class="delete-btn btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a>
                @endadminCan
            </div>

        </div>
    </div>
    @if (!empty($menu->child) && count($menu->child) > 0)
        <ol class="dd-list">
            @foreach ($menu->child as $child)
                <x-custommenu::menu-item :menu="$child" />
            @endforeach
        </ol>
    @endif
</li>
