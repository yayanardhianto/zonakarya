@extends('frontend.layouts.master')

@section('meta_title', $team?->name . ' || ' . $setting->app_name)
@section('meta_description', $team?->name)

@push('custom_meta')
    <meta property="og:title" content="{{ $team?->name }}" />
    <meta property="og:description" content="{{ $team?->name }}" />
    <meta property="og:image" content="{{ asset($team?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endpush

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- breadcrumb-area -->
    <x-breadcrumb-two :title="$team?->name" :links="[['url' => route('home'), 'text' => __('Beranda')],['url' => route('team'), 'text' => __('Tim')]]" />

    <!-- Main Area -->
    <div class="team-details-page-area space">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-5 col-lg-6">
                    <div class="team-inner-thumb mb-lg-0 mb-40">
                        <img class="w-100" src="{{ asset($team?->image) }}" alt="{{ $team?->name }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="team-details-about-info mb-0">
                        <h2 class="sec-title mb-3">{{ $team?->name }}</h2>
                        <h4 class="team-desig">{{ $team?->designation }}</h4>
                        <div class="sec-text mt-30">
                            {!! clean(processText($team?->sort_description)) !!}
                        </div>
                        <div class="about-contact-wrap mt-35">
                            <h6 class="about-contact-title"><a href="mailto:{{ $team?->email }}">{{ $team?->email }}</a>
                            </h6>
                            @if ($team?->phone)
                                <h6 class="about-contact-title"><a href="tel:{{ $team?->phone }}">{{ $team?->phone }}</a>
                                </h6>
                            @endif
                            <div class="social-btn mt-4">
                                @if ($team?->facebook)
                                    <a href="{{ $team?->facebook }}">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                @endif
                                @if ($team?->instagram)
                                    <a href="{{ $team?->instagram }}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if ($team?->twitter)
                                    <a href="{{ $team?->twitter }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                                @if ($team?->dribbble)
                                    <a href="{{ $team?->dribbble }}">
                                        <i class="fab fa-dribbble"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @if ($setting?->contact_team_member == 'active')
        <!-- Contact Area -->
        <div class="contact-area-2 text-center space-bottom">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-8">
                        <div class="contact-form-wrap">
                            <div class="title-area mb-30">
                                <h3 class="sec-title">{{ __('Hubungi Saya') }}</h3>
                            </div>
                            <form id="team-form" action="{{route('contact.team.member',$team?->slug)}}" class="contact-form ajax-contact">
                                <div class="row">
                                    @auth('web')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control style-border" name="name"
                                                    value="{{userAuth()?->name}}" placeholder="{{ __('Nama Lengkap') }}*" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control style-border" name="email"
                                                value="{{userAuth()?->email}}" placeholder="{{ __('Alamat Email') }}*" required>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control style-border" name="name" placeholder="{{ __('Nama Lengkap') }}*" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control style-border" name="email" placeholder="{{ __('Alamat Email') }}*" required>
                                            </div>
                                        </div>
                                    @endauth
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea name="message" placeholder="{{ __('Tulis pesan Anda') }}*" id="contactForm"
                                                class="form-control style-border style2" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                @if ($setting?->recaptcha_status == 'active')
                                    <div class="form-group mb-0 col-12 d-flex justify-content-center">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting?->recaptcha_site_key }}"></div>
                                    </div>
                                @endif
                                <div class="form-btn col-12">
                                    <button type="submit" class="btn mt-20">
                                        <span class="link-effect text-uppercase">
                                            <span class="effect-1">{{ __('Kirim Pesan') }}</span>
                                            <span class="effect-1">{{ __('Kirim Pesan') }}</span>
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


    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
