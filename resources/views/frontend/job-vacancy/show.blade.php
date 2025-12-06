@extends('frontend.layouts.master')
@section('title', $jobVacancy->position . ' - ' . $jobVacancy->company_name . ' || ' . $setting->app_name)
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <x-breadcrumb :title="$jobVacancy->position" />

    <div class="job-detail-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Job Header -->
                    <!-- <div class="job-header mb-4">
                        <div class="d-flex align-items-start">
                            @if($jobVacancy->company_logo)
                                <img src="{{ asset('storage/' . $jobVacancy->company_logo) }}" 
                                     alt="{{ $jobVacancy->company_name }}" 
                                     class="company-logo mr-4" 
                                     width="80" height="80">
                            @else
                                <div class="company-logo-placeholder mr-4 d-flex align-items-center justify-content-center bg-light rounded" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-building fa-2x text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h1 class="job-title mb-2">{{ $jobVacancy->position }}</h1>
                                <h3 class="company-name text-primary mb-2">{{ $jobVacancy->company_name }}</h3>
                                <div class="job-meta">
                                    <span class="badge badge-primary me-2">{{ $jobVacancy->work_type }}</span>
                                    <span class="badge badge-info me-2">{{ $jobVacancy->education }}</span>
                                    @if($jobVacancy->experience_years > 0)
                                        <span class="badge badge-warning">{{ $jobVacancy->experience_years }} {{ __('years exp') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Job Details -->
                    <div class="job-details card bg-light p-4 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ $setting->label_location ?? __('Lokasi') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        {{ $jobVacancy->location }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ $setting->label_work_type ?? __('Jenis Pekerjaan') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-briefcase text-secondary me-2"></i>
                                        {{ $jobVacancy->work_type }}
                                    </p>
                                </div>
                            </div>
                            @if($jobVacancy->show_salary)
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ $setting->label_salary_range ?? __('Range Gaji') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-dollar-sign text-success me-2"></i>
                                        {{ $jobVacancy->formatted_salary }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if($jobVacancy->show_age)
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ $setting->label_age_range ?? __('Range Usia') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-info me-2"></i>
                                        {{ $jobVacancy->formatted_age }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ $setting->label_gender ?? __('Jenis Kelamin') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-user text-warning me-2"></i>
                                        {{ $jobVacancy->gender }}
                                    </p>
                                </div>
                            </div>
                            @if($jobVacancy->application_deadline)
                                <div class="col-md-6">
                                    <div class="detail-item mb-3">
                                        <h6 class="text-muted mb-1">{{ $setting->label_deadline ?? __('Deadline Pendaftaran') }}</h6>
                                        <p class="mb-0">
                                            <i class="fas fa-clock text-danger me-2"></i>
                                            {{ $jobVacancy->application_deadline->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="job-description mb-4">
                        <h4 class="mb-3">{{ $setting->label_job_description ?? __('Deskripsi Pekerjaan') }}</h4>
                        <div class="content">
                            {!! nl2br(e($jobVacancy->description)) !!}
                        </div>
                    </div>

                    <!-- Responsibilities -->
                    @if($jobVacancy->responsibilities)
                        <div class="job-responsibilities mb-4">
                            <h4 class="mb-3">{{ $setting->label_responsibilities ?? __('Tanggung Jawab') }}</h4>
                            <div class="content">
                                {!! nl2br(e($jobVacancy->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Specific Requirements -->
                    @if($jobVacancy->specific_requirements && count($jobVacancy->specific_requirements) > 0)
                        <div class="job-requirements mb-4">
                            <h4 class="mb-3">{{ $setting->label_requirements ?? __('Persyaratan Khusus') }}</h4>
                            <ul class="list-unstyled">
                                @foreach($jobVacancy->specific_requirements as $requirement)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        {{ $requirement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Benefits -->
                    @if($jobVacancy->benefits)
                        <div class="job-benefits mb-4">
                            <h4 class="mb-3">{{ $setting->label_benefits ?? __('Keuntungan') }}</h4>
                            <div class="content">
                                {!! nl2br(e($jobVacancy->benefits)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <!-- Company Info Card -->
                    <div class="company-info-card card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $setting->label_company_info ?? __('Informasi Perusahaan') }}</h5>
                        </div>
                        <div class="card-body text-center">
                                <img src="{{ asset($setting?->logo) }}" 
                                     alt="{{ $jobVacancy->company_name }}" 
                                     class="company-logo mb-3 w-50" 
                                     height="60"/>
                            <h4 class="company-name mb-3">Zona Karya Nusantara</h4>
                            
                            <div class="contact-info">
                                <div class="contact-item mb-2">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <a href="mailto:{{ $jobVacancy->contact_email }}">hr@zona-karya.id</a>
                                </div>
                                
                                <!-- @if($jobVacancy->contact_phone)
                                    <div class="contact-item mb-2">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <a href="tel:{{ $jobVacancy->contact_phone }}">{{ $jobVacancy->contact_phone }}</a>
                                    </div>
                                @endif -->
                            </div>
                        </div>
                    </div>

                    <!-- Application Card -->
                    <div class="application-card card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $setting->label_apply_position ?? __('Lamar Posisi Ini') }}</h5>
                        </div>
                        <div class="card-body">
                            @if($hasExistingApplication && $existingApplication)
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>{{ __('Perhatian!') }}</strong>
                                    {{ __('Anda sudah pernah melamar untuk lowongan ini sebelumnya pada') }} 
                                    <strong>{{ $existingApplication->created_at->format('d M Y H:i') }}</strong>.
                                    {{ __('Status lamaran Anda saat ini:') }} 
                                    <strong>{{ ucfirst(str_replace('_', ' ', $existingApplication->status)) }}</strong>.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <p class="text-muted mb-3">{{ __('Tertarik dengan posisi ini? Hubungi perusahaan langsung menggunakan informasi yang tersedia.') }}</p>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" id="openApplyBtn"
                                        @if($hasExistingApplication) disabled @endif>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    @if($hasExistingApplication)
                                        {{ __('Sudah Melamar') }}
                                    @else
                                        {{ $setting->label_send_application ?? __('Kirim Lamaran') }}
                                    @endif
                                </button>
                                
                                <a href="mailto:{{ $jobVacancy->contact_email }}?subject=Application for {{ $jobVacancy->position }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ $setting->label_send_email ?? __('Kirim Email') }}
                                </a>
                                
                                @if($jobVacancy->contact_phone)
                                    <a href="tel:{{ $jobVacancy->contact_phone }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-phone me-2"></i>
                                        {{ $setting->label_call_company ?? __('Telepon Perusahaan') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Job Stats -->
                    <div class="job-stats card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $setting->label_job_stats ?? __('Statistik Lowongan') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="stat-item d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ $setting->label_views ?? __('Dilihat') }}:</span>
                                <span class="font-weight-bold">{{ $jobVacancy->views }}</span>
                            </div>
                            <div class="stat-item d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ $setting->label_posted ?? __('Diposting') }}:</span>
                                <span class="font-weight-bold">{{ $jobVacancy->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="stat-item d-flex justify-content-between">
                                <span class="text-muted">{{ $setting->label_last_updated ?? __('Terakhir Diupdate') }}:</span>
                                <span class="font-weight-bold">{{ $jobVacancy->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Jobs -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        {{ __('Kembali ke Semua Lowongan') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Modal (Step 1: Prelim) -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">{{ __('Lamar untuk') }} {{ $jobVacancy->position }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    @if($hasExistingApplication && $existingApplication)
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ __('Anda sudah pernah melamar untuk lowongan ini!') }}</strong><br>
                            {{ __('Lamaran sebelumnya dibuat pada') }}: <strong>{{ $existingApplication->created_at->format('d M Y H:i') }}</strong><br>
                            {{ __('Status lamaran') }}: <strong>{{ ucfirst(str_replace('_', ' ', $existingApplication->status)) }}</strong><br>
                            <small>{{ __('Anda tidak dapat melamar lagi untuk lowongan yang sama.') }}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @auth
                        @if($applicant && !$hasExistingApplication)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Data lamaran sebelumnya telah diisi otomatis. Anda dapat mengubah informasi jika diperlukan.') }}
                            </div>
                        @elseif(!$hasExistingApplication)
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle"></i>
                                {{ __('Selamat datang kembali! Informasi profil Anda telah diisi otomatis.') }}
                            </div>
                        @endif
                    @endauth
                    
                    <form id="applyFormPrelim" @if($hasExistingApplication) style="pointer-events: none; opacity: 0.6;" @endif>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Nama Lengkap') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ $user->name ?? ($applicant->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">{{ __('Nomor WhatsApp') }} <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="whatsapp" name="whatsapp" 
                                           placeholder="+628123456789" 
                                           value="{{ $applicant->whatsapp ?? '' }}" required>
                                    <small class="form-text text-muted">{{ __('Kami akan menghubungi Anda via WhatsApp untuk langkah selanjutnya') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <small class="text-muted">{{ __('Setelah menyimpan data awal, Anda akan diarahkan untuk mengikuti tes screening. Setelah tes selesai, Anda akan diminta mengunggah CV dan foto untuk menyelesaikan lamaran.') }}</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="button" class="btn btn-primary" id="submitPrelim" 
                            @if($hasExistingApplication) disabled @endif>
                        {{ __('Mulai Test Screening') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Finalize Modal (Step 2: after test - upload CV & Photo) -->
    <div class="modal fade" id="finalizeModal" tabindex="-1" aria-labelledby="finalizeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalizeModalLabel">{{ __('Selesaikan Lamaran Anda') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <form id="finalizeForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="finalize_application_id" name="application_id" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cv_finalize">{{ __('CV/Resume') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="cv_finalize" name="cv" accept=".pdf,.doc,.docx" required>
                                    <small class="form-text text-muted">{{ __('Format yang diterima: PDF, DOC, DOCX (Maks: 25MB)') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo_finalize">{{ __('Foto') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="photo_finalize" name="photo" accept="image/*" required>
                                    <small class="form-text text-muted">{{ __('Format yang diterima: JPG, PNG (Maks: 1MB)') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="camera-section" id="cameraSectionFinalize" style="display: none;">
                                <label>{{ __('Ambil Foto dengan Kamera') }}</label>
                                <div class="camera-container">
                                    <video id="cameraFinalize" width="320" height="240" autoplay></video>
                                    <canvas id="canvasFinalize" width="320" height="240" style="display: none;"></canvas>
                                </div>
                                <div class="camera-controls mt-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="captureBtnFinalize">{{ __('Ambil') }}</button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="retakeBtnFinalize" style="display: none;">{{ __('Ulang') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-outline-primary" id="useCameraBtnFinalize">
                                <i class="fas fa-camera"></i> {{ __('Gunakan Kamera') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="button" class="btn btn-primary" id="submitFinalize">
                        {{ __('Selesaikan Lamaran') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Login Modal -->
    <div class="modal fade" id="socialLoginModal" tabindex="-1" aria-labelledby="socialLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="socialLoginModalLabel">{{ __('Lengkapi Pendaftaran') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-4">{{ __('Silakan masuk dengan akun Anda untuk melengkapi pendaftaran') }}</p>
                    
                    <div class="social-login-buttons">
                        <a href="#" class="btn btn-outline-danger w-100 mb-3" id="googleLoginBtn">
                            <i class="fab fa-google"></i> {{ __('Lanjutkan dengan Google') }}
                        </a>
                        <a href="#" class="btn btn-outline-primary w-100" id="linkedinLoginBtn">
                            <i class="fab fa-linkedin"></i> {{ __('Lanjutkan dengan LinkedIn') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">{{ __('Lamaran Terkirim') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i>s</button>
                </div>
                <div class="modal-body text-center">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>{{ __('Terima kasih telah melamar!') }}</h4>
                    <p>{{ __('Kami telah menerima lamaran Anda, dan akan segera menghubungi Anda untuk langkah selanjutnya.') }}</p>
                    <p class="text-muted">{{ __('Anda akan menerima pesan WhatsApp dengan detail tes screening.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .job-title {
        color: #2c3e50;
        font-weight: 700;
    }
    
    .company-name {
        font-weight: 600;
    }
    
    .company-logo {
        object-fit: contain;
        border-radius: 12px;
        width: 100%;
        height: 36px !important;
        margin-top: 10px;
    }
    
    .company-logo-placeholder {
        border-radius: 12px;
    }
    
    .detail-item h6 {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .content {
        line-height: 1.6;
        color: #555;
    }
    
    .contact-item a {
        color: #2c3e50;
        text-decoration: none;
    }
    
    .contact-item a:hover {
        color: #3498db;
        text-decoration: underline;
    }
    
    .stat-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    .job-meta .badge {
        font-size: 0.8rem;
    }
    
    .camera-container {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        background: #f8f9fa;
    }

    .camera-container video {
        border-radius: 4px;
    }

    .social-login-buttons .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
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
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentApplicantId = null;
    let stream = null;

    // Handle "Mulai Test Screening" button click
    const openApplyBtn = document.getElementById('openApplyBtn');
    if (openApplyBtn) {
        openApplyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const isLoggedIn = @json(auth()->check());
            if (!isLoggedIn) {
                const socialLoginModal = new bootstrap.Modal(document.getElementById('socialLoginModal'));
                socialLoginModal.show();
            } else {
                // If logged in, go directly to profile (with skip test flow)
                const btn = this;
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Proses...") }}';

                const payload = { _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') };

                // Use applyDirectProfile endpoint which will check if screening is required
                fetch('/jobs/{{ $jobVacancy->unique_code }}/apply-direct-profile', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify(payload)
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP Error ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success && data.skip_test) {
                        // Skip test flow - go directly to profile page
                        showNotification('{{ __("Mengarahkan ke profil...") }}', 'success');
                        sessionStorage.setItem('pending_job_vacancy_id', data.job_vacancy_id);
                        setTimeout(() => { window.location.href = '/jobs/{{ $jobVacancy->unique_code }}/profile'; }, 600);
                    } else if (data.success) {
                        // Normal test flow - go to test
                        showNotification('{{ __("Mengarahkan ke tes...") }}', 'success');
                        sessionStorage.setItem('pending_application_id', data.application_id);
                        sessionStorage.setItem('pending_applicant_id', data.applicant_id);
                        if (data.start_test_url) {
                            setTimeout(() => { window.location.href = data.start_test_url; }, 600);
                        }
                    } else {
                        showNotification(data.message || 'Error saat memproses lamaran', 'danger');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(err => { 
                    console.error(err);
                    // If endpoint doesn't exist, fallback to applyPrelim
                    fetch('/jobs/{{ $jobVacancy->unique_code }}/apply-prelim', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            sessionStorage.setItem('pending_application_id', data.application_id);
                            sessionStorage.setItem('pending_applicant_id', data.applicant_id);
                            showNotification('{{ __("Mengarahkan ke tes...") }}', 'success');
                            if (data.start_test_url) {
                                setTimeout(() => { window.location.href = data.start_test_url; }, 600);
                            }
                        } else {
                            showNotification(data.message || 'Error', 'danger');
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                    })
                    .catch(err2 => {
                        showNotification('{{ __("Terjadi kesalahan. Silakan coba lagi.") }}', 'danger');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                });
            }
        });
    }

    // Notification helpers
    function showNotification(message, type = 'warning') {
        const existing = document.querySelectorAll('.form-notification');
        existing.forEach(e => e.remove());
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} form-notification alert-dismissible fade show`;
        notification.setAttribute('role', 'alert');
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        const icon = type === 'warning' ? 'fa-exclamation-triangle' : (type === 'success' ? 'fa-check-circle' : 'fa-times-circle');
        notification.innerHTML = `<i class="fas ${icon} me-2"></i><span>${message}</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        document.body.appendChild(notification);
        setTimeout(() => { notification.classList.remove('show'); setTimeout(() => notification.remove(), 150); }, 5000);
    }

    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        const existing = field.parentElement.querySelector('.field-error');
        if (existing) existing.remove();
        field.classList.add('is-invalid');
        const div = document.createElement('div');
        div.className = 'field-error text-danger small mt-1';
        div.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
        field.parentElement.appendChild(div);
    }

    function removeFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        field.classList.remove('is-invalid');
        const existing = field.parentElement.querySelector('.field-error');
        if (existing) existing.remove();
    }

    function validateFile(input, maxSizeMB, allowedTypes, typeName) {
        if (!input || !input.files || input.files.length === 0) {
            return { valid: false, message: `${typeName} harus diisi` };
        }
        const file = input.files[0];
        const fileSizeMB = file.size / (1024 * 1024);
        const fileName = file.name || '';
        const lastDot = fileName.lastIndexOf('.');
        const ext = lastDot > 0 ? fileName.substring(lastDot + 1).toLowerCase() : '';
        if (file.size === 0) return { valid: false, message: `${typeName} kosong atau corrupt.` };
        if (fileSizeMB > maxSizeMB) return { valid: false, message: `${typeName} terlalu besar (${fileSizeMB.toFixed(2)} MB). Maks ${maxSizeMB} MB` };
        let isValid = false;
        const allowedExt = allowedTypes.filter(t => !t.includes('/'));
        const allowedMime = allowedTypes.filter(t => t.includes('/'));
        if (ext && allowedExt.length) isValid = allowedExt.includes(ext.toLowerCase());
        if (!isValid && file.type && allowedMime.length) {
            isValid = allowedMime.some(m => file.type === m || file.type.includes(m.split('/')[1]));
        }
        if (!isValid && !ext) return { valid: false, message: `${typeName} tidak memiliki ekstensi.` };
        if (!isValid) return { valid: false, message: `Format ${typeName} tidak valid (${ext || 'tidak diketahui'})` };
        return { valid: true, message: '' };
    }

    // Finalize modal camera & inputs (use finalize IDs)
    const useCameraBtnFinalize = document.getElementById('useCameraBtnFinalize');
    const cameraSectionFinalize = document.getElementById('cameraSectionFinalize');
    const cameraFinalize = document.getElementById('cameraFinalize');
    const canvasFinalize = document.getElementById('canvasFinalize');
    const captureBtnFinalize = document.getElementById('captureBtnFinalize');
    const retakeBtnFinalize = document.getElementById('retakeBtnFinalize');
    const photoInput = document.getElementById('photo_finalize');
    const cvInput = document.getElementById('cv_finalize');
    const nameInput = document.getElementById('name');
    const whatsappInput = document.getElementById('whatsapp');

    function startCameraFinalize() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return alert('{{ __('Akses kamera tidak tersedia') }}');
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(mediaStream => {
                stream = mediaStream;
                cameraFinalize.srcObject = stream;
                cameraSectionFinalize.style.display = 'block';
                useCameraBtnFinalize.textContent = '{{ __('Sembunyikan Kamera') }}';
            })
            .catch(err => { console.error('Camera error:', err); alert('{{ __('Akses kamera ditolak atau tidak tersedia') }}'); });
    }

    function stopCameraFinalize() {
        if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
        cameraSectionFinalize.style.display = 'none';
        useCameraBtnFinalize.textContent = '{{ __('Gunakan Kamera') }}';
    }

    if (useCameraBtnFinalize) {
        useCameraBtnFinalize.addEventListener('click', function() {
            if (cameraSectionFinalize.style.display === 'none') startCameraFinalize(); else stopCameraFinalize();
        });
    }

    if (captureBtnFinalize) {
        captureBtnFinalize.addEventListener('click', function() {
            const ctx = canvasFinalize.getContext('2d');
            ctx.drawImage(cameraFinalize, 0, 0, 320, 240);
            canvasFinalize.toBlob(function(blob) {
                const sizeMB = blob.size / (1024*1024);
                if (sizeMB > 1) { showFieldError('photo_finalize', `Foto terlalu besar (${sizeMB.toFixed(2)} MB)`); showNotification('Foto terlalu besar','warning'); return; }
                const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer(); dt.items.add(file); photoInput.files = dt.files;
                removeFieldError('photo_finalize'); showNotification('Foto berhasil diambil dari kamera', 'success');
            }, 'image/jpeg', 0.8);
            captureBtnFinalize.style.display = 'none'; retakeBtnFinalize.style.display = 'inline-block';
        });

        retakeBtnFinalize.addEventListener('click', function() {
            captureBtnFinalize.style.display = 'inline-block'; retakeBtnFinalize.style.display = 'none'; photoInput.value = ''; removeFieldError('photo_finalize');
        });
    }

    // CV/Photo change handlers for finalize
    if (cvInput) cvInput.addEventListener('change', function() {
        const v = validateFile(this, 25, ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','pdf','doc','docx'], 'CV/Resume');
        if (!v.valid) { showFieldError('cv_finalize', v.message); showNotification(v.message,'warning'); this.value = ''; } else removeFieldError('cv_finalize');
    });
    if (photoInput) photoInput.addEventListener('change', function() {
        const v = validateFile(this, 1, ['image/jpeg','image/png','image/jpg','jpg','jpeg','png'], 'Foto');
        if (!v.valid) { showFieldError('photo_finalize', v.message); showNotification(v.message,'warning'); this.value = ''; } else removeFieldError('photo_finalize');
    });

    // Simple prelim validation
    function validatePrelim() {
        let ok = true; const errs = [];
        if (!nameInput.value.trim()) { showFieldError('name','Nama lengkap harus diisi'); errs.push('Nama lengkap harus diisi'); ok=false; }
        else removeFieldError('name');
        
        // Validate WhatsApp number - should be at least 10 digits
        const whatsappValue = whatsappInput.value.trim();
        const cleaned = whatsappValue.replace(/\D/g, ''); // Remove all non-digits
        
        let isValidWhatsapp = false;
        if (cleaned.length >= 10 && cleaned.length <= 15) {
            // Accept if: starts with 62 (country code), or 0 (local), or + prefix
            isValidWhatsapp = /^(62|0|\+62)/.test(cleaned.replace(/\+/g, ''));
        }
        
        if (!whatsappValue || !isValidWhatsapp) {
            showFieldError('whatsapp','Nomor WhatsApp tidak valid (min. 10 digit)');
            errs.push('Nomor WhatsApp tidak valid');
            ok = false;
        } else {
            removeFieldError('whatsapp');
        }
        
        return { isValid: ok, errors: errs };
    }

    // Prelim submit
    const submitPrelimBtn = document.getElementById('submitPrelim');
    if (submitPrelimBtn) {
        submitPrelimBtn.addEventListener('click', function() {
            const btn = this;
            @if($hasExistingApplication && $existingApplication)
                showNotification('{{ __("Anda sudah pernah melamar untuk lowongan ini sebelumnya pada") }} {{ $existingApplication->created_at->format("d M Y H:i") }}. {{ __("Status lamaran Anda saat ini:") }} {{ ucfirst(str_replace("_", " ", $existingApplication->status)) }}.', 'warning');
                return;
            @endif

            const v = validatePrelim();
            if (!v.isValid) { showNotification(v.errors[0] || 'Mohon lengkapi data', 'warning'); const fe = document.querySelector('.is-invalid'); if (fe) fe.scrollIntoView({behavior:'smooth', block:'center'}); return; }

            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Proses...") }}';

            const payload = { name: nameInput.value.trim(), whatsapp: whatsappInput.value.trim(), _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') };

            fetch('/jobs/{{ $jobVacancy->unique_code }}/apply-prelim', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify(payload)
            })
            .then(res => {
                // Check HTTP status code
                if (!res.ok && res.status !== 409) {
                    throw new Error(`HTTP Error ${res.status}`);
                }
                return res.json().then(data => ({ status: res.status, data: data }));
            })
            .then(({ status, data }) => {
                // Handle 409 Conflict (duplicate application)
                if (status === 409) {
                    showNotification(data.message || 'Anda sudah melamar di posisi ini sebelumnya.', 'danger');
                    return;
                }

                if (data.success) {
                    // store pending ids so finalize step can pick them up
                    sessionStorage.setItem('pending_application_id', data.application_id);
                    sessionStorage.setItem('pending_applicant_id', data.applicant_id);

                    showNotification('{{ __("Data awal berhasil disimpan. Mengarahkan ke tes...") }}', 'success');
                    // redirect to existing test route provided by backend
                    if (data.start_test_url) {
                        setTimeout(() => { window.location.href = data.start_test_url; }, 600);
                    } else {
                        // no test package available — show finalize modal directly
                        setTimeout(() => {
                            const finalizeModal = new bootstrap.Modal(document.getElementById('finalizeModal'));
                            document.getElementById('finalize_application_id').value = data.application_id;
                            finalizeModal.show();
                        }, 600);
                    }
                } else {
                    showNotification(data.message || 'Error saat menyimpan data awal', 'danger');
                }
            })
            .catch(err => { console.error(err); showNotification('{{ __("Terjadi kesalahan. Silakan coba lagi.") }}', 'danger'); })
            .finally(() => { btn.disabled = false; btn.innerHTML = '{{ __("Mulai Test Screening") }}'; });
        });
    }

    // Check if page opened after test completion: look for query param after_test=1 and application_id
    function getQueryParam(name) { const params = new URLSearchParams(window.location.search); return params.get(name); }
    const afterTest = getQueryParam('after_test');
    const returnedAppId = getQueryParam('application_id') || sessionStorage.getItem('pending_application_id');
    if (afterTest === '1' && returnedAppId) {
        // Redirect to Applicant Profile page which replaces the previous finalize modal
        setTimeout(() => {
            window.location.href = '/applications/' + returnedAppId + '/profile';
        }, 600);
    }

    // Finalize submit
    const submitFinalizeBtn = document.getElementById('submitFinalize');
    if (submitFinalizeBtn) {
        submitFinalizeBtn.addEventListener('click', function() {
            const btn = this;
            const applicationId = document.getElementById('finalize_application_id').value || sessionStorage.getItem('pending_application_id');
            if (!applicationId) { showNotification('Aplikasi tidak ditemukan. Mohon ulangi langkah sebelumnya.', 'warning'); return; }

            // validate files
            const cvEl = document.getElementById('cv_finalize');
            const photoEl = document.getElementById('photo_finalize');
            const cvV = validateFile(cvEl, 25, ['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','pdf','doc','docx'], 'CV/Resume');
            const photoV = validateFile(photoEl, 1, ['image/jpeg','image/png','image/jpg','jpg','jpeg','png'], 'Foto');
            if (!cvV.valid) { showFieldError('cv_finalize', cvV.message); showNotification(cvV.message,'warning'); return; }
            if (!photoV.valid) { showFieldError('photo_finalize', photoV.message); showNotification(photoV.message,'warning'); return; }

            const form = new FormData();
            form.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            form.append('cv', cvEl.files[0]);
            form.append('photo', photoEl.files[0]);

            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';

            fetch('/applications/' + applicationId + '/finalize', {
                method: 'POST',
                body: form,
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showNotification('{{ __("Lamaran berhasil diselesaikan!") }}', 'success');
                    // clear pending ids
                    sessionStorage.removeItem('pending_application_id');
                    sessionStorage.removeItem('pending_applicant_id');
                    // hide finalize modal and show success
                    const finalizeModalElem = document.getElementById('finalizeModal');
                    const modal = bootstrap.Modal.getInstance(finalizeModalElem);
                    if (modal) modal.hide();
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    // Optionally redirect to thank you with applicant
                    if (data.applicant_id) {
                        setTimeout(() => { window.location.href = '{{ route("jobs.thank-you", ":applicant_id") }}'.replace(':applicant_id', data.applicant_id); }, 1200);
                    }
                } else {
                    showNotification(data.message || 'Error saat menyelesaikan lamaran', 'danger');
                }
            })
            .catch(err => { console.error(err); showNotification('{{ __("Terjadi kesalahan. Silakan coba lagi.") }}', 'danger'); })
            .finally(() => { btn.disabled = false; btn.innerHTML = '{{ __("Selesaikan Lamaran") }}'; });
        });
    }

    // Social login buttons - store job vacancy info before redirecting
    document.getElementById('googleLoginBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const jobVacancyId = {{ $jobVacancy->id }};
        sessionStorage.setItem('pendingJobVacancyId', jobVacancyId);
        sessionStorage.setItem('showApplyModalAfterLogin', 'true');
        // Pass current page as intended URL so user comes back here after login
        const intendedUrl = window.location.href;
        window.location.href = '{{ route("auth.google") }}?intended=' + encodeURIComponent(intendedUrl);
    });
    document.getElementById('linkedinLoginBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const jobVacancyId = {{ $jobVacancy->id }};
        sessionStorage.setItem('pendingJobVacancyId', jobVacancyId);
        sessionStorage.setItem('showApplyModalAfterLogin', 'true');
        // Pass current page as intended URL so user comes back here after login
        const intendedUrl = window.location.href;
        window.location.href = '{{ route("auth.linkedin") }}?intended=' + encodeURIComponent(intendedUrl);
    });

    if (sessionStorage.getItem('showApplyModalAfterLogin') === 'true') {
        sessionStorage.removeItem('showApplyModalAfterLogin');
        const isLoggedIn = @json(auth()->check());
        if (isLoggedIn) {
            // After login, trigger apply flow
            setTimeout(() => {
                const applyBtn = document.getElementById('openApplyBtn');
                if (applyBtn) {
                    applyBtn.click(); // Trigger the apply button which will handle the routing
                }
            }, 500);
        }
    }

    // Cleanup and reset handlers for modals
    $('#applyModal').on('hidden.bs.modal', function() {
        // nothing special to cleanup for prelim
        document.getElementById('applyFormPrelim').reset();
        document.querySelectorAll('.is-invalid').forEach(f => f.classList.remove('is-invalid'));
        document.querySelectorAll('.field-error').forEach(e => e.remove());
        document.querySelectorAll('.form-notification').forEach(n => n.remove());
    });

    $('#finalizeModal').on('hidden.bs.modal', function() {
        stopCameraFinalize();
        document.getElementById('finalizeForm').reset();
        document.querySelectorAll('.is-invalid').forEach(f => f.classList.remove('is-invalid'));
        document.querySelectorAll('.field-error').forEach(e => e.remove());
        document.querySelectorAll('.form-notification').forEach(n => n.remove());
    });

    @auth
        @if($applicant || $user)
            const preFilledFields = ['name', 'whatsapp'];
            preFilledFields.forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field && field.value) {
                    field.classList.add('pre-filled');
                    field.style.transition = 'all 0.3s ease';
                    setTimeout(() => { field.style.backgroundColor = '#f8f9fa'; field.style.borderColor = '#28a745'; }, 100);
                }
            });
        @endif
    @endauth
});
</script>

<style>
.pre-filled {
    background-color: #f8f9fa !important;
    border-color: #28a745 !important;
}

.pre-filled:focus {
    background-color: #fff !important;
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

    .alert-success {
        border-left: 4px solid #28a745;
    }

    /* Form notification styles */
    .form-notification {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Field error styles */
    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6 .4.4.4-.4m0 4.8h-.8'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        padding-right: calc(1.5em + 0.75rem);
    }

    .field-error {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Success state for valid fields */
    .is-valid {
        border-color: #28a745 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        padding-right: calc(1.5em + 0.75rem);
    }

    /* File input feedback */
    .form-control:focus.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-control:focus.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
</style>
@endpush
