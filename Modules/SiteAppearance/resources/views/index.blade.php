@extends('admin.master_layout')
@section('title')
    <title>{{ __('Themes') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Themes') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Themes') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <form class="on-change-submit" action="{{ route('admin.show.all.homepage') }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="d-flex align-items-center mb-0">
                                        <input type="hidden" value="0" name="show_all_homepage"
                                            class="custom-switch-input">
                                        <input {{ $setting?->show_all_homepage == '1' ? 'checked' : '' }} type="checkbox"
                                            value="1" name="show_all_homepage" class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">{{ __('Show All Homepage') }}</span>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($themes as $theme)
                        @php
                            $is_active = DEFAULT_HOMEPAGE == $theme?->name;
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3 theme_item">
                            <h6 class="text-center d-flex justify-content-center gap-1">
                                {{ $theme?->title }}
                                @if ($is_active)
                                    <div class="badges">
                                        <span class="badge badge-success m-0">{{ __('Active') }}</span>
                                    </div>
                                @endif
                            </h6>
                            <div class="theme_screenshot shadow">
                                <img src="{{ asset($theme?->screenshot) }}" alt="{{ $theme?->title }}">
                            </div>
                            @unless ($is_active)
                                <form id="theme-update-{{ $theme?->name }}"
                                    action="{{ route('admin.site-appearance.update') }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                    <x-admin.form-input type="hidden" id="{{ $theme?->name }}" name="theme"
                                        value="{{ $theme?->name }}" required />
                                </form>
                                <button data-form-id="#theme-update-{{ $theme?->name }}" class="btn btn-sm btn-primary activate-default-theme">
                                    {{ __('Activate') }}
                                </button>
                            @endunless
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection