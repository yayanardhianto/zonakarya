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
                                    <span class="badge badge-primary mr-2">{{ $jobVacancy->work_type }}</span>
                                    <span class="badge badge-info mr-2">{{ $jobVacancy->education }}</span>
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
                                    <h6 class="text-muted mb-1">{{ __('Location') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                        {{ $jobVacancy->location }}
                                    </p>
                                </div>
                            </div>
                            @if($jobVacancy->show_salary)
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ __('Salary Range') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-dollar-sign text-success mr-2"></i>
                                        {{ $jobVacancy->formatted_salary }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            @if($jobVacancy->show_age)
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ __('Age Range') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar text-info mr-2"></i>
                                        {{ $jobVacancy->formatted_age }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">{{ __('Gender') }}</h6>
                                    <p class="mb-0">
                                        <i class="fas fa-user text-warning mr-2"></i>
                                        {{ $jobVacancy->gender }}
                                    </p>
                                </div>
                            </div>
                            @if($jobVacancy->application_deadline)
                                <div class="col-md-6">
                                    <div class="detail-item mb-3">
                                        <h6 class="text-muted mb-1">{{ __('Application Deadline') }}</h6>
                                        <p class="mb-0">
                                            <i class="fas fa-clock text-danger mr-2"></i>
                                            {{ $jobVacancy->application_deadline->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="job-description mb-4">
                        <h4 class="mb-3">{{ __('Job Description') }}</h4>
                        <div class="content">
                            {!! nl2br(e($jobVacancy->description)) !!}
                        </div>
                    </div>

                    <!-- Responsibilities -->
                    @if($jobVacancy->responsibilities)
                        <div class="job-responsibilities mb-4">
                            <h4 class="mb-3">{{ __('Responsibilities') }}</h4>
                            <div class="content">
                                {!! nl2br(e($jobVacancy->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Specific Requirements -->
                    @if($jobVacancy->specific_requirements && count($jobVacancy->specific_requirements) > 0)
                        <div class="job-requirements mb-4">
                            <h4 class="mb-3">{{ __('Specific Requirements') }}</h4>
                            <ul class="list-unstyled">
                                @foreach($jobVacancy->specific_requirements as $requirement)
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success mr-2"></i>
                                        {{ $requirement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Benefits -->
                    @if($jobVacancy->benefits)
                        <div class="job-benefits mb-4">
                            <h4 class="mb-3">{{ __('Benefits') }}</h4>
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
                            <h5 class="mb-0">{{ __('Company Information') }}</h5>
                        </div>
                        <div class="card-body text-center">
                                <img src="{{ asset('uploads/custom-images/wsus-img-2025-10-09-05-44-11-3128.png') }}" 
                                     alt="{{ $jobVacancy->company_name }}" 
                                     class="company-logo mb-3 w-50" 
                                     height="60"/>
                            <h4 class="company-name mb-3">Zona Karya Nusantara</h4>
                            
                            <div class="contact-info">
                                <div class="contact-item mb-2">
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    <a href="mailto:{{ $jobVacancy->contact_email }}">hr@zona-karya.id</a>
                                </div>
                                
                                <!-- @if($jobVacancy->contact_phone)
                                    <div class="contact-item mb-2">
                                        <i class="fas fa-phone text-success mr-2"></i>
                                        <a href="tel:{{ $jobVacancy->contact_phone }}">{{ $jobVacancy->contact_phone }}</a>
                                    </div>
                                @endif -->
                            </div>
                        </div>
                    </div>

                    <!-- Application Card -->
                    <div class="application-card card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Apply for this Position') }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">{{ __('Interested in this position? Contact the company directly using the information provided.') }}</p>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    {{ __('Send Application') }}
                                </button>
                                
                                <a href="mailto:{{ $jobVacancy->contact_email }}?subject=Application for {{ $jobVacancy->position }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-envelope mr-2"></i>
                                    {{ __('Email Company') }}
                                </a>
                                
                                @if($jobVacancy->contact_phone)
                                    <a href="tel:{{ $jobVacancy->contact_phone }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ __('Call Company') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Job Stats -->
                    <div class="job-stats card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Job Statistics') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="stat-item d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Views') }}:</span>
                                <span class="font-weight-bold">{{ $jobVacancy->views }}</span>
                            </div>
                            <div class="stat-item d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Posted') }}:</span>
                                <span class="font-weight-bold">{{ $jobVacancy->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="stat-item d-flex justify-content-between">
                                <span class="text-muted">{{ __('Last Updated') }}:</span>
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
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('Back to All Jobs') }}
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
                    <h5 class="modal-title" id="applyModalLabel">{{ __('Apply for') }} {{ $jobVacancy->position }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="applyForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">{{ __('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="whatsapp" name="whatsapp" placeholder="+628123456789" required>
                                    <small class="form-text text-muted">{{ __('We will contact you via WhatsApp for the next steps') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cv">{{ __('CV/Resume') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                                    <small class="form-text text-muted">{{ __('Accepted formats: PDF, DOC, DOCX (Max: 2MB)') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('Photo') }} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                                    <small class="form-text text-muted">{{ __('Accepted formats: JPG, PNG (Max: 1MB)') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="camera-section" id="cameraSection" style="display: none;">
                                <label>{{ __('Take Photo with Camera') }}</label>
                                <div class="camera-container">
                                    <video id="camera" width="320" height="240" autoplay></video>
                                    <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                                </div>
                                <div class="camera-controls mt-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="captureBtn">{{ __('Capture') }}</button>
                                    <button type="button" class="btn btn-sm btn-secondary" id="retakeBtn" style="display: none;">{{ __('Retake') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-outline-primary" id="useCameraBtn">
                                <i class="fas fa-camera"></i> {{ __('Use Camera Instead') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="submitApplication">{{ __('Submit Application') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Login Modal -->
    <div class="modal fade" id="socialLoginModal" tabindex="-1" aria-labelledby="socialLoginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="socialLoginModalLabel">{{ __('Complete Registration') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-4">{{ __('Please sign in with your social account to complete the registration') }}</p>
                    
                    <div class="social-login-buttons">
                        <a href="#" class="btn btn-outline-danger w-100 mb-3" id="googleLoginBtn">
                            <i class="fab fa-google"></i> {{ __('Continue with Google') }}
                        </a>
                        <a href="#" class="btn btn-outline-primary w-100" id="linkedinLoginBtn">
                            <i class="fab fa-linkedin"></i> {{ __('Continue with LinkedIn') }}
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
                    <h5 class="modal-title" id="successModalLabel">{{ __('Application Submitted') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>{{ __('Thank you for applying!') }}</h4>
                    <p>{{ __('We have received your application and will contact you soon for the next steps.') }}</p>
                    <p class="text-muted">{{ __('You will receive a WhatsApp message with test screening details.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Close') }}</button>
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
                useCameraBtn.textContent = '{{ __("Hide Camera") }}';
            })
            .catch(function(err) {
                alert('{{ __("Camera access denied or not available") }}');
                console.error('Camera error:', err);
            });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraSection.style.display = 'none';
        useCameraBtn.textContent = '{{ __("Use Camera Instead") }}';
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
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __("Submitting...") }}';
        
        fetch('/jobs/{{ $jobVacancy->id }}/apply', {
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
                alert('{{ __("Error submitting application") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error submitting application") }}');
        })
        .finally(() => {
            this.disabled = false;
            this.innerHTML = '{{ __("Submit Application") }}';
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
});
</script>
@endpush
