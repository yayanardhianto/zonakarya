@extends('frontend.layouts.master')
@section('title', 'Application Status - ' . $user->name . ' || ' . $setting->app_name)
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <div class="application-status-section py-5">
        <div class="container mt-lg-5">
            <div class="row">
                <div class="col-lg-8 mx-auto pt-5">
                    <!-- Header -->
                    <div class="status-header text-center mb-5">
                        <h2 class="mb-3">{{ __('Status Lamaran') }}</h2>
                        <p class="text-muted">{{ __('Lacak progres lamaran pekerjaan Anda') }}</p>
                    </div>

                    <!-- User Info -->
                    <div class="applicant-info-card card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Informasi Pelamar') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>{{ __('Nama') }}:</strong> {{ $user->name }}</p>
                                    <p><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>{{ __('Total Lamaran') }}:</strong> {{ $applications->count() }}</p>
                                    <p><strong>{{ __('Terakhir Melamar') }}:</strong> {{ $applications->first() ? $applications->first()->created_at->format('d M Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications List -->
                    <div class="applications-list">
                        @forelse($applications as $application)
                            <div class="application-card card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">{{ $application->jobVacancy->position }}</h5>
                                    <span class="badge badge-{{ $application->status_badge }} mb-3 me-3 position-relative">
                                        {{ $application->status_text }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Perusahaan') }}:</strong> {{ $application->jobVacancy->company_name }}</p>
                                            <p><strong>{{ __('Lokasi') }}:</strong> {{ $application->jobVacancy->location }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Tipe Pekerjaan') }}:</strong> {{ $application->jobVacancy->work_type }}</p>
                                            <p><strong>{{ __('Gaji') }}:</strong> {{ $application->jobVacancy->formatted_salary }}</p>
                                        </div>
                                    </div>

                                    <!-- Status Timeline -->
                                    <div class="status-timeline mt-4">
                                        <h6 class="mb-3">{{ __('Progres Lamaran') }}</h6>
                                        <div class="timeline">
                                            <div class="timeline-item {{ $application->status == 'pending' ? 'active' : ($application->status == 'sent' || $application->status == 'check' || $application->status == 'short_call' ? 'completed' : '') }}">
                                                <div class="timeline-marker">
                                                    <i class="fas fa-paper-plane"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h6>{{ __('Lamaran Dikirim') }}</h6>
                                                    <p class="text-muted">{{ $application->created_at->format('d M Y H:i') }}</p>
                                                </div>
                                            </div>

                                            @if($application->status == 'sent' || $application->status == 'check' || $application->status == 'short_call')
                                                <div class="timeline-item {{ $application->status == 'sent' ? 'active' : ($application->status == 'check' || $application->status == 'short_call' ? 'completed' : '') }}">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Invitation Tes Dikirim') }}</h6>
                                                        <p class="text-muted">{{ $application->test_sent_at ? $application->test_sent_at->format('d M Y H:i') : 'Menunggu' }}</p>
                                                        @if($application->testSession && $application->testSession->status === 'pending')
                                                            <a href="{{ route('test.take', ['session' => $application->testSession, 'token' => $application->testSession->access_token]) }}" 
                                                               class="btn btn-sm btn-primary">
                                                                <i class="fas fa-play"></i> {{ __('Kerjakan Tes') }}
                                                            </a>
                                                        @elseif($application->testSession && $application->testSession->status === 'completed')
                                                            <p class="text-success">
                                                                <i class="fas fa-check"></i> {{ __('Tes Selesai') }} - {{ $application->test_completed_at ? $application->test_completed_at->format('d M Y H:i') : 'N/A' }}
                                                            </p>
                                                        @else
                                                            <p class="text-info">
                                                                <i class="fas fa-clock"></i> {{ __('Menggunakan hasil tes screening sebelumnya') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'check' || $application->status == 'short_call')
                                                <div class="timeline-item {{ $application->status == 'check' ? 'active' : 'completed' }}">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Sedang Ditinjau') }}</h6>
                                                        <p class="text-muted">{{ __('HR sedang meninjau lamaran dan hasil tes Anda') }}</p>
                                                        @if($application->test_completed_at)
                                                            <p class="text-success">
                                                                <i class="fas fa-check"></i> {{ __('Tes Selesai') }} - {{ $application->test_completed_at->format('d M Y H:i') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'short_call')
                                                <div class="timeline-item active">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Invitation Short Call') }}</h6>
                                                        <p class="text-muted">{{ __('Anda telah diundang untuk wawancara via telepon') }}</p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($application->status == 'rejected')
                                                <div class="timeline-item rejected">
                                                    <div class="timeline-marker">
                                                        <i class="fas fa-times"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <h6>{{ __('Lamaran Ditolak') }}</h6>
                                                        <p class="text-muted">{{ $application->notes ?? 'Terima kasih atas minat Anda. Sayangnya, kami memutuskan untuk melanjutkan dengan kandidat lain.' }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Test Results -->
                                    @if($application->testSession && ($application->test_completed_at || $application->testSession->status === 'completed'))
                                        <div class="test-results mt-4">
                                            <h6 class="mb-3">{{ __('Hasil Tes') }}</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-primary">{{ $application->test_score ?? ($application->testSession->score ?? 'N/A') }}</h4>
                                                        <p class="text-muted">{{ __('Nilai') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-success">{{ $application->test_completed_at ? $application->test_completed_at->format('d M Y') : ($application->testSession->completed_at ? $application->testSession->completed_at->format('d M Y') : 'N/A') }}</h4>
                                                        <p class="text-muted">{{ __('Selesai') }}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="result-item text-center">
                                                        <h4 class="text-info">{{ $application->testSession->package->total_questions ?? 'N/A' }}</h4>
                                                        <p class="text-muted">{{ __('Soal') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="no-applications text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>{{ __('Tidak Ada Lamaran Ditemukan') }}</h5>
                                <p class="text-muted">{{ __('Anda belum mengirimkan lamaran pekerjaan apapun.') }}</p>
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('lihat Lowongan Lain') }}
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons text-center mt-4">
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> {{ __('Lihat Lowongan Lain') }}
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home"></i> {{ __('Kembali ke Home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }
    
    .timeline-item.active {
        opacity: 1;
    }
    
    .timeline-item.completed {
        opacity: 1;
    }
    
    .timeline-item.rejected {
        opacity: 1;
    }
    
    .timeline-marker {
        position: absolute;
        left: -2rem;
        top: 0;
        width: 2rem;
        height: 2rem;
        background: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.8rem;
    }
    
    .timeline-item.active .timeline-marker {
        background: #007bff;
        color: white;
    }
    
    .timeline-item.completed .timeline-marker {
        background: #28a745;
        color: white;
    }
    
    .timeline-item.rejected .timeline-marker {
        background: #dc3545;
        color: white;
    }
    
    .timeline-content {
        padding-left: 1rem;
    }
    
    .result-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .application-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    
    .applicant-info-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>
@endpush
