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
                                <img src="{{ asset('uploads/custom-images/wsus-img-2025-10-09-05-44-11-3128.png') }}" 
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
                                    <small class="form-text text-muted">{{ __('Format yang diterima: PDF, DOC, DOCX (Maks: 2MB)') }}</small>
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

    // Camera functionality
    const useCameraBtn = document.getElementById('useCameraBtn');
    const cameraSection = document.getElementById('cameraSection');
    const camera = document.getElementById('camera');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const photoInput = document.getElementById('photo');

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
            const file = new File([blob], 'photo.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;
        }, 'image/jpeg', 0.8);
        
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
    });

    retakeBtn.addEventListener('click', function() {
        captureBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
        photoInput.value = '';
    });

    // Form submission
    document.getElementById('submitApplication').addEventListener('click', function() {
        const form = document.getElementById('applyForm');
        const formData = new FormData(form);
        
        // Show loading
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Mengirim...") }}';
        
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
                $('#applyModal').modal('hide');
                
                // Check if user is already logged in
                @auth
                    // User is logged in, redirect to thank you page directly
                    window.location.href = '{{ route("jobs.thank-you", ":applicant_id") }}'.replace(':applicant_id', currentApplicantId);
                @else
                    // User is not logged in, show social login modal
                    $('#socialLoginModal').modal('show');
                @endauth
            } else {
                alert('{{ __("Error mengirim lamaran") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error mengirim lamaran") }}');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '{{ __("Kirim Lamaran") }}';
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

    // Cleanup camera on modal close
    $('#applyModal').on('hidden.bs.modal', function() {
        stopCamera();
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
</style>
@endpush
