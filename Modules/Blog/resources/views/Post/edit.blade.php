@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Post') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Post') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog List') => route('admin.blogs.index'),
                __('Edit Post') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Edit Post')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.blogs.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.blogs.update', [
                                        'blog' => $blog->id,
                                        'code' => $code,
                                    ]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div
                                            class="form-group col-md-12 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-image-preview :image="$blog->image" required="0" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="title" data-translate="true" name="title"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                value="{{ $blog->getTranslation($code)->title }}" required="true" />
                                        </div>
                                        <div
                                            class="form-group col-md-8 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="slug" name="slug" label="{{ __('Slug') }}"
                                                placeholder="{{ __('Enter Slug') }}" value="{{ $blog->slug }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-select name="blog_category_id" id="blog_category_id"
                                                class="select2" label="{{ __('Category') }}" required="true">
                                                <x-admin.select-option value="" text="{{ __('Select Category') }}" />
                                                @foreach ($categories as $category)
                                                    <x-admin.select-option :selected="$category->id ==
                                                        old('blog_category_id', $blog->blog_category_id)" value="{{ $category->id }}"
                                                        text="{{ $category->title }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor-with-image data-translate="true" id="description"
                                                name="description" label="{{ __('Description') }}"
                                                value="{!! replaceImageSources($blog->getTranslation($code)->description) !!}" required="true" />
                                        </div>
                                        <div
                                            class="form-group col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="tags" name="tags" label="{{ __('Tags') }}"
                                                value="{!! $blog->tags !!}" class="tags" />
                                        </div>

                                        <div class="form-group col-md-8">
                                            <x-admin.form-input id="seo_title" name="seo_title"
                                                label="{{ __('SEO Title') }}" placeholder="{{ __('Enter SEO Title') }}"
                                                data-translate="true"
                                                value="{{ $blog->getTranslation($code)->seo_title }}" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="seo_description" name="seo_description"
                                                label="{{ __('SEO Description') }}"
                                                placeholder="{{ __('Enter SEO Description') }}" data-translate="true"
                                                value="{{ $blog->getTranslation($code)->seo_description }}"
                                                maxlength="2000" />
                                        </div>
                                        <div
                                            class="form-group col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-switch name="show_homepage" label="{{ __('Show on homepage') }}"
                                                :checked="$blog->show_homepage == 1" />
                                        </div>

                                        <div
                                            class="form-group col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-switch name="is_popular" label="{{ __('Mark as a Popular') }}"
                                                :checked="$blog->is_popular == 1" />
                                        </div>

                                        <div
                                            class="form-group col-md-4 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                :checked="$blog->status == 1" />
                                        </div>

                                    </div>
                                    <div class="row">
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

@push('js')
    @if ($code == $languages->first()->code)
        <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
        <script>
            $.uploadPreview({
                input_field: "#image-upload",
                preview_box: "#image-preview",
                label_field: "#image-label",
                label_default: "{{ __('Choose Image') }}",
                label_selected: "{{ __('Change Image') }}",
                no_label: false,
                success_callback: null
            });
            (function($) {
                "use strict";
                $(document).ready(function() {
                    $("#title").on("keyup", function(e) {
                        $("#slug").val(convertToSlug($(this).val()));
                    })
                });
            })(jQuery);

            function convertToSlug(Text) {
                return Text
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        </script>
    @else
        <script>
            'use strict';
            $('#translate-btn').on('click', function() {
                translateAllTo("{{ $code }}");
            })
        </script>
    @endif
@endpush
