@extends('frontend.layouts.master')

@section('meta_title', $project?->seo_title . ' || ' . $setting->app_name)
@section('meta_description', $project?->seo_description)

@push('custom_meta')
    <meta property="og:title" content="{{ $project?->seo_title }}" />
    <meta property="og:description" content="{{ $project?->seo_description }}" />
    <meta property="og:image" content="{{ asset($project?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endpush

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- breadcrumb-area -->
    <x-breadcrumb-two :title="$project?->title" :links="[['url' => route('home'), 'text' => __('Home')],['url' => route('portfolios'), 'text' => __('Portfolio')]]" />

    <!-- Main Area -->
    <div class="project-details-page-area space">
        <div class="container">
            <div class="row global-carousel default" data-arrows="true" data-xl-arrows="true" data-ml-arrows="true"
                data-lg-arrows="true" data-md-arrows="true">
                @foreach ($project?->images as $image)
                <div class="col-xl-12">
                    <div class="project-inner-thumb mb-80 wow img-custom-anim-top">
                        <img class="w-100" src="{{asset($image?->large_image)}}" alt="{{$project?->title}}">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row justify-content-between flex-row-reverse">
                <div class="col-xl-3 col-lg-4">
                    <div class="project-details-info mb-lg-0 mb-40">
                        <ul class="list-wrap">
                            <li><span>{{__('Category')}}:</span>{{$project?->project_category}}</li>
                            <li><span>{{__('Software')}}:</span>{!! $tagString !!}</li>
                            <li><span>{{__('Service')}}:</span>{{$project?->service?->title}}</li>
                            <li><span>{{__('Client')}}:</span>{{$project?->project_author}}</li>
                            <li><span>{{__('Date')}}:</span>{{ formattedDate($project?->created_at) }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="title-area details-text mb-35">
                        <h2>{{$project?->title}}</h2>
                        {!! clean(replaceImageSources($project?->description)) !!}
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="inner__page-nav space-top mt-n1 mb-n1">
                        <a href="{{ $prevPost ? route('single.portfolio', $prevPost?->slug) : 'javascript:;' }}" class="nav-btn {{$prevPost ? '': 'disabled'}}">
                            <i class="fa fa-arrow-left"></i> <span><span class="link-effect">
                                    <span class="effect-1">{{ __('Previous Post') }}</span>
                                    <span class="effect-1">{{ __('Previous Post') }}</span>
                                </span></span>
                        </a>
                        <a href="{{ $nextPost ? route('single.portfolio', $nextPost?->slug) : 'javascript:;' }}" class="nav-btn {{$nextPost ? '': 'disabled'}}"><span><span class="link-effect">
                                    <span class="effect-1">{{ __('Next Post') }}</span>
                                    <span class="effect-1">{{ __('Next Post') }}</span>
                                </span></span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
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
