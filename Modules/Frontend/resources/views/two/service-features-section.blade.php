@extends('admin.master_layout')
@section('title')
    <title>{{ __('Service Feature Section') }}</title>
@endsection
@php
    $current_theme_title = collect(App\Enums\ThemeList::themes())->firstWhere('name', DEFAULT_HOMEPAGE)?->title
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Service Feature Section') }} ( {{$current_theme_title}} )" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service Feature Section') => '#',
            ]" />
            <!-- <div class="section-body row">
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
                                                href="{{ route('admin.service-features-section.index', ['code' => $language->code]) }}"><i
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Service Feature Section')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.service-features-section.update', ['code' => $code]) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="title" data-translate="true" name="title"
                                                    label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->title }}"
                                                    required="true" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="sub_title" name="sub_title"
                                                    label="{{ __('Sub Title') }}"
                                                    placeholder="{{ __('Enter Sub Title') }}" data-translate="true"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->sub_title }}"
                                                    maxlength="1000" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="skill_percentage_one" name="skill_percentage_one"
                                                    label="{{ __('Skill One Percentage') }}"
                                                    placeholder="{{ __('Enter Skill One Percentage') }}"
                                                    value="{{ $featureSection?->global_content?->skill_percentage_one }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-{{ $code == $languages->first()->code ? '9' : '6' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="skill_title_one" data-translate="true"
                                                    name="skill_title_one" label="{{ __('Skill One Title') }}"
                                                    placeholder="{{ __('Enter Skill One Title') }}"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->skill_title_one }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="skill_percentage_two" name="skill_percentage_two"
                                                    label="{{ __('Skill Two Percentage') }}"
                                                    placeholder="{{ __('Enter Skill Two Percentage') }}"
                                                    value="{{ $featureSection?->global_content?->skill_percentage_two }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-{{ $code == $languages->first()->code ? '9' : '6' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="skill_title_two" data-translate="true"
                                                    name="skill_title_two" label="{{ __('Skill Two Title') }}"
                                                    placeholder="{{ __('Enter Skill Two Title') }}"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->skill_title_two }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="skill_percentage_three" name="skill_percentage_three"
                                                    label="{{ __('Skill Three Percentage') }}"
                                                    placeholder="{{ __('Enter Skill Three Percentage') }}"
                                                    value="{{ $featureSection?->global_content?->skill_percentage_three }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-{{ $code == $languages->first()->code ? '9' : '6' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="skill_title_three" data-translate="true"
                                                    name="skill_title_three" label="{{ __('Skill Three Title') }}"
                                                    placeholder="{{ __('Enter Skill Three Title') }}"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->skill_title_three }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="skill_percentage_four" name="skill_percentage_four"
                                                    label="{{ __('Skill Four Percentage') }}"
                                                    placeholder="{{ __('Enter Skill Four Percentage') }}"
                                                    value="{{ $featureSection?->global_content?->skill_percentage_four }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-{{ $code == $languages->first()->code ? '9' : '6' }}">
                                            <div class="form-group">
                                                <x-admin.form-input id="skill_title_four" data-translate="true"
                                                    name="skill_title_four" label="{{ __('Skill Four Title') }}"
                                                    placeholder="{{ __('Enter Skill Four Title') }}"
                                                    value="{{ $featureSection?->getTranslation($code)?->content?->skill_title_four }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <x-admin.form-image-preview name="image" :image="$featureSection?->global_content?->image"
                                                    required="0" />
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
