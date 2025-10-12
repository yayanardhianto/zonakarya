@extends('admin.master_layout')
@section('title')
    <title>{{ __('Hero Section') }}</title>
@endsection
@php
    $current_theme_title = collect(App\Enums\ThemeList::themes())->firstWhere('name', DEFAULT_HOMEPAGE)?->title
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Hero Section') }} ( {{$current_theme_title}} )" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Hero Section') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @if ($code !== $languages->first()->code)
                                <x-admin.button id="translate-btn" :text="__('Translate')" />
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.hero-section.index', ['code' => $language->code]) }}"><i
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
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Hero Section')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.hero-section.update', ['code' => $code]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" data-translate="true" name="title"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->title }}"
                                                    required="true" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="sub_title" name="sub_title"
                                                    label="{{ __('Sub Title') }}"
                                                    placeholder="{{ __('Enter Sub Title') }}" data-translate="true"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->sub_title }}"
                                                    maxlength="1000" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="action_button_text" data-translate="true"
                                                    name="action_button_text" label="{{ __('Button Text') }}"
                                                    placeholder="{{ __('Enter Button Text') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->action_button_text }}"/>
                                            </div>
                                        </div>

                                        <div class="col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="action_button_url" data-translate="true"
                                                    name="action_button_url" label="{{ __('Button url') }}"
                                                    placeholder="{{ __('Enter Button url') }}"
                                                    value="{{ $heroSection?->global_content?->action_button_url }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="total_customers" data-translate="true"
                                                    name="total_customers" label="{{ __('Total Customers') }}"
                                                    placeholder="{{ __('Enter Total Customers') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->total_customers }}"/>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-image-preview name="image" :image="$heroSection?->global_content?->image" required="0" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-image-preview div_id="image-preview-two" label_id="image-label-two" input_id="image-upload-two" name="image_two" :label="__('Total Customers')" :image="$heroSection?->global_content?->image_two" required="0" />
                                            </div>
                                        </div>
                                        <div class="text-center col-12">
                                            <x-admin.save-button :text="__('Save')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        'use strict';

        $('#translate-btn').on('click', function() {
            translateAllTo("{{ $code }}");
        })
        @if ($code == $languages->first()->code)
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label",
                label_default: "{{ __('Choose Image') }}",
                label_selected: "{{ __('Change Image') }}",
                no_label: false,
                success_callback: null
            });
            $.uploadPreview({
                input_field: "#image-upload-two",
                preview_box: "#image-preview-two",
                label_field: "#image-label-two",
                label_default: "{{ __('Choose Image') }}",
                label_selected: "{{ __('Change Image') }}",
                no_label: false,
                success_callback: null
            });
        @endif
    </script>
@endpush
