@extends('frontend.layouts.master')
@section('title', __('Available Tests'))
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection
@section('contents')


<x-breadcrumb :image="$setting?->portfolio_page_breadcrumb_image" :title="__('Tests')" />

<!-- Main Area -->
<div class="portfolio-area-1 space-y overflow-hidden">
    <div class="container">
        <div class="row">
            @forelse($packages as $package)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-1 rounded-0 p-2">
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3 mt-4">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px;">
                                    <i class="ki-solid ki-document fs-4"></i>
                                </div>
                            </div>
                            
                            <h4 class="card-title text-center mb-3">{{ $package->name }}</h4>
                            
                            <div class="mb-3">
                                <span class="badge bg-black text-white mb-5 mt-2 ms-2">{{ $package->category->name }}</span>
                                @if($package->description)
                                    <p class="card-text text-muted small">{{ Str::limit($package->description, 100) }}</p>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h6 class="text-muted mb-1">{{ __('Duration') }}</h6>
                                            <small class="fw-bold">{{ $package->getDurationFormattedWithQuestionTime() }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h6 class="text-muted mb-1">{{ __('Questions') }}</h6>
                                            <small class="fw-bold">{{ $package->total_questions }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <h6 class="text-muted mb-1">{{ __('Passing Score') }}</h6>
                                        <small class="fw-bold">{{ $package->passing_score }}%</small>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('test.start', $package) }}" 
                                       class="btn btn-primary btn-lg">
                                        <i class="fas fa-play me-2"></i>{{ __('Start Test') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">{{ __('No Tests Available') }}</h3>
                        <p class="text-muted">{{ __('There are currently no test packages available. Please check back later.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($packages->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>{{ __('Test Instructions') }}
                        </h5>
                        <ul class="mb-0">
                            <li>{{ __('Each test has a specific time limit. Make sure you have enough time to complete it.') }}</li>
                            <li>{{ __('You cannot pause or resume the test once started.') }}</li>
                            <li>{{ __('Make sure you have a stable internet connection.') }}</li>
                            <li>{{ __('Read each question carefully before answering.') }}</li>
                            <li>{{ __('For multiple choice questions, select the best answer.') }}</li>
                            <li>{{ __('For essay questions, provide detailed and thoughtful answers.') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, #0056b3, #004085);
        transform: translateY(-2px);
    }
    .space-y {
        padding-top: 100px !important;
        padding-bottom: 100px !important;
    }
</style>
@endpush
