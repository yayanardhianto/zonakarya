@extends('admin.master_layout')
@section('title')
    <title>{{ __('Applicant Details') }} - {{ $applicant->name }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Applicant Details') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Applicants') => route('admin.applicants.index'),
            $applicant->name => '#',
        ]" />

        <div class="section-body">
            <div class="row">
                <!-- Applicant Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Applicant Information') }}</h4>
                        </div>
                        <div class="card-body text-center">
                            @if($applicant->photo_path)
                                <img src="{{ asset('uploads/store/' . $applicant->photo_path) }}" 
                                     alt="{{ $applicant->name }}" 
                                     class="rounded-circle mb-3" 
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @elseif($applicant->user && $applicant->user->avatar)
                                <img src="{{ $applicant->user->avatar }}" 
                                     alt="{{ $applicant->name }}" 
                                     class="rounded-circle mb-3" 
                                     width="120" height="120"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                     style="width: 120px; height: 120px; font-size: 3rem;">
                                    {{ substr($applicant->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <h4>{{ $applicant->name }}</h4>
                            <p class="text-muted">{{ $applicant->email }}</p>
                            
                            <div class="contact-info">
                                <p><i class="fas fa-phone"></i> {{ $applicant->phone }}</p>
                                @if($applicant->whatsapp && $applicant->whatsapp != $applicant->phone)
                                    <p><i class="fab fa-whatsapp"></i> {{ $applicant->whatsapp }}</p>
                                @endif
                            </div>
                            
                            <div class="status-info mt-3">
                                <span class="badge badge-{{ $applicant->status_badge }} badge-lg">
                                    {{ $applicant->status_text }}
                                </span>
                            </div>
                            
                            @if($applicant->provider)
                                <div class="social-info mt-2">
                                    <small class="text-muted">
                                        <i class="fab fa-{{ $applicant->provider }}"></i>
                                        {{ __('Registered via') }} {{ ucfirst($applicant->provider) }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Files -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4>{{ __('Files') }}</h4>
                        </div>
                        <div class="card-body">
                            @if($applicant->cv_path)
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="{{ route('admin.applicants.download-cv', $applicant) }}" 
                                           class="btn btn-success btn-block mb-2">
                                            <i class="fas fa-download me-1"></i> {{ __('Download CV') }}
                                        </a>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-block mb-2" 
                                                onclick="viewCv({{ $applicant->id }})">
                                            <i class="fas fa-eye me-1"></i> {{ __('View CV') }}
                                        </button>
                                    </div>
                                    @if($applicant->photo_path)
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-info btn-block mb-2" 
                                                    onclick="viewPhoto({{ $applicant->id }})">
                                                <i class="fas fa-image me-1"></i> {{ __('View Photo') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Application Details') }}</h4>
                        </div>
                        <div class="card-body">
                            @foreach($applicant->applications as $application)
                                <div class="application-item border-bottom pb-3 mb-3">
                                    <h5>{{ $application->jobVacancy->position }}</h5>
                                    <p class="text-muted">{{ $application->jobVacancy->company_name }}</p>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Location') }}:</strong> {{ $application->jobVacancy->location }}</p>
                                            <p><strong>{{ __('Work Type') }}:</strong> {{ $application->jobVacancy->work_type }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Applied Date') }}:</strong> {{ $application->created_at->format('d M Y H:i') }}</p>
                                            <p><strong>{{ __('Status') }}:</strong> 
                                                <span class="badge badge-{{ $application->status_badge }}">
                                                    {{ $application->status_text }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($application->testSession)
                                        <div class="test-info mt-2">
                                            <h6>{{ __('Test Information') }}</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>{{ __('Test Package') }}:</strong> {{ $application->testSession->package->name }}</p>
                                                    <p><strong>{{ __('Test Status') }}:</strong> 
                                                        <span class="badge badge-{{ $application->testSession->status == 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($application->testSession->status) }}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    @if($application->testSession->status == 'completed')
                                                        @if($application->testSession->score !== null)
                                                            <p><strong>{{ __('Score') }}:</strong>
                                                                <span class="badge badge-{{ $application->testSession->score >= 70 ? 'success' : 'danger' }}">
                                                                    {{ $application->testSession->score }}%
                                                                </span>
                                                            </p>
                                                        @elseif(isset($application->testSession->multiple_choice_score) && $application->testSession->multiple_choice_score !== null)
                                                            <p><strong>{{ __('Score (Multiple Choice Only)') }}:</strong>
                                                                <span class="badge badge-{{ $application->testSession->multiple_choice_is_passed ? 'success' : 'danger' }}">
                                                                    {{ $application->testSession->multiple_choice_score }}%
                                                                </span>
                                                                <br>
                                                                <small class="text-muted">({{ $application->testSession->multiple_choice_points ?? 0 }}/{{ $application->testSession->multiple_choice_max ?? 0 }} points)</small>
                                                                <br><small class="text-muted text-small"><i class="fas fa-info-circle text-small ms-1"></i> {{ __('MC Only - overall score requires manual review') }}</small>
                                                            </p>
                                                        @endif
                                                        <p><strong>{{ __('Completed At') }}:</strong> {{ $application->testSession->updated_at->format('d M Y H:i') }}</p>
                                                    @endif
                                                    @if($application->testSession->status == 'pending')
                                                        <p><strong>{{ __('Test Link') }}:</strong> 
                                                            <a href="{{ route('test.take', ['session' => $application->testSession, 'token' => $application->testSession->access_token]) }}" 
                                                               target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-external-link-alt"></i> {{ __('Take Test') }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <!-- <div class="card mt-4">
                        <div class="card-header">
                            <h4>{{ __('Actions') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>{{ __('Update Status') }}</h6>
                                    <form id="statusForm">
                                        @csrf
                                        <div class="form-group">
                                            <select name="status" class="form-control" id="statusSelect">
                                                <option value="pending" {{ $applicant->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                <option value="sent" {{ $applicant->status == 'sent' ? 'selected' : '' }}>{{ __('Test Sent') }}</option>
                                                <option value="check" {{ $applicant->status == 'check' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                                                <option value="short_call" {{ $applicant->status == 'short_call' ? 'selected' : '' }}>{{ __('Short Call') }}</option>
                                                <option value="rejected" {{ $applicant->status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <textarea name="notes" class="form-control" rows="3" 
                                                      placeholder="{{ __('Add notes (optional)') }}">{{ $applicant->notes }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> {{ __('Update Status') }}
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>{{ __('Quick Actions') }}</h6>
                                    <div class="d-grid gap-2">
                                        @if($applicant->status == 'check')
                                            <button class="btn btn-success" id="nextStepBtn">
                                                <i class="fas fa-arrow-right"></i> {{ __('Next Step') }}
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-danger" id="rejectBtn">
                                            <i class="fas fa-times"></i> {{ __('Reject') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Notes -->
                    @if($applicant->notes)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>{{ __('Notes') }}</h4>
                            </div>
                            <div class="card-body">
                                <p>{{ $applicant->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Next Step Modal -->
<div class="modal fade" id="nextStepModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Move to Next Step') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="nextStepForm">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('WhatsApp Template') }} <span class="text-danger">*</span></label>
                        <select name="template_id" class="form-control" required>
                            <option value="">{{ __('Select Template') }}</option>
                            @foreach(\App\Models\WhatsAppTemplate::where('type', 'short_call_invitation')->where('is_active', true)->get() as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="{{ __('Add notes for short call invitation') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-success" id="confirmNextStep">{{ __('Move to Next Step') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Reject Applicant') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('WhatsApp Template') }} <span class="text-danger">*</span></label>
                        <select name="template_id" class="form-control" required>
                            <option value="">{{ __('Select Template') }}</option>
                            @foreach(\App\Models\WhatsAppTemplate::where('type', 'rejection_message')->where('is_active', true)->get() as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Reason for Rejection') }}</label>
                        <textarea name="notes" class="form-control" rows="3" 
                                  placeholder="{{ __('Enter reason for rejection') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmReject">{{ __('Reject Applicant') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update status
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("admin.applicants.update-status", $applicant) }}', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
        });
    });

    // Next step
    document.getElementById('nextStepBtn').addEventListener('click', function() {
        $('#nextStepModal').modal('show');
    });

    document.getElementById('confirmNextStep').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('nextStepForm'));
        
        fetch('{{ route("admin.applicants.next-step", $applicant) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#nextStepModal').modal('hide');
                if (data.whatsapp_url) {
                    // Open WhatsApp URL in new tab
                    window.open(data.whatsapp_url, '_blank');
                }
                location.reload();
            } else {
                alert('Error moving to next step: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error moving to next step');
        });
    });

    // Reject
    document.getElementById('rejectBtn').addEventListener('click', function() {
        $('#rejectModal').modal('show');
    });

    document.getElementById('confirmReject').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('rejectForm'));
        
        fetch('{{ route("admin.applicants.reject", $applicant) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#rejectModal').modal('hide');
                if (data.whatsapp_url) {
                    // Open WhatsApp URL in new tab
                    window.open(data.whatsapp_url, '_blank');
                }
                location.reload();
            } else {
                alert('Error rejecting applicant: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting applicant');
        });
    });
});

// CV View Modal Functions
function viewCv(applicantId) {
    // Show CV in modal
    const modal = document.getElementById('cvModal');
    const cvFrame = document.getElementById('cvFrame');
    cvFrame.src = `/admin/applicants/${applicantId}/view-cv`;
    modal.style.display = 'block';
}

function closeCvModal() {
    document.getElementById('cvModal').style.display = 'none';
    document.getElementById('cvFrame').src = '';
}

// Photo View Modal Functions
function viewPhoto(applicantId) {
    // Show Photo in modal
    const modal = document.getElementById('photoModal');
    const photoImg = document.getElementById('photoImg');
    photoImg.src = `/admin/applicants/${applicantId}/view-photo`;
    modal.style.display = 'block';
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
    document.getElementById('photoImg').src = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const cvModal = document.getElementById('cvModal');
    const photoModal = document.getElementById('photoModal');
    
    if (event.target == cvModal) {
        closeCvModal();
    }
    
    if (event.target == photoModal) {
        closePhotoModal();
    }
}
</script>
@endpush

<!-- CV View Modal -->
<div id="cvModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 90%; height: 80%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('View CV') }}</h3>
            <span class="close" onclick="closeCvModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body" style="height: calc(100% - 60px);">
            <iframe id="cvFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>
</div>

<!-- Photo View Modal -->
<div id="photoModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8);">
    <div class="modal-content" style="background-color: #fefefe; margin: 2% auto; padding: 20px; border: 1px solid #888; width: 70%; height: 90%; border-radius: 8px; position: relative;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('View Photo') }}</h3>
            <span class="close" onclick="closePhotoModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body" style="height: calc(100% - 60px); display: flex; align-items: center; justify-content: center;">
            <img id="photoImg" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 8px;" alt="Applicant Photo">
        </div>
    </div>
</div>
