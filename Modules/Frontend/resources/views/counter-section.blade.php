@extends('admin.master_layout')
@section('title')
    <title>{{ __('Counter Section') }}</title>
@endsection
@php
    $current_theme_title = collect(App\Enums\ThemeList::themes())->firstWhere('name', DEFAULT_HOMEPAGE)?->title
@endphp
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Counter Section') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Counter Section') => '#',
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
                                                href="{{ route('admin.counter-section.index', ['code' => $language->code]) }}"><i
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
                                <x-admin.form-title :text="__('Counter Section')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.counter-section.update', ['code' => $code]) }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="year_experience_count">{{ __('Total Years of Experience') }}<span
                                                        class="text-danger"></span></label>
                                                <input data-translate="true" type="text" id="year_experience_count"
                                                    name="year_experience_count"
                                                    value="{{ $counterSection?->global_content?->year_experience_count}}"
                                                    placeholder="{{ __('Total Years of Experience') }}" class="form-control">
                                                @error('year_experience_count')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="year_experience_title" data-translate="true" name="year_experience_title"
                                                    label="{{ __('Years of Experience') }}" placeholder="{{ __('Years of Experience Title') }}"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->year_experience_title }}"
                                                    required="true" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="year_experience_sub_title" name="year_experience_sub_title"
                                                    label="{{ __('Years of Experience Sub Title') }}"
                                                    placeholder="{{ __('Years of Experience Sub Title') }}" data-translate="true"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->year_experience_sub_title }}"
                                                    maxlength="1000" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="project_count">{{ __('Total Cities') }}<span
                                                        class="text-danger"></span></label>
                                                <input data-translate="true" type="text" id="project_count"
                                                    name="project_count"
                                                    value="{{ $counterSection?->global_content?->project_count }}"
                                                    placeholder="{{ __('Total Cities') }}" class="form-control">
                                                @error('project_count')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="project_title" data-translate="true" name="project_title"
                                                    label="{{ __('City Title') }}" placeholder="{{ __('City Title') }}"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->project_title }}"
                                                    required="true" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="project_sub_title" name="project_sub_title"
                                                    label="{{ __('City Sub Title') }}"
                                                    placeholder="{{ __('City Sub Title') }}" data-translate="true"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->project_sub_title }}"
                                                    maxlength="1000" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_count">{{ __('Total Stores') }}<span
                                                        class="text-danger"></span></label>
                                                <input data-translate="true" type="text" id="customer_count"
                                                    name="customer_count"
                                                    value="{{ $counterSection?->global_content?->customer_count }}"
                                                    placeholder="{{ __('Total Stores') }}" class="form-control">
                                                @error('customer_count')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-admin.form-input id="customer_title" data-translate="true" name="customer_title"
                                                    label="{{ __('Store Title') }}" placeholder="{{ __('Store Title') }}"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->customer_title }}"
                                                    required="true" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="customer_sub_title" name="customer_sub_title"
                                                    label="{{ __('Store Sub Title') }}"
                                                    placeholder="{{ __('Store Sub Title') }}" data-translate="true"
                                                    value="{{ $counterSection?->getTranslation($code)?->content?->customer_sub_title }}"
                                                    maxlength="1000" />
                                                <small>{{ __('use \ for break and {} for bold') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
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
