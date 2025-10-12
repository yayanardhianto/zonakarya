    
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- style sheets and font icons  -->
    <link rel="stylesheet" href="{{ asset('frontend/css02/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css02/icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css02/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css02/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css02/landing.css') }}">
<style>
:root {--theme-color: {{ $setting?->primary_color ?? '#E3FF04' }};--title-color: {{ $setting?->secondary_color ?? '#0A0C00' }};--body-color: {{ $setting?->secondary_color ?? '#0A0C00' }};}
@if ($setting?->tawk_status == 'active')
.scroll-top {bottom: 90px;}
@endif
</style>
@stack('css')
