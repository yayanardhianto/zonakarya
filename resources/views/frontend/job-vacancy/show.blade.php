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
                            <p class="text-muted mb-3">{{ __('Tertarik dengan posisi ini? Hubungi perusahaan langsung menggunakan informasi yang tersedia.') }}</p>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    {{ $setting->label_send_application ?? __('Kirim Lamaran') }}
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

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">{{ __('Lamar untuk') }} {{ $jobVacancy->position }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @auth
                        @if($applicant)
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Data lamaran sebelumnya telah diisi otomatis. Anda dapat mengubah informasi jika diperlukan.') }}
                            </div>
                        @else
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle"></i>
                                {{ __('Selamat datang kembali! Informasi profil Anda telah diisi otomatis.') }}
                            </div>
                        @endif
                    @endauth
                    
                    <form id="applyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Nama Lengkap') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ $user->name ?? ($applicant->name ?? '') }}" required>
                                        <!-- @auth
                                            @if($user->name || ($applicant && $applicant->name))
                                                <div class="input-group-append">
                                                    <span class="input-group-text text-success" title="{{ __('Pre-filled from your profile') }}">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        @endauth -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">{{ __('Nomor WhatsApp') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="whatsapp" name="whatsapp" 
                                               placeholder="+628123456789" 
                                               value="{{ $applicant->whatsapp ?? '' }}" required>
                                        <!-- @auth
                                            @if($applicant && $applicant->whatsapp)
                                                <div class="input-group-append">
                                                    <span class="input-group-text text-success" title="{{ __('Pre-filled from your previous application') }}">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                </div>
                                            @endif
                                        @endauth -->
                                    </div>
                                    <small class="form-text text-muted">{{ __('Kami akan menghubungi Anda via WhatsApp untuk langkah selanjutnya') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cv">{{ __('CV/Resume') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                                    <small class="form-text text-muted">{{ __('Format yang diterima: PDF, DOC, DOCX (Maks: 25MB)') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('Foto') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                                    <small class="form-text text-muted">{{ __('Format yang diterima: JPG, PNG (Maks: 1MB)') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="camera-section" id="cameraSection" style="display: none;">
                                <label>{{ __('Ambil Foto dengan Kamera') }}</label>
                                <div class="camera-container">
                                    <video id="camera" width="320" height="240" autoplay></video>
                                    <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                                </div>
                                <div class="camera-controls mt-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="captureBtn">{{ __('Ambil') }}</button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="retakeBtn" style="display: none;">{{ __('Ulang') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-outline-primary" id="useCameraBtn">
                                <i class="fas fa-camera"></i> {{ __('Gunakan Kamera') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                    <button type="button" class="btn btn-primary" id="submitApplication">{{ __('Kirim Lamaran') }}</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

    // Notification function
    function showNotification(message, type = 'warning') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.form-notification');
        existingNotifications.forEach(notif => notif.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} form-notification alert-dismissible fade show`;
        notification.setAttribute('role', 'alert');
        notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        
        const icon = type === 'warning' ? 'fa-exclamation-triangle' : (type === 'success' ? 'fa-check-circle' : 'fa-times-circle');
        notification.innerHTML = `
            <i class="fas ${icon} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 150);
        }, 5000);
    }

    // Show field error
    function showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        // Remove existing error
        const existingError = field.parentElement.querySelector('.field-error');
        if (existingError) existingError.remove();

        // Add error class
        field.classList.add('is-invalid');

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-danger small mt-1';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${message}`;
        field.parentElement.appendChild(errorDiv);
    }

    // Remove field error
    function removeFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        field.classList.remove('is-invalid');
        const existingError = field.parentElement.querySelector('.field-error');
        if (existingError) existingError.remove();
    }

    // Validate file size and format
    function validateFile(input, maxSizeMB, allowedTypes, typeName) {
        if (!input.files || input.files.length === 0) {
            return { valid: false, message: `${typeName} harus diisi` };
        }

        const file = input.files[0];
        const fileSizeMB = file.size / (1024 * 1024);
        
        // Get file extension more robustly (handle files with special characters)
        const fileName = file.name || '';
        const lastDotIndex = fileName.lastIndexOf('.');
        const fileExtension = lastDotIndex > 0 ? fileName.substring(lastDotIndex + 1).toLowerCase() : '';

        // Check if file is empty or too small (might be corrupt)
        if (file.size === 0) {
            return { 
                valid: false, 
                message: `${typeName} kosong atau corrupt. Silakan pilih file lain.` 
            };
        }

        // Check file size
        if (fileSizeMB > maxSizeMB) {
            return { 
                valid: false, 
                message: `${typeName} terlalu besar (${fileSizeMB.toFixed(2)} MB). Maksimal ${maxSizeMB} MB` 
            };
        }

        // Check file type by extension first (more reliable)
        let isValidType = false;
        const allowedExtensions = allowedTypes.filter(type => !type.includes('/'));
        const allowedMimeTypes = allowedTypes.filter(type => type.includes('/'));

        // Check by extension
        if (fileExtension && allowedExtensions.length > 0) {
            isValidType = allowedExtensions.some(ext => fileExtension === ext.toLowerCase());
        }

        // If extension check fails, check by MIME type
        if (!isValidType && file.type && allowedMimeTypes.length > 0) {
            isValidType = allowedMimeTypes.some(mimeType => {
                // Handle partial MIME type matches (e.g., "application/pdf" matches "pdf")
                if (file.type === mimeType) return true;
                // Also check if MIME type contains the type (e.g., "application/pdf" contains "pdf")
                const mimeBase = mimeType.split('/')[1];
                return file.type.includes(mimeBase);
            });
        }

        // If still not valid, check if file extension exists
        if (!isValidType && !fileExtension) {
            return { 
                valid: false, 
                message: `${typeName} tidak memiliki ekstensi file. Pastikan file memiliki ekstensi yang benar (${allowedExtensions.join(', ').toUpperCase()})` 
            };
        }

        if (!isValidType) {
            const formatList = allowedExtensions.length > 0 
                ? allowedExtensions.join(', ').toUpperCase() 
                : allowedTypes.join(', ').toUpperCase();
            return { 
                valid: false, 
                message: `Format ${typeName} tidak valid (${fileExtension || 'tidak diketahui'}). Format yang diterima: ${formatList}` 
            };
        }

        return { valid: true, message: '' };
    }

    // Camera functionality
    const useCameraBtn = document.getElementById('useCameraBtn');
    const cameraSection = document.getElementById('cameraSection');
    const camera = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const photoInput = document.getElementById('photo');
    const cvInput = document.getElementById('cv');
    const nameInput = document.getElementById('name');
    const whatsappInput = document.getElementById('whatsapp');

    useCameraBtn.addEventListener('click', function() {
        if (cameraSection.style.display === 'none') {
            startCamera();
        } else {
            stopCamera();
        }
    });

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(mediaStream) {
                stream = mediaStream;
                camera.srcObject = stream;
                cameraSection.style.display = 'block';
                useCameraBtn.textContent = '{{ __("Sembunyikan Kamera") }}';
            })
            .catch(function(err) {
                alert('{{ __("Akses kamera ditolak atau tidak tersedia") }}');
                console.error('Camera error:', err);
            });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraSection.style.display = 'none';
        useCameraBtn.textContent = '{{ __("Gunakan Kamera") }}';
    }

    captureBtn.addEventListener('click', function() {
        const context = canvas.getContext('2d');
        context.drawImage(camera, 0, 0, 320, 240);
        
        canvas.toBlob(function(blob) {
            const fileSizeMB = blob.size / (1024 * 1024);
            
            // Check file size (max 1MB)
            if (fileSizeMB > 1) {
                showFieldError('photo', `Foto terlalu besar (${fileSizeMB.toFixed(2)} MB). Maksimal 1 MB`);
                showNotification('Foto terlalu besar. Silakan gunakan foto dengan ukuran lebih kecil.', 'warning');
                return;
            }
            
            const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;
            
            // Photo from camera is always valid JPEG
            removeFieldError('photo');
            showNotification('Foto berhasil diambil dari kamera', 'success');
        }, 'image/jpeg', 0.8);
        
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
    });

    retakeBtn.addEventListener('click', function() {
        captureBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
        photoInput.value = '';
        removeFieldError('photo');
    });

    // Real-time validation for CV file
    cvInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        // First do basic validation
        const validation = validateFile(this, 25, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf', 'doc', 'docx'], 'CV/Resume');
        
        if (!validation.valid) {
            showFieldError('cv', validation.message);
            showNotification(validation.message, 'warning');
            return;
        }

        // If it's a PDF file, check the header for corruption
        if (file.name.toLowerCase().endsWith('.pdf') || file.type === 'application/pdf') {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const arrayBuffer = e.target.result;
                    const uint8Array = new Uint8Array(arrayBuffer);
                    const header = String.fromCharCode.apply(null, uint8Array.slice(0, 4));
                    
                    // PDF files should start with %PDF
                    if (!header.startsWith('%PDF')) {
                        showFieldError('cv', 'File PDF tidak valid atau corrupt. Pastikan file adalah PDF yang benar.');
                        showNotification('File PDF tidak valid atau corrupt. Silakan pilih file PDF yang benar.', 'warning');
                        cvInput.value = ''; // Clear the invalid file
                        return;
                    } else {
                        // PDF header is valid
                        removeFieldError('cv');
                    }
                } catch (error) {
                    console.error('Error reading PDF header:', error);
                    showFieldError('cv', 'Tidak dapat membaca file. Pastikan file tidak corrupt.');
                    showNotification('Tidak dapat membaca file. Pastikan file tidak corrupt.', 'warning');
                }
            };
            reader.onerror = function() {
                showFieldError('cv', 'Error membaca file. Pastikan file tidak corrupt.');
                showNotification('Error membaca file. Pastikan file tidak corrupt.', 'warning');
            };
            reader.readAsArrayBuffer(file.slice(0, 4)); // Only read first 4 bytes
        } else {
            // Not a PDF, just use normal validation
            removeFieldError('cv');
        }
    });

    // Real-time validation for Photo file
    photoInput.addEventListener('change', function() {
        const validation = validateFile(this, 1, ['image/jpeg', 'image/png', 'image/jpg', 'jpg', 'jpeg', 'png'], 'Foto');
        if (validation.valid) {
            removeFieldError('photo');
        } else {
            showFieldError('photo', validation.message);
            showNotification(validation.message, 'warning');
        }
    });

    // Real-time validation for Name field
    nameInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            showFieldError('name', 'Nama lengkap harus diisi');
        } else {
            removeFieldError('name');
        }
    });

    // Real-time validation for WhatsApp field
    whatsappInput.addEventListener('blur', function() {
        const cleanedValue = this.value.replace(/\s+/g, '');
        const whatsappRegex = /^(\+62|62|0)[0-9]{9,12}$/;
        
        if (!this.value.trim()) {
            showFieldError('whatsapp', 'Nomor WhatsApp harus diisi');
        } else {
            // Check if it's just a prefix without number
            if (/^(\+62|62|0)$/.test(cleanedValue) || cleanedValue.length < 10) {
                showFieldError('whatsapp', 'Nomor WhatsApp tidak lengkap. Contoh: 08123456789, 628123456789, atau +628123456789');
            } else if (!whatsappRegex.test(cleanedValue)) {
                showFieldError('whatsapp', 'Format nomor WhatsApp tidak valid. Gunakan format: 08123456789, 628123456789, atau +628123456789');
            } else {
                removeFieldError('whatsapp');
            }
        }
    });

    // Form validation before submission
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Validate name
        if (!nameInput.value.trim()) {
            showFieldError('name', 'Nama lengkap harus diisi');
            errors.push('Nama lengkap harus diisi');
            isValid = false;
        } else {
            removeFieldError('name');
        }

        // Validate WhatsApp
        const cleanedWhatsapp = whatsappInput.value.replace(/\s+/g, '');
        const whatsappRegex = /^(\+62|62|0)[0-9]{9,12}$/;
        if (!whatsappInput.value.trim()) {
            showFieldError('whatsapp', 'Nomor WhatsApp harus diisi');
            errors.push('Nomor WhatsApp harus diisi');
            isValid = false;
        } else if (/^(\+62|62|0)$/.test(cleanedWhatsapp) || cleanedWhatsapp.length < 10) {
            showFieldError('whatsapp', 'Nomor WhatsApp tidak lengkap');
            errors.push('Nomor WhatsApp tidak lengkap. Contoh: 08123456789, 628123456789, atau +628123456789');
            isValid = false;
        } else if (!whatsappRegex.test(cleanedWhatsapp)) {
            showFieldError('whatsapp', 'Format nomor WhatsApp tidak valid');
            errors.push('Format nomor WhatsApp tidak valid. Gunakan format: 08123456789, 628123456789, atau +628123456789');
            isValid = false;
        } else {
            removeFieldError('whatsapp');
        }

        // Validate CV
        const cvValidation = validateFile(cvInput, 25, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'pdf', 'doc', 'docx'], 'CV/Resume');
        if (!cvValidation.valid) {
            showFieldError('cv', cvValidation.message);
            errors.push(cvValidation.message);
            isValid = false;
        } else {
            removeFieldError('cv');
        }

        // Validate Photo
        const photoValidation = validateFile(photoInput, 1, ['image/jpeg', 'image/png', 'image/jpg', 'jpg', 'jpeg', 'png'], 'Foto');
        if (!photoValidation.valid) {
            showFieldError('photo', photoValidation.message);
            errors.push(photoValidation.message);
            isValid = false;
        } else {
            removeFieldError('photo');
        }

        return { isValid, errors };
    }

    // Form submission
    document.getElementById('submitApplication').addEventListener('click', function() {
        const submitBtn = this;
        
        // Validate form before submission
        const validation = validateForm();
        
        if (!validation.isValid) {
            // Show notification with all errors
            const errorMessage = validation.errors.length > 0 
                ? validation.errors[0] 
                : 'Mohon lengkapi semua field yang wajib diisi';
            showNotification(errorMessage, 'warning');
            
            // Scroll to first error field
            const firstErrorField = document.querySelector('.is-invalid');
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstErrorField.focus();
            }
            return;
        }

        const form = document.getElementById('applyForm');
        const formData = new FormData(form);
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';
        
        fetch('/jobs/{{ $jobVacancy->unique_code }}/apply', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentApplicantId = data.applicant_id;
                showNotification('{{ __("Lamaran berhasil dikirim!") }}', 'success');
                $('#applyModal').modal('hide');
                
                // Check if user is already logged in
                @auth
                    // User is logged in, redirect to thank you page directly
                    setTimeout(() => {
                        window.location.href = '{{ route("jobs.thank-you", ":applicant_id") }}'.replace(':applicant_id', currentApplicantId);
                    }, 1000);
                @else
                    // User is not logged in, show social login modal
                    setTimeout(() => {
                        $('#socialLoginModal').modal('show');
                    }, 1000);
                @endauth
            } else {
                const errorMsg = data.message || data.errors || '{{ __("Error mengirim lamaran") }}';
                showNotification(errorMsg, 'danger');
                
                // Show field errors if any
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const fieldId = field.replace('_', '');
                        const fieldElement = document.getElementById(fieldId);
                        if (fieldElement) {
                            showFieldError(fieldId, data.errors[field][0]);
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('{{ __("Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.") }}', 'danger');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '{{ __("Kirim Lamaran") }}';
        });
    });

    // Social login
    document.getElementById('googleLoginBtn').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ route("auth.google") }}?applicant_id=' + currentApplicantId;
    });

    document.getElementById('linkedinLoginBtn').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '{{ route("auth.linkedin") }}?applicant_id=' + currentApplicantId;
    });

    // Cleanup camera and reset form on modal close
    $('#applyModal').on('hidden.bs.modal', function() {
        stopCamera();
        
        // Reset form
        document.getElementById('applyForm').reset();
        
        // Remove all field errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.field-error').forEach(error => {
            error.remove();
        });
        
        // Remove notifications
        document.querySelectorAll('.form-notification').forEach(notif => {
            notif.remove();
        });
    });

    // Reset form when modal opens
    $('#applyModal').on('show.bs.modal', function() {
        // Remove any existing errors
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.field-error').forEach(error => {
            error.remove();
        });
    });

    // Highlight pre-filled fields
    @auth
        @if($applicant || $user)
            document.addEventListener('DOMContentLoaded', function() {
                const preFilledFields = ['name', 'whatsapp'];
                preFilledFields.forEach(function(fieldId) {
                    const field = document.getElementById(fieldId);
                    if (field && field.value) {
                        field.classList.add('pre-filled');
                        // Add a subtle animation
                        field.style.transition = 'all 0.3s ease';
                        setTimeout(() => {
                            field.style.backgroundColor = '#f8f9fa';
                            field.style.borderColor = '#28a745';
                        }, 100);
                    }
                });
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
