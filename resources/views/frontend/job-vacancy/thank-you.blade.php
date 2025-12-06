@extends('frontend.layouts.master')
@section('title', 'Thank You - Application Submitted || ' . $setting->app_name)
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <div class="thank-you-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 pt-5">
                    <div class="thank-you-card card text-center mt-lg-5">
                        <div class="card-body py-5 px-5">
                            <div class="success-icon mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>
                            
                            <h2 class="card-title mb-4">{{ __('Terima Kasih Atas Lamaran Anda!') }}</h2>
                            
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Lamaran Anda telah berhasil dikirim. Kami akan segera menghubungi Anda untuk langkah selanjutnya.') }}
                            </div>
                            
                            @php
                                $latestApplication = $applicant->applications()->latest()->first();
                                $testSession = $latestApplication ? $latestApplication->testSession : null;
                                $isSkipTest = !$testSession; // Jika tidak ada test session, berarti skip test
                            @endphp
                            
                            @if(!$isSkipTest)
                            <div class="whatsapp-info mb-4">
                                <h5 class="mb-3">{{ __('Langkah Selanjutnya') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="step-item mb-3">
                                            <i class="fas fa-mobile-alt text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6>{{ __('Notifikasi WhatsApp') }}</h6>
                                            <p class="text-muted">{{ __('Anda akan menerima pesan WhatsApp dengan detail tes screening.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="step-item mb-3">
                                            <i class="fas fa-clock text-warning mb-2" style="font-size: 2rem;"></i>
                                            <h6>{{ __('Validitas Link Tes') }}</h6>
                                            <p class="text-muted">{{ __('Link tes hanya berlaku untuk hari ini.') }}</p>
                                            @if($testSession && $testSession->status === 'pending')
                                                <a href="{{ route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]) }}" 
                                                   class="btn btn-sm btn-primary mt-2">
                                                    <i class="fas fa-play"></i> {{ __('Kerjakan Tes Sekarang') }}
                                                </a>
                                            @elseif($testSession && $testSession->status === 'completed')
                                                <p class="text-success mt-2">
                                                    <i class="fas fa-check"></i> {{ __('Tes Selesai') }}
                                                </p>
                                            @else
                                                <p class="text-info mt-2">
                                                    <i class="fas fa-clock"></i> {{ __('Undangan tes akan segera dikirim') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle"></i>
                                <strong>{{ __('Lamaran Anda telah diproses!') }}</strong>
                                <p class="mb-0">{{ __('Tim kami akan segera menghubungi Anda melalui WhatsApp untuk langkah selanjutnya.') }}</p>
                            </div> -->
                            @endif
                            
                            <div class="application-details mb-4">
                                <h5 class="mb-3">{{ __('Detail Lamaran') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>{{ __('Nama') }}:</strong> {{ $applicant->name }}</p>
                                        <p><strong>{{ __('Email') }}:</strong> {{ $applicant->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>{{ __('WhatsApp') }}:</strong> {{ $applicant->whatsapp }}</p>
                                        <p><strong>{{ __('Status') }}:</strong> 
                                            @if($applicant->status)
                                                <span class="badge badge-{{ $applicant->status_badge }} position-relative">{{ $applicant->status_text }}</span>
                                            @else
                                                <span class="badge badge-secondary position-relative">{{ __('Menunggu') }}</span>
                                            @endif
                                            @if(config('app.debug'))
                                                <small class="text-muted d-block">Debug: Status={{ $applicant->status ?? 'NULL' }}, Badge={{ $applicant->status_badge ?? 'NULL' }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- @auth
                                <div class="alert alert-success mt-3">
                                    <i class="fas fa-user-check"></i>
                                    <strong>{{ __('Anda sekarang sudah login!') }}</strong>
                                    <p class="mb-0">{{ __('Anda sekarang dapat melamar pekerjaan lain tanpa perlu mendaftar ulang.') }}</p>
                                </div>
                                @endauth -->
                                
                                @if($testSession)
                                <div class="test-info mt-3 bg-white">
                                    <h6 class="mb-3">{{ __('Informasi Tes') }}</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Paket Tes') }}:</strong> {{ $testSession->package->name ?? 'N/A' }}</p>
                                            <p><strong>{{ __('Status') }}:</strong> 
                                                <span class="badge badge-{{ $testSession->status === 'pending' ? 'warning' : ($testSession->status === 'completed' ? 'success' : 'info') }} position-relative">
                                                    {{ ucfirst($testSession->status == 'in_progress' ? 'Sedang Berlangsung' : ($testSession->status == 'pending' ? 'Menunggu' : ($testSession->status == 'completed' ? 'Selesai' : $testSession->status))) }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Soal') }}:</strong> {{ $testSession->package->total_questions ?? 'N/A' }}</p>
                                            <p><strong>{{ __('Durasi') }}:</strong> {{ $testSession->package->duration ?? 'N/A' }} {{ __('menit') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($testSession->status === 'pending')
                                    <div class="test-actions mt-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{ route('test.take', ['session' => $testSession, 'token' => $testSession->access_token]) }}" 
                                                   class="btn btn-primary btn-block">
                                                    <i class="fas fa-play"></i> {{ __('Mulai Tes') }}
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ route('test.qr-code', ['session' => $testSession]) }}" 
                                                   class="btn btn-outline-primary btn-block" target="_blank">
                                                    <i class="fas fa-qrcode"></i> {{ __('Kode QR') }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                {{ __('Link tes berakhir pada') }}: {{ $testSession->expires_at ? $testSession->expires_at->format('d M Y H:i') : 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                    @elseif($testSession->status === 'completed')
                                    <div class="test-results mt-3">
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            <strong>{{ __('Tes Berhasil Diselesaikan!') }}</strong>
                                            <p class="mb-0">{{ __('Tes Anda telah dikirim dan sedang ditinjau.') }}</p>
                                        </div>
                                        @if($testSession->score)
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{ $testSession->score }}</h4>
                                                    <p class="text-muted">{{ __('Nilai') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-success">{{ $testSession->completed_at ? $testSession->completed_at->format('d M Y') : 'N/A' }}</h4>
                                                    <p class="text-muted">{{ __('Selesai') }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-info">{{ $testSession->package->total_questions ?? 'N/A' }}</h4>
                                                    <p class="text-muted">{{ __('Soal') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('applicant.status') }}" class="btn btn-success">
                                    <i class="fas fa-chart-line"></i> {{ __('Lacak Status Lamaran') }}
                                </a>
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('Lihat Lowongan Lain') }}
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-0 mt-md-2">
                                    <i class="fas fa-home"></i> {{ __('Kembali ke Home') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .thank-you-card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
    }
    
    .success-icon {
        animation: bounce 1s ease-in-out;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }
    
    .step-item {
        padding: 1rem;
        border-radius: 10px;
        background: #f8f9fa;
        transition: transform 0.3s ease;
    }
    
    .step-item:hover {
        transform: translateY(-5px);
    }
    
    .application-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    
    .badge-primary {
        background-color: #007bff;
        color: white;
    }
    
    .badge-danger {
        background-color: #dc3545;
        color: white;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    
    .test-info {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid #007bff;
    }
    
    .test-actions .btn {
        margin-bottom: 0.5rem;
    }
    
    .test-results .alert {
        border-radius: 8px;
    }
    
    .test-results .text-center {
        padding: 1rem;
        background: #fff;
        border-radius: 8px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endpush
