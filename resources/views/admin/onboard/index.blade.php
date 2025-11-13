@extends('admin.master_layout')
@section('title')
    <title>{{ __('Onboard Management') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Onboard Management') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Onboard') => '#',
        ]" />

        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Onboard') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalOnboard }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('This Month') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $thisMonthOnboard }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.onboard.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Job Vacancy') }}</label>
                                    <select name="job_vacancy_id" class="form-control">
                                        <option value="">{{ __('All Jobs') }}</option>
                                        @foreach($jobVacancies as $job)
                                            <option value="{{ $job->id }}" {{ request('job_vacancy_id') == $job->id ? 'selected' : '' }}>
                                                {{ $job->position }} - {{ $job->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Search') }}</label>
                                    <input type="text" name="search" class="form-control" 
                                           value="{{ request('search') }}" 
                                           placeholder="{{ __('Search by name, email, or phone') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <div class="form-group mb-0 mt-4">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-search"></i> {{ __('Filter') }}
                                        </button>
                                    </div>
                                    <div class="form-group mb-0 mt-4">
                                        <label>&nbsp;</label>
                                        <a href="{{ route('admin.onboard.index') }}" class="btn btn-dark btn-block">
                                            <i class="fas fa-times"></i> {{ __('Clear') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Onboard Applicants Table -->
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Onboard Applicants') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Position Applied') }}</th>
                                    <th>{{ __('Onboard Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($application->user && $application->user->avatar)
                                                    <img src="{{ $application->user->avatar }}" 
                                                         alt="{{ $application->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="36" height="36" style="object-fit: cover;">
                                                @elseif($application->applicant && $application->applicant->photo_path)
                                                    <img src="{{ asset('uploads/store/' . $application->applicant->photo_path) }}" 
                                                         alt="{{ $application->applicant->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="36" height="36" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                                        {{ substr($application->user->name ?? $application->applicant->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $application->user->name ?? $application->applicant->name }}</strong>
                                                    @if($application->applicant->provider)
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fab fa-{{ $application->applicant->provider }}"></i>
                                                            {{ ucfirst($application->applicant->provider) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $application->user->email ?? $application->applicant->email }}</td>
                                        <td>
                                            @if($application->applicant->whatsapp && $application->applicant->whatsapp != $application->applicant->phone)
                                                <small class="text-muted">{{ $application->applicant->whatsapp }}</small>
                                            @endif
                                        </td>
                                        <td class="py-2">
                                            <span class="badge badge-success">{{ $application->jobVacancy->position }}</span>
                                            <br>
                                            <small class="text-muted">{{ $application->jobVacancy->location }}</small>
                                        </td>
                                        <td>
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i>
                                                <span class="text-small text-black">{{ $application->updated_at->format('d M Y H:i') }}</span>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" 
                                                        id="dropdownMenuButton{{ $application->id }}" 
                                                        data-toggle="dropdown" 
                                                        data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                    <i class="fas fa-cog"></i> {{ __('Actions') }}
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $application->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.applicants.show', $application->applicant) }}">
                                                            <i class="fas fa-eye me-2"></i>{{ __('View Details') }}
                                                        </a>
                                                    </li>
                                                    @php
                                                        $talent = \App\Models\Talent::where('user_id', $application->user_id)->first();
                                                    @endphp
                                                    @if($talent)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.talents.show', $talent) }}">
                                                                <i class="fas fa-star me-2"></i>{{ __('View Talent') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($application->applicant->cv_path)
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="viewCv({{ $application->applicant->id }})">
                                                                <i class="fas fa-file-pdf me-2"></i>{{ __('View CV') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($application->applicant->photo_path)
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="viewPhoto({{ $application->applicant->id }})">
                                                                <i class="fas fa-image me-2"></i>{{ __('View Photo') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-info" href="#" onclick="contactApplicant({{ $application->applicant->id }})">
                                                            <i class="fab fa-whatsapp me-2"></i>{{ __('Contact via WhatsApp') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">{{ __('No onboard applicants found') }}</h5>
                                                <p class="text-muted">{{ __('Applicants who have completed all stages will appear here.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

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
<div id="photoModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('View Photo') }}</h3>
            <span class="close" onclick="closePhotoModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body text-center">
            <img id="photoFrame" src="" style="max-width: 100%; max-height: 400px; border-radius: 8px;">
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.badge {
    font-size: 0.75rem;
}

.card-statistic-1 .card-icon {
    width: 60px;
    height: 60px;
    margin: auto;
    border-radius: 10px;
    line-height: 60px;
    text-align: center;
    font-size: 24px;
}

.card-statistic-1 .card-icon.bg-success {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.card-statistic-1 .card-icon.bg-info {
    background: linear-gradient(45deg, #17a2b8, #6f42c1);
}

.card-statistic-1 .card-wrap {
    padding-left: 15px;
}

.card-statistic-1 .card-header {
    padding-bottom: 0;
}

.card-statistic-1 .card-body {
    font-size: 24px;
    font-weight: 600;
    color: #6c757d;
}

/* Dropdown Menu Fix */
.dropdown-menu {
    z-index: 1050 !important;
    min-width: 200px;
    max-height: 300px;
    overflow-y: auto;
}

.dropdown-divider {
    border-top-color: #eceaea;
}

/* Ensure dropdown appears above table */
.table-responsive {
    overflow: visible !important;
}

.card-body {
    overflow: visible !important;
}
</style>
@endpush

@push('js')
<script>
/**
 * Normalize phone number to international format (62xxxxxxxxxx)
 * Converts: 081234567890 -> 6281234567890
 * Converts: +6281234567890 -> 6281234567890
 * Converts: 6281234567890 -> 6281234567890
 */
function normalizePhoneNumber(phone) {
    if (!phone) return phone;
    
    // Remove all non-numeric characters
    let cleanPhone = phone.replace(/[^0-9]/g, '');
    
    // If empty, return as is
    if (!cleanPhone) return phone;
    
    // If starts with 0, replace with 62
    if (cleanPhone.startsWith('0')) {
        cleanPhone = '62' + cleanPhone.substring(1);
    }
    // If starts with 62, keep it
    else if (cleanPhone.startsWith('62')) {
        // Already in correct format
    }
    // If doesn't start with 62, assume it's local format and add 62
    else {
        cleanPhone = '62' + cleanPhone;
    }
    
    return cleanPhone;
}

function viewCv(applicantId) {
    const modal = document.getElementById('cvModal');
    const cvFrame = document.getElementById('cvFrame');
    cvFrame.src = `/admin/applicants/${applicantId}/view-cv`;
    modal.style.display = 'block';
}

function closeCvModal() {
    document.getElementById('cvModal').style.display = 'none';
    document.getElementById('cvFrame').src = '';
}

function viewPhoto(applicantId) {
    const modal = document.getElementById('photoModal');
    const photoFrame = document.getElementById('photoFrame');
    photoFrame.src = `/admin/applicants/${applicantId}/view-photo`;
    modal.style.display = 'block';
}

function closePhotoModal() {
    document.getElementById('photoModal').style.display = 'none';
    document.getElementById('photoFrame').src = '';
}

function contactApplicant(applicantId) {
    // Get applicant WhatsApp data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const phoneNumber = normalizePhoneNumber(data.applicant.whatsapp);
                const message = `Halo ${data.applicant.name}, selamat! Anda telah berhasil lolos semua tahapan seleksi dan sekarang sudah onboard di ${data.job.company_name} sebagai ${data.job.position}.`;
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                window.open(whatsappUrl, '_blank');
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
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

// Initialize Bootstrap dropdowns
document.addEventListener('DOMContentLoaded', function() {
    const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    const dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
    
    // Handle dropdown positioning for table
    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
        const button = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (button && menu) {
            button.addEventListener('show.bs.dropdown', function() {
                const rect = button.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                
                if (spaceBelow < 200 && spaceAbove > 200) {
                    menu.style.top = 'auto';
                    menu.style.bottom = '100%';
                    menu.style.transform = 'translate3d(0px, -5px, 0px)';
                } else {
                    menu.style.top = '100%';
                    menu.style.bottom = 'auto';
                    menu.style.transform = 'translate3d(0px, 5px, 0px)';
                }
            });
        }
    });
});
</script>
@endpush
