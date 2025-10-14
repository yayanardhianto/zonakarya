@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['contact_page']['seo_title'])
@section('meta_description', $seo_setting['contact_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->contact_page_breadcrumb_image" :title="$contactSection?->breadcrumb_title ?? __('Contact')" />

    @if($contactSection?->page_title)
    <!-- Page Title Area -->
    <div class="page-title-area space">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title text-center">
                        <h1 class="sec-title">{{ $contactSection->page_title }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Feature Area -->
    <div class="feature-area-1 space">
        <div class="container">
            <div class="row gy-4 align-items-center justify-content-center">
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <img src="{{ asset('frontend/images/location-pin-alt.svg') }}" alt="icon">
                        </div>
                        <div class="feature-card-details">
                            <h4 class="feature-card-title">
                                <a href="javascript:;">{{ $contactSection?->headquarters_title ?? __('Headquarters') }}</a>
                            </h4>
                            <p class="feature-card-text contact-page-address">{{ $contactSection?->address }}</p>

                            <a href="{{ asset($contactSection?->map) }}" target="_blank" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ $contactSection?->get_direction_text ?? __('Get direction') }}</span>
                                    <span class="effect-1">{{ $contactSection?->get_direction_text ?? __('Get direction') }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <img src="{{ asset('frontend/images/speech-bubble.svg') }}" alt="icon">
                        </div>
                        <div class="feature-card-details">
                            <h4 class="feature-card-title">
                                <a href="javascript:;">{{ $contactSection?->email_title ?? __('Email Address') }}</a>
                            </h4>
                            <p class="feature-card-text mb-0">{{ $contactSection?->email }}</p>
                            @if($contactSection?->show_second_email && $contactSection?->email_two)
                                <p class="feature-card-text">{{ $contactSection?->email_two }}</p>
                            @endif
                            <a href="mailto:{{ $contactSection?->email }}" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ $contactSection?->send_message_text ?? __('Send message') }}</span>
                                    <span class="effect-1">{{ $contactSection?->send_message_text ?? __('Send message') }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-card-icon">
                            <img src="{{ asset('frontend/images/phone.svg') }}" alt="icon">
                        </div>
                        <div class="feature-card-details">
                            <h4 class="feature-card-title">
                                <a href="javascript:;">{{ $contactSection?->phone_title ?? __('Phone Number') }}</a>
                            </h4>
                            <p class="feature-card-text mb-0">{{ $contactSection?->phone }} </p>
                            @if($contactSection?->show_second_phone && $contactSection?->phone_two)
                                <p class="feature-card-text">{{ $contactSection?->phone_two }} </p>
                            @endif

                            <a href="tel:{{ $contactSection?->phone }}" class="link-btn">
                                <span class="link-effect">
                                    <span class="effect-1">{{ $contactSection?->call_anytime_text ?? __('Call anytime') }}</span>
                                    <span class="effect-1">{{ $contactSection?->call_anytime_text ?? __('Call anytime') }}</span>
                                </span>
                                <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Area -->
    <div class="contact-area-1 space bg-theme">
        <div class="contact-map shape-mockup wow img-custom-anim-left" data-wow-duration="1.5s" data-wow-delay="0.2s"
            data-left="0" data-top="-100px" data-bottom="140px">
            <iframe src="{{ $contactSection?->map }}" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-end">
                <div class="col-lg-6">
                    <div class="contact-form-wrap">
                        <div class="title-area mb-30">
                            <h2 class="sec-title">{{ $contactSection?->form_title ?? __('Have Any Project on Your Mind?') }}</h2>
                            <p>{{ $contactSection?->form_subtitle ?? __("Great! We're excited to hear from you and let's start something") }}</p>
                        </div>
                        <form action="{{ route('send-contact-message') }}" method="POST" id="contact-form" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control style-border text-white" name="name"
                                            placeholder="{{ $contactSection?->full_name_label ?? __('Full name') }}*" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control style-border text-white" name="email"
                                            placeholder="{{ $contactSection?->email_label ?? __('Email address') }}*" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                @if($contactSection?->show_website_field)
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control style-border text-white" name="website"
                                            placeholder="{{ $contactSection?->website_label ?? __('Website link') }}" value="{{ old('website') }}">
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control style-border text-white" name="subject"
                                            placeholder="{{ $contactSection?->subject_label ?? __('Subject') }}*" value="{{ old('subject') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea name="message" placeholder="{{ $contactSection?->message_label ?? __('How Can We Help You') }}*" class="form-control style-border text-white" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @if ($setting?->recaptcha_status == 'active')
                                <div class="form-group mb-0 col-12">
                                    <div class="g-recaptcha" data-sitekey="{{ $setting?->recaptcha_site_key }}"></div>
                                </div>
                            @endif
                            <div class="form-btn col-12">
                                <button type="submit" class="btn mt-20">
                                    <span class="link-effect text-uppercase">
                                        <span class="effect-1">{{ $contactSection?->submit_button_text ?? __('Send message') }}</span>
                                        <span class="effect-1">{{ $contactSection?->submit_button_text ?? __('Send message') }}</span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Contact form submission handler
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = form.serialize();
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.find('.effect-1').first().html();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true);
        submitBtn.find('.effect-1').html('Sending... <i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Clear form
                    form.find('input, textarea').val('');
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                } else {
                    // Show error message
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message);
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while sending your message.';
                
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON?.message;
                    if (errors) {
                        errorMessage = Object.values(errors).flat().join('\n');
                    }
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMessage);
                } else {
                    alert('Error: ' + errorMessage);
                }
            },
            complete: function() {
                // Re-enable button and restore text
                submitBtn.prop('disabled', false);
                submitBtn.find('.effect-1').html(originalText);
            }
        });
    });
});
</script>
@endpush

@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
