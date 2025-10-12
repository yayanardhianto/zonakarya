@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['faq_page']['seo_title'])
@section('meta_description', $seo_setting['faq_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->faq_page_breadcrumb_image" :title="__('FAQ')" />

    <!-- Main Area -->
    <div class="faq-area-2 space overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="accordion-area accordion" id="faqAccordion">
                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-card style2 {{ $index == 0 ? 'active' : '' }}">
                                <div class="accordion-header" id="collapse-item-{{ $index + 1 }}">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index + 1 }}"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse-{{ $index + 1 }}">{{ $faq?->question }}</button>
                                </div>
                                <div id="collapse-{{ $index + 1 }}"
                                    class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                    aria-labelledby="collapse-item-{{ $index + 1 }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p class="faq-text">{{ $faq?->answer }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Area -->
    <div class="feature-area-1 space-bottom">
        <div class="container">
            <div class="row gy-4 align-items-center justify-content-center">
                <div class="col-xxl-6 col-xl-6">
                    <div class="feature-card style-grid">
                        <div class="feature-card-icon">
                            <img src="{{ asset('frontend/images/phone.svg') }}" alt="icon">
                        </div>
                        <div class="feature-card-details">
                            <h4 class="feature-card-title">
                                <a href="{{ route('contact') }}">{{ __('Contact with Us') }}</a>
                            </h4>
                            <p class="feature-card-text">
                                {{ __('Good website tells a story that will make users fully immerse themselves operating') }}
                            </p>
                            <a href="tel:{{ $contactSection?->phone }}" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ $contactSection?->phone }}</span>
                                    <span class="effect-1">{{ $contactSection?->phone }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6">
                    <div class="feature-card style-grid">
                        <div class="feature-card-icon">
                            <img src="{{ asset('frontend/images/speech-bubble.svg') }}" alt="icon">
                        </div>
                        <div class="feature-card-details">
                            <h4 class="feature-card-title">
                                <a href="{{ route('contact') }}">{{ __('Send a Message') }}</a>
                            </h4>
                            <p class="feature-card-text">
                                {{ __('Good website tells a story that will make users fully immerse themselves operating') }}
                            </p>
                            <a href="mailto:{{ $contactSection?->email }}" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ $contactSection?->email }}</span>
                                    <span class="effect-1">{{ $contactSection?->email }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
