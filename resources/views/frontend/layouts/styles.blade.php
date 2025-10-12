    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/imageRevealHover.css') }}">
    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/cookie-consent.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/keenicons/outline/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/keenicons/solid/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/keenicons/duotone/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}?v={{ $setting?->version }}">
    @if (session()->has('text_direction') && session()->get('text_direction') == 'rtl')
    <link rel="stylesheet" href="{{ asset('frontend/css/rtl.css') }}?v={{ $setting?->version }}">
    @endif
<style>
:root {--theme-color: {{ $setting?->primary_color ?? '#E3FF04' }};--title-color: {{ $setting?->secondary_color ?? '#0A0C00' }};--body-color: {{ $setting?->secondary_color ?? '#0A0C00' }};}
@if ($setting?->tawk_status == 'active')
.scroll-top {bottom: 90px;}
@endif
</style>
@stack('css')
