@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQS') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit FAQ') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQS') => route('admin.faq.index'),
                __('Edit FAQ') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('faq.translate')
                                @if ($code !== $languages->first()->code)
                                    <x-admin.button id="translate-btn" :text="__('Translate')" />
                                @endif
                            @endadminCan
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.faq.edit', ['faq' => $faq->id, 'code' => $language->code]) }}"><i
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
                                <x-admin.form-title :text="__('Edit FAQ')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.faq.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.faq.update', ['faq' => $faq->id, 'code' => $code]) }}"
                                    method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input data-translate="true" id="question" name="question"
                                                    label="{{ __('Question') }}" placeholder="{{ __('Enter Question') }}"
                                                    value="{{ old('question', $faq->getTranslation($code)->question) }}"
                                                    required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea data-translate="true" id="answer" name="answer"
                                                    label="{{ __('Answer') }}" placeholder="{{ __('Enter Answer') }}"
                                                    value="{{ old('answer', $faq->getTranslation($code)->answer) }}"
                                                    maxlength="1000" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.update-button :text="__('Update')" />
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
@if ($code != $languages->first()->code)
    @push('js')
        <script>
            'use strict';
            $('#translate-btn').on('click', function() {
                translateAllTo("{{ $code }}");
            })
        </script>
    @endpush
@endif
