@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQS') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create FAQ') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQS') => route('admin.faq.index'),
                __('Create FAQ') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Create FAQ')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.faq.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.faq.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="question"  name="question" label="{{ __('Question') }}" placeholder="{{ __('Enter Question') }}" value="{{ old('question') }}" required="true"/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}" placeholder="{{ __('Enter Answer') }}" value="{{ old('answer') }}" maxlength="1000" required="true"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
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
