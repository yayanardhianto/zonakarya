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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" data-translate="true" name="title"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->title }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="title_two" data-translate="true" name="title_two"
                                                    label="{{ __('Title Part Two') }}"
                                                    placeholder="{{ __('Enter Title Part Two') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->title_two }}"
                                                    required="true" />
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


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="action_button_text" data-translate="true"
                                                    name="action_button_text" label="{{ __('Button Text') }}"
                                                    placeholder="{{ __('Enter Button Text') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->action_button_text }}"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="action_button_url" data-translate="true"
                                                    name="action_button_url" label="{{ __('Button url') }}"
                                                    placeholder="{{ __('Enter Button url') }}"
                                                    value="{{ $heroSection?->global_content?->action_button_url }}" />
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-admin.form-input id="hero_year_text" data-translate="true"
                                                    name="hero_year_text" label="{{ __('Hero Year Text') }}"
                                                    placeholder="{{ __('Enter Hero Year Text') }}"
                                                    value="{{ $heroSection?->getTranslation($code)?->content?->hero_year_text }}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-image-preview :label="__('Hero Year Image')" name="hero_year_image"
                                                    :image="$heroSection?->global_content?->hero_year_image" required="0" />
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
        @endif
    </script>
@endpush
