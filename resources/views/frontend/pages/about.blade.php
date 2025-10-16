@extends('frontend.layouts.master')

@section('meta_title', $seo_setting['about_page']['seo_title'])
@section('meta_description', $seo_setting['about_page']['seo_description'])

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :image="$setting?->about_page_breadcrumb_image" :title="$setting?->about_page_title ?? __('About')" />

    @if($sections->where('name', 'counter_section')->first()?->is_active)
    <!-- Counter Area -->
    <div class="counter-area-1 space overflow-hidden">
        <div class="container">
            <div class="row gy-40 align-items-center justify-content-lg-between justify-content-center">
                <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                    <div class="counter-card">
                        <h3 class="counter-card_number">
                            <span class="counter-number">{{$counterSection?->global_content?->year_experience_count}}</span>+
                        </h3>
                        <h4 class="counter-card_title">{{$counterSection?->content?->year_experience_title}}</h4>
                        <div class="counter-card_text">
                            {!! clean(processText($counterSection?->content?->year_experience_sub_title)) !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                    <div class="counter-card">
                        <h3 class="counter-card_number">
                            <span class="counter-number">{{$counterSection?->global_content?->project_count}}</span>+
                        </h3>
                        <h4 class="counter-card_title">{{$counterSection?->content?->project_title}}</h4>
                        <div class="counter-card_text">
                            {!! clean(processText($counterSection?->content?->project_sub_title)) !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-auto col-lg-4 col-md-6 counter-divider">
                    <div class="counter-card">
                        <h3 class="counter-card_number">
                            <span class="counter-number">{{$counterSection?->global_content?->customer_count}}</span>+
                        </h3>
                        <h4 class="counter-card_title">{{$counterSection?->content?->customer_title}}</h4>
                        <div class="counter-card_text">
                            {!! clean(processText($counterSection?->content?->customer_sub_title)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($sections->where('name', 'choose_us_section')->first()?->is_active)
    <!-- Main Area -->
    <div class="why-area-1 space bg-theme">
        <div class="why-img-1-1 shape-mockup wow img-custom-anim-right" data-wow-duration="1.5s" data-wow-delay="0.2s"
            data-right="0" data-top="-100px" data-bottom="140px">
            <img src="{{asset($chooseUsSection?->global_content?->image)}}" alt="{{$chooseUsSection?->content?->title}}">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="title-area mb-45">
                        <h2 class="sec-title">{{$chooseUsSection?->content?->title}}</h2>
                    </div>
                    {!! clean(processText($chooseUsSection?->content?->sub_title)) !!}
                </div>
            </div>

        </div>
    </div>
    @endif

    @if($sections->where('name', 'award_section')->first()?->is_active)
    <!-- Award Area -->
    <div class="award-area-1 space overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <ul class="award-wrap-area">
                        @foreach ($awards as $award)
                          <li class="single-award-list">
                            <span class="award-year">{{$award?->year}}</span>
                            <div class="award-details">
                                <h4><a href="{{$award?->url}}">{{$award?->title}}</a></h4>
                                <p>{{$award?->sub_title}}</p>
                            </div>
                            <span class="award-tag">{{$award?->tag}}</span>
                        </li>  
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($sections->where('name', 'team_section')->first()?->is_active)
    <!-- Team Area -->
    <div class="team-area-1 space-bottom overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="title-area text-center">
                        @php
                            $teamSection = $sections->where('name', 'team_section')->first();
                            $teamSectionTitle = __('Our Team Behind The Studio');
                            
                            if ($teamSection && $teamSection->global_content) {
                                $globalContent = $teamSection->global_content;
                                if (is_object($globalContent) && isset($globalContent->title)) {
                                    $teamSectionTitle = $globalContent->title;
                                } elseif (is_array($globalContent) && isset($globalContent['title'])) {
                                    $teamSectionTitle = $globalContent['title'];
                                }
                            }
                        @endphp
                        <h2 class="sec-title">{{ $teamSectionTitle }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @foreach ($teams as $team)
                    <div class="col-lg-3 col-md-6">
                        <div class="team-card">
                            <div class="team-card_img">
                                <img src="{{ asset($team?->image) }}" alt="{{ $team?->name }}">
                            </div>
                            <div class="team-card_content">
                                <h3 class="team-card_title"><a
                                        href="{{ route('single.team', $team?->slug) }}">{{ $team?->name }}</a></h3>
                                <span class="team-card_desig">{{ $team?->designation }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if($sections->where('name', 'contact_section')->first()?->is_active)
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
    @endif

    @if($sections->where('name', 'brand_section')->first()?->is_active)
    <!-- Brand Area -->
    <div class="client-area-1 overflow-hidden space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <ul class="client-list-wrap">
                        @foreach ($brands as $brand)
                        <li>
                            <a href="{{ $brand?->url }}">
                                <span class="link-effect">
                                    <span class="effect-1"><img src="{{ asset($brand?->image) }}"
                                            alt="{{ $brand?->name }}"></span>
                                    <span class="effect-1"><img src="{{ asset($brand?->image) }}"
                                            alt="{{ $brand?->name }}"></span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

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
