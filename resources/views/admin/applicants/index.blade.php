@extends('admin.master_layout')
@section('title')
    <title>{{ __('Applicants Management') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Applicants Management') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Applicants') => '#',
        ]" />

        <div class="section-body">
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.applicants.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ __('Job Vacancy') }}</label>
                                    <select name="job_vacancy_id" class="form-control">
                                        <option value="">{{ __('All Jobs') }}</option>
                                        @foreach($jobVacancies as $job)
                                            <option value="{{ $job->id }}" {{ request('job_vacancy_id') == $job->id ? 'selected' : '' }}>
                                                {{ $job->position }} - {{ $job->company_name }}
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
                            <div class="col-md-2">
                                <div class="form-group mb-0 mt-4">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> {{ __('Filter') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Applicants Table -->
            <div class="card">
                <div class="card-header">
                    <div class="w-100">
                        <div class="btn-group status-filter-tabs" role="group" aria-label="Status Filter">
                            <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" 
                               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                {{ __('All') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" 
                               class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">
                                {{ __('Pending') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'sent']) }}" 
                               class="btn {{ request('status') == 'sent' ? 'btn-info' : 'btn-outline-info' }} btn-sm">
                                {{ __('Test Screening') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'check']) }}" 
                               class="btn {{ request('status') == 'check' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                {{ __('Check') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'short_call']) }}" 
                               class="btn {{ request('status') == 'short_call' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                {{ __('Short Call') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'group_interview']) }}" 
                               class="btn {{ request('status') == 'group_interview' ? 'btn-info' : 'btn-outline-info' }} btn-sm">
                                {{ __('Group Interview') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'test_psychology']) }}" 
                               class="btn {{ request('status') == 'test_psychology' ? 'btn-secondary' : 'btn-outline-secondary' }} btn-sm">
                                {{ __('Test Psychology') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'ojt']) }}" 
                               class="btn {{ request('status') == 'ojt' ? 'btn-dark' : 'btn-outline-dark' }} btn-sm">
                                {{ __('OJT') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'final_interview']) }}" 
                               class="btn {{ request('status') == 'final_interview' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                {{ __('Final Interview') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'sent_offering_letter']) }}" 
                               class="btn {{ request('status') == 'sent_offering_letter' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                {{ __('Sent Offering Letter') }}
                            </a>
                            <!-- <a href="{{ request()->fullUrlWithQuery(['status' => 'onboard']) }}" 
                               class="btn {{ request('status') == 'onboard' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                {{ __('Onboard') }}
                            </a> -->
                            <!-- <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" 
                               class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                {{ __('Rejected') }}
                            </a> -->
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected_by_applicant']) }}" 
                               class="btn {{ request('status') == 'rejected_by_applicant' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                {{ __('Rejected by Applicant') }}
                            </a>
                        </div>
                        <h4 class="mt-3">{{ __('Applicants List') }}</h4>
                    </div>
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
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Applied Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applications as $application)
                                @if($application->status != 'onboard' && $application->status != 'rejected')
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($application->user && $application->user->avatar)
                                                    <img src="{{ $application->user->avatar }}" 
                                                         alt="{{ $application->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="36" height="36">
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
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
                                            {{ $application->applicant->phone }}
                                            @if($application->applicant->whatsapp && $application->applicant->whatsapp != $application->applicant->phone)
                                                <br><small class="text-muted">{{ $application->applicant->whatsapp }}</small>
                                            @endif
                                        </td>
                                        <td>
                                                <span class="badge badge-info">{{ $application->jobVacancy->position }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $application->status_badge }}">
                                                {{ $application->status_text }}
                                            </span>
                                        </td>
                                        <td class="text-small px-0">{{ $application->created_at->format('d M Y H:i') }}</td>
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
                                                
                                                    @if($application->applicant->cv_path && $application->status == 'check')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="viewCv({{ $application->applicant->id }})">
                                                                <i class="fas fa-file-pdf me-2"></i>{{ __('View CV') }}
                                                            </a>
                                                        </li>
                                                @endif
                                                
                                                    <!-- Status-based actions -->
                                                    @if($application->status == 'check')
                                                        @if($application->applicant->hasCompletedScreening())
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="viewTestResult({{ $application->applicant->id }}, 'screening')">
                                                                    <i class="fas fa-chart-line me-2"></i>{{ __('View Screening Test Result') }}
                                                                </a>
                                                            </li>
                                                @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showNextStepModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-arrow-right me-2"></i>{{ __('Next Step') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                    @elseif($application->status == 'short_call')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="resendWhatsApp({{ $application->applicant->id }})">
                                                                <i class="fas fa-redo me-2"></i>{{ __('Resend WhatsApp') }}
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showGroupInterviewModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-users me-2"></i>{{ __('Next Step - Group Interview') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                    @elseif($application->status == 'group_interview')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showTestPsychologyModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-brain me-2"></i>{{ __('Next Step - Test Psychology') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'test_psychology')
                                                    @if($application->applicant->hasCompletedPsychology())
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="viewTestResult({{ $application->applicant->id }}, 'psychology')">
                                                                    <i class="fas fa-brain me-2"></i>{{ __('View Psychology Test Result') }}
                                                                </a>
                                                            </li>
                                                    @endif
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showOjtModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-briefcase me-2"></i>{{ __('Next Step - OJT') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'ojt')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showFinalInterviewModal({{ $application->applicant->id }}, {{ $application->id }}, 'ojt')">
                                                                <i class="fas fa-handshake me-2"></i>{{ __('Next Step - Final Interview') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'final_interview')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="showFinalInterviewModal({{ $application->applicant->id }}, {{ $application->id }}, 'final_interview')">
                                                                <i class="fas fa-envelope me-2"></i>{{ __('Next Step - Send Offering Letter') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'sent_offering_letter')
                                                        <li>
                                                            <a class="dropdown-item text-success" href="#" onclick="acceptApplicant({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-check me-2"></i>{{ __('Accept') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="rejectByApplicant({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Rejected by Applicant') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'rejected_by_applicant')
                                                        <li>
                                                            <a class="dropdown-item" href="#" onclick="resendOfferingLetter({{ $application->applicant->id }})">
                                                                <i class="fas fa-redo me-2"></i>{{ __('Resend Offering Letter') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-times me-2"></i>{{ __('Reject') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-warning" href="#" onclick="showRejectSaveTalentModal({{ $application->applicant->id }}, {{ $application->id }})">
                                                                <i class="fas fa-user-minus me-2"></i>{{ __('Reject + Save Talent') }}
                                                            </a>
                                                        </li>
                                                @elseif($application->status == 'onboard')
                                                    <span class="badge badge-success">{{ __('Onboarded') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('No applications found') }}</td>
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

<!-- Next Step Modal -->
<div id="nextStepModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Next Step - Send WhatsApp') }}</h3>
            <span class="close" onclick="closeNextStepModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="applicantId" value="">
                <input type="hidden" id="applicationId" value="">
                
                <div class="mb-3">
                    <label for="templateSelect" class="form-label">{{ __('Select WhatsApp Template') }}</label>
                    <select id="templateSelect" class="form-select" required onchange="previewTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                <div id="extraFields" style="display: none;">
                    <div class="mb-3">
                        <label for="stepDate" class="form-label">{{ __('Date') }}</label>
                        <input type="date" id="stepDate" class="form-control" placeholder="{{ __('Enter date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="stepTime" class="form-label">{{ __('Time') }}</label>
                        <input type="time" id="stepTime" class="form-control" placeholder="{{ __('Enter time') }}">
                    </div>
                    <div class="mb-3">
                        <label for="stepLocation" class="form-label">{{ __('Location') }}</label>
                        <input type="text" id="stepLocation" class="form-control" placeholder="{{ __('Enter location') }}">
                    </div>
                </div>
                <div id="extraFields2" style="display: none;">
                    <div class="mb-3">
                        <label for="startDate" class="form-label">{{ __('StartDate') }}</label>
                        <input type="date" id="startDate" class="form-control" placeholder="{{ __('Enter start date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">{{ __('EndDate') }}</label>
                        <input type="date" id="endDate" class="form-control" placeholder="{{ __('Enter end date') }}">
                    </div>
                </div>
                <div id="extraFields3" style="display: none;">
                    <div class="mb-3">
                        <label for="stepDate" class="form-label">{{ __('Date') }}</label>
                        <input type="date" id="stepDate" class="form-control" placeholder="{{ __('Enter date') }}">
                    </div>
                </div>

                
                <div class="mb-3" id="templatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="previewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">{{ __('Additional Notes (Optional)') }}</label>
                    <textarea id="notes" class="form-control" rows="3" placeholder="{{ __('Enter any additional notes...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeNextStepModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" onclick="sendNextStep()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Process') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Group Interview Modal -->
<div id="groupInterviewModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Next Step - Group Interview') }}</h3>
            <span class="close" onclick="closeGroupInterviewModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="groupInterviewApplicantId" value="">
                <input type="hidden" id="groupInterviewApplicationId" value="">
                
                <div class="mb-3">
                    <label for="groupInterviewTemplateSelect" class="form-label">{{ __('Select WhatsApp Template for Group Interview') }}</label>
                    <select id="groupInterviewTemplateSelect" class="form-select" required onchange="previewGroupInterviewTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="groupInterviewDate" class="form-label">{{ __('Date') }}</label>
                    <input type="date" id="groupInterviewDate" class="form-control" placeholder="{{ __('Enter date') }}">
                </div>
                <div class="mb-3">
                    <label for="groupInterviewTime" class="form-label">{{ __('Time') }}</label>
                    <input type="time" id="groupInterviewTime" class="form-control" placeholder="{{ __('Enter time') }}">
                </div>
                <div class="mb-3">
                    <label for="groupInterviewLocation" class="form-label">{{ __('Location') }}</label>
                    <input type="text" id="groupInterviewLocation" class="form-control" placeholder="{{ __('Enter location') }}">
                </div>
                <div class="mb-3" id="groupInterviewTemplatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="groupInterviewPreviewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="groupInterviewNotes" class="form-label">{{ __('Additional Notes (Optional)') }}</label>
                    <textarea id="groupInterviewNotes" class="form-control" rows="3" placeholder="{{ __('Enter any additional notes...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeGroupInterviewModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" onclick="sendGroupInterview()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Process') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Talent Form Modal -->
<div id="talentFormModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 60%; border-radius: 8px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 id="talentFormTitle">{{ __('Talent Information') }}</h3>
            <span class="close" onclick="closeTalentFormModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form id="talentForm">
                <input type="hidden" id="talentApplicantId" value="">
                <input type="hidden" id="talentApplicationId" value="">
                <input type="hidden" id="talentActionType" value=""> <!-- 'group_interview' or 'reject_save' -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="talentName" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="talentName" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="talentLevelPotential" class="form-label">{{ __('Level Potential') }}</label>
                            <select id="talentLevelPotential" class="form-select">
                                <option value="">{{ __('Select level potential') }}</option>
                                <option value="Junior Staff">Junior Staff</option>
                                <option value="Senior Staff">Senior Staff</option>
                                <option value="Koordinator">Koordinator</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Manager">Manager</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="talentPotential" class="form-label">{{ __('Talent Potential') }}</label>
                            <select id="talentPotential" class="form-select">
                                <option value="">{{ __('Select talent potential') }}</option>
                                <option value="Frontline">Frontline</option>
                                <option value="Backline">Backline</option>
                                <option value="Officer">Officer</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="talentPositionPotential" class="form-label">{{ __('Position Potential') }}</label>
                            <input type="text" id="talentPositionPotential" class="form-control" placeholder="e.g., Kasir, Packer, SPG, Picker">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="talentCommunication" class="form-label">{{ __('Communication') }}</label>
                            <select id="talentCommunication" class="form-select">
                                <option value="">{{ __('Select score') }}</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Below Average</option>
                                <option value="3">3 - Average</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="talentAttitude" class="form-label">{{ __('Attitude') }}</label>
                            <select id="talentAttitude" class="form-select">
                                <option value="">{{ __('Select score') }}</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Below Average</option>
                                <option value="3">3 - Average</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="talentInitiative" class="form-label">{{ __('Initiative') }}</label>
                            <select id="talentInitiative" class="form-select">
                                <option value="">{{ __('Select score') }}</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Below Average</option>
                                <option value="3">3 - Average</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="talentLeadership" class="form-label">{{ __('Leadership') }}</label>
                            <select id="talentLeadership" class="form-select">
                                <option value="">{{ __('Select score') }}</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Below Average</option>
                                <option value="3">3 - Average</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="talentNotes" class="form-label">{{ __('Notes') }}</label>
                    <textarea id="talentNotes" class="form-control" rows="4" placeholder="{{ __('Enter additional notes about this talent...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeTalentFormModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" onclick="saveTalentData()">
                        <i class="fas fa-save"></i> {{ __('Save Talent Data') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OJT Modal -->
<div id="ojtModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Next Step - OJT') }}</h3>
            <span class="close" onclick="closeOjtModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="ojtApplicantId" value="">
                <input type="hidden" id="ojtApplicationId" value="">
                
                <div class="mb-3">
                    <label for="ojtTemplateSelect" class="form-label">{{ __('Select WhatsApp Template for OJT') }}</label>
                    <select id="ojtTemplateSelect" class="form-select" required onchange="previewOjtTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                <div class="mb-3">
                        <label for="ojtStartDate" class="form-label">{{ __('Start Date') }}</label>
                        <input type="date" id="ojtStartDate" class="form-control" placeholder="{{ __('Enter start date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="ojtEndDate" class="form-label">{{ __('End Date') }}</label>
                        <input type="date" id="ojtEndDate" class="form-control" placeholder="{{ __('Enter end date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="ojtLocation" class="form-label">{{ __('Location') }}</label>
                        <input type="text" id="ojtLocation" class="form-control" placeholder="{{ __('Enter location') }}">
                    </div>

                <div class="mb-3" id="ojtTemplatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="ojtPreviewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="ojtNotes" class="form-label">{{ __('Additional Notes (Optional)') }}</label>
                    <textarea id="ojtNotes" class="form-control" rows="3" placeholder="{{ __('Enter any additional notes...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeOjtModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" onclick="sendOjt()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Process') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Final Interview Modal -->
<div id="finalInterviewModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Next Step - Final Interview') }}</h3>
            <span class="close" onclick="closeFinalInterviewModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="finalInterviewApplicantId" value="">
                <input type="hidden" id="finalInterviewApplicationId" value="">
                
                <div class="mb-3">
                    <label for="finalInterviewTemplateSelect" class="form-label">{{ __('Select WhatsApp Template') }}</label>
                    <select id="finalInterviewTemplateSelect" class="form-select" required onchange="previewFinalInterviewTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                <div id="extraFields4">
                <div class="mb-3">
                        <label for="interviewDate" class="form-label">{{ __('Date') }}</label>
                        <input type="date" id="interviewDate" class="form-control" placeholder="{{ __('Enter date') }}">
                    </div>
                    <div class="mb-3">
                        <label for="interviewTime" class="form-label">{{ __('Time') }}</label>
                        <input type="time" id="interviewTime" class="form-control" placeholder="{{ __('Enter time') }}">
                    </div>
                    <div class="mb-3">
                        <label for="interviewLocation" class="form-label">{{ __('Location') }}</label>
                        <input type="text" id="interviewLocation" class="form-control" placeholder="{{ __('Enter location') }}">
                    </div>
                </div>
                
                <div class="mb-3" id="finalInterviewTemplatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="finalInterviewPreviewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="finalInterviewNotes" class="form-label">{{ __('Additional Notes (Optional)') }}</label>
                    <textarea id="finalInterviewNotes" class="form-control" rows="3" placeholder="{{ __('Enter any additional notes...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeFinalInterviewModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-success" onclick="sendFinalInterview()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Process') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Save Talent Modal -->
<div id="rejectSaveTalentModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Reject + Save Talent') }}</h3>
            <span class="close" onclick="closeRejectSaveTalentModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="rejectSaveTalentApplicantId" value="">
                <input type="hidden" id="rejectSaveTalentApplicationId" value="">
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>{{ __('Warning') }}:</strong> {{ __('This will reject the applicant but save them to the talent database for future reference.') }}
                </div>
                
                <div class="mb-3">
                    <label for="rejectSaveTalentTemplateSelect" class="form-label">{{ __('Select WhatsApp Template for Rejection') }}</label>
                    <select id="rejectSaveTalentTemplateSelect" class="form-select" required onchange="previewRejectSaveTalentTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                
                <div class="mb-3" id="rejectSaveTalentTemplatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="rejectSaveTalentPreviewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="rejectNotes" class="form-label">{{ __('Rejection Reason') }}</label>
                    <textarea id="rejectNotes" class="form-control" rows="3" placeholder="{{ __('Enter rejection reason...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectSaveTalentModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-warning" onclick="processRejectSaveTalent()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Save Talent') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 50%; border-radius: 8px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ __('Reject Applicant') }}</h3>
            <span class="close" onclick="closeRejectModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="rejectApplicantId" value="">
                <input type="hidden" id="rejectApplicationId" value="">
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>{{ __('Warning') }}:</strong> {{ __('This will reject the applicant and send a rejection message via WhatsApp.') }}
                </div>
                
                <div class="mb-3">
                    <label for="rejectTemplateSelect" class="form-label">{{ __('Select WhatsApp Template for Rejection') }}</label>
                    <select id="rejectTemplateSelect" class="form-select" required onchange="previewRejectTemplate()">
                        <option value="">{{ __('Loading templates...') }}</option>
                    </select>
                </div>
                
                <div class="mb-3" id="rejectTemplatePreview" style="display: none;">
                    <label class="form-label">{{ __('Template Preview') }}</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 24px;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="bg-light p-3 rounded" style="max-width: 300px;">
                                        <div id="rejectPreviewContent" class="text-dark"></div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">{{ __('Variables will be replaced with actual data') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="rejectReason" class="form-label">{{ __('Rejection Reason') }}</label>
                    <textarea id="rejectReason" class="form-control" rows="3" placeholder="{{ __('Enter rejection reason...') }}"></textarea>
                </div>
                
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-danger" onclick="processReject()">
                        <i class="fab fa-whatsapp"></i> {{ __('Open WhatsApp & Reject') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Test Result Modal -->
<div id="testResultModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 2% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 1000px; border-radius: 8px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 id="testResultModalTitle">{{ __('Test Result') }}</h3>
            <span class="close" onclick="closeTestResultModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <div id="testResultContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
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

/* Status Filter Tab Buttons */
.status-filter-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.status-filter-tabs .btn {
    margin-right: 0;
    margin-bottom: 4px;
    white-space: nowrap;
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}

.status-filter-tabs .btn {
    max-width: 220px;
}

/* Responsive for smaller screens */
@media (max-width: 1200px) {
    .status-filter-tabs {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .status-filter-tabs .btn {
        width: 100%;
        margin-bottom: 2px;
    }
}

@media (max-width: 768px) {
    .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .card-header h4 {
        margin-bottom: 15px;
    }
    
    .status-filter-tabs {
        width: 100%;
    }
}

/* WhatsApp Preview Styles */
#templatePreview .card {
    border: 1px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#templatePreview .bg-light {
    background-color: #f0f0f0 !important;
    border-radius: 8px;
    position: relative;
}

#templatePreview .bg-light::before {
    content: '';
    position: absolute;
    top: 0;
    left: -8px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid #f0f0f0;
}

#previewContent {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.4;
    white-space: pre-wrap;
}

/* Modal improvements */
.modal-content {
    max-height: 90vh;
    overflow-y: auto;
}

#nextStepModal .modal-content {
    width: 60%;
    max-width: 600px;
}
</style>
@endpush

@push('js')
<script>
function nextStep(applicantId, applicationId) {
    if (confirm('{{ __("Move to next step (Short Call)?") }}')) {
        fetch(`/admin/applicants/${applicantId}/next-step`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                application_id: applicationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("Moved to next step successfully!") }}');
                location.reload();
            } else {
                alert('{{ __("Error moving to next step") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error moving to next step") }}');
        });
    }
}

function rejectApplicant(applicantId) {
    const reason = prompt('{{ __("Enter reason for rejection:") }}');
    if (reason !== null) {
        fetch(`/admin/applicants/${applicantId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notes: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("Applicant rejected successfully!") }}');
                location.reload();
            } else {
                alert('{{ __("Error rejecting applicant") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Error rejecting applicant") }}');
        });
    }
}

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

function showNextStepModal(applicantId, applicationId) {
    // Show next step modal with WhatsApp templates
    const modal = document.getElementById('nextStepModal');
    document.getElementById('applicantId').value = applicantId;
    document.getElementById('applicationId').value = applicationId;
    modal.style.display = 'block';
    
    // Load WhatsApp templates
    loadWhatsAppTemplates();
}

function closeNextStepModal() {
    document.getElementById('nextStepModal').style.display = 'none';
}

let templatesData = [];

function loadWhatsAppTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('templateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'short_call_invitation' || 
                        template.type === 'group_interview_invitation' ||
                        template.type === 'test_psychology_invitation' ||
                        template.type === 'ojt_invitation' ||
                        template.type === 'final_interview_invitation') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewTemplate() {
    const select = document.getElementById('templateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('templatePreview');
    const previewContent = document.getElementById('previewContent');
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    if (templateId == 5 || templateId == 6 || templateId == 9 || templateId == 10) {
        document.getElementById('extraFields').style.display = 'block';     
    } else if (templateId == 7 || templateId == 8) {
        document.getElementById('extraFields2').style.display = 'block';
    } else if (templateId == 18){
        document.getElementById('extraFields3').style.display = 'block';
    }
    // Find template data
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        previewDiv.style.display = 'none';
        return;
    }
    
    // Replace variables with sample data
    let previewText = template.template;
    const sampleData = {
        'NAME': 'John Doe',
        'POSITION': 'Senior Developer',
        'COMPANY': 'PT. Example Company',
        'DATE': 'Monday, 15 September 2025',
        'TIME': '10:00 AM',
        'LOCATION': 'Jakarta',
        'START_DATE': '2025-09-15',
        'END_DATE': '2025-09-16'
    };
    
    // Replace variables
    Object.keys(sampleData).forEach(key => {
        const regex = new RegExp(`{${key}}`, 'g');
        previewText = previewText.replace(regex, sampleData[key]);
    });
    
    // Display preview
    previewContent.innerHTML = previewText.replace(/\n/g, '<br>');
    previewDiv.style.display = 'block';
}

function sendNextStep() {
    const applicantId = document.getElementById('applicantId').value;
    const applicationId = document.getElementById('applicationId').value;
    const templateId = document.getElementById('templateSelect').value;
    const notes = document.getElementById('notes').value;
    const date = document.getElementById('stepDate').value || '';
    const time = document.getElementById('stepTime').value || '';
    const location = document.getElementById('stepLocation').value || '';
    const startDate = document.getElementById('startDate').value || '';
    const endDate = document.getElementById('endDate').value || '';

    if (!templateId) {
        alert('Please select a template');
        return;
    }
    // Find selected template
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant data for WhatsApp URL
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data);
                // Replace template variables with actual data
                let message = template.template;
                let status = data.application.status;
                const variables = {
                'NAME': data.applicant.name,
                'POSITION': data.job.position,
                'COMPANY': data.job.company_name
                };

                if (templateId == 5 || templateId == 6 || templateId == 9 || templateId == 10) {
                variables['DATE'] = new Date(date).toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                });
                variables['TIME'] = time;
                variables['LOCATION'] = location;
                } else if (templateId == 7 || templateId == 8) {
                variables['START_DATE'] = new Date(startDate).toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                });
                variables['END_DATE'] = new Date(endDate).toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                });
                variables['LOCATION'] = location;
                } else if (templateId == 18) {
                variables['DATE'] = new Date(date).toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                });
                } else {
                variables['DATE'] = new Date().toLocaleDateString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                });
                variables['TIME'] = new Date().toLocaleTimeString('id-ID', { 
                    hour: '2-digit', minute: '2-digit' 
                });
                };
                
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Add notes if provided
                if (notes.trim()) {
                    message += '\n\n' + notes.trim();
                }
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, ''); // Remove non-numeric characters
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Update status in background
                updateApplicantStatus(applicantId, applicationId, templateId, notes);
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

function updateApplicantStatus(applicantId, applicationId, templateId, notes) {
    fetch(`/admin/applicants/${applicantId}/next-step`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            application_id: applicationId,
            template_id: templateId,
            notes: notes 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeNextStepModal();
            location.reload();
        } else {
            console.error('Error updating status:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

// Group Interview Modal Functions
function showGroupInterviewModal(applicantId, applicationId) {
    const modal = document.getElementById('groupInterviewModal');
    document.getElementById('groupInterviewApplicantId').value = applicantId;
    document.getElementById('groupInterviewApplicationId').value = applicationId;
    modal.style.display = 'block';
    
    // Load WhatsApp templates for group interview
    loadGroupInterviewTemplates();
}

function closeGroupInterviewModal() {
    document.getElementById('groupInterviewModal').style.display = 'none';
}

function loadGroupInterviewTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('groupInterviewTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'group_interview_invitation') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewGroupInterviewTemplate() {
    const select = document.getElementById('groupInterviewTemplateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('groupInterviewTemplatePreview');
    const previewContent = document.getElementById('groupInterviewPreviewContent');
    
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (template) {
        // Replace variables with sample data
        let message = template.template;
        const sampleData = {
            'NAME': 'John Doe',
            'POSITION': 'Software Developer',
            'COMPANY': 'Tech Company',
            'DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'TIME': new Date().toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            }),
            'LOCATION': 'Jakarta'
        };
        
        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(`{${key}}`, 'g');
            message = message.replace(regex, sampleData[key]);
        });
        
        previewContent.textContent = message;
        previewDiv.style.display = 'block';
    }
}

function sendGroupInterview() {
    const applicantId = document.getElementById('groupInterviewApplicantId').value;
    const applicationId = document.getElementById('groupInterviewApplicationId').value;
    const templateId = document.getElementById('groupInterviewTemplateSelect').value;
    const notes = document.getElementById('groupInterviewNotes').value;
    const date = document.getElementById('groupInterviewDate').value || '';
    const time = document.getElementById('groupInterviewTime').value || '';
    const location = document.getElementById('groupInterviewLocation').value || '';
    if (!templateId) {
        alert('Please select a WhatsApp template');
        return;
    }
    if (!date) {
        alert('Please enter a date');
        return;
    }
    if (!time) {
        alert('Please enter a time');
        return;
    }
    if (!location) {
        alert('Please enter a location');
        return;
    }
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant and job data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace template variables with actual data
                let message = template.template;
                const variables = {
                    'NAME': data.applicant.name,
                    'POSITION': data.job.position,
                    'COMPANY': data.job.company_name,
                    'DATE': new Date(date).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'TIME': time,
                    'LOCATION': location
                };
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Add notes if provided
                if (notes.trim()) {
                    message += '\n\n' + notes.trim();
                }
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, '');
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Update status to group interview
                fetch(`/admin/applicants/${applicantId}/group-interview`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        application_id: applicationId,
                        name: data.applicant.name,
                        level_potential: '',
                        talent_potential: '',
                        position_potential: '',
                        communication: '',
                        attitude: '',
                        initiative: '',
                        leadership: '',
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close WhatsApp modal and show talent form modal
                        closeGroupInterviewModal();
                        showTalentFormModal(applicantId, 'group_interview', applicationId);
                    } else {
                        alert('Error updating status: ' + data.message);
                        closeGroupInterviewModal();
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('Error updating status');
                    closeGroupInterviewModal();
                });
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

function updateApplicantToGroupInterview(applicantId, templateId, attitudeLevel, city, notes) {
    fetch(`/admin/applicants/${applicantId}/group-interview`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            template_id: templateId,
            attitude_level: attitudeLevel,
            city: city,
            notes: notes 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeGroupInterviewModal();
            location.reload();
        } else {
            console.error('Error updating status:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

// Reject Save Talent Modal Functions
function showRejectSaveTalentModal(applicantId, applicationId) {
    const modal = document.getElementById('rejectSaveTalentModal');
    document.getElementById('rejectSaveTalentApplicantId').value = applicantId;
    document.getElementById('rejectSaveTalentApplicationId').value = applicationId;
    modal.style.display = 'block';
    
    // Load WhatsApp templates for rejection
    loadRejectSaveTalentTemplates();
}

function closeRejectSaveTalentModal() {
    document.getElementById('rejectSaveTalentModal').style.display = 'none';
}

function loadRejectSaveTalentTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('rejectSaveTalentTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'rejection_message') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewRejectSaveTalentTemplate() {
    const select = document.getElementById('rejectSaveTalentTemplateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('rejectSaveTalentTemplatePreview');
    const previewContent = document.getElementById('rejectSaveTalentPreviewContent');
    
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (template) {
        // Replace variables with sample data
        let message = template.template;
        const sampleData = {
            'NAME': 'John Doe',
            'POSITION': 'Software Developer',
            'COMPANY': 'Tech Company',
            'DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'REASON': 'Not meeting requirements'
        };
        
        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(`{${key}}`, 'g');
            message = message.replace(regex, sampleData[key]);
        });
        
        previewContent.textContent = message;
        previewDiv.style.display = 'block';
    }
}

function processRejectSaveTalent() {
    const applicantId = document.getElementById('rejectSaveTalentApplicantId').value;
    const applicationId = document.getElementById('rejectSaveTalentApplicationId').value;
    const templateId = document.getElementById('rejectSaveTalentTemplateSelect').value;
    const notes = document.getElementById('rejectNotes').value;
    
    if (!templateId) {
        alert('Please select a WhatsApp template');
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant and job data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace template variables with actual data
                let message = template.template;
                const variables = {
                    'NAME': data.applicant.name,
                    'POSITION': data.job.position,
                    'COMPANY': data.job.company_name,
                    'DATE': new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'REASON': notes || 'Not meeting requirements'
                };
                
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, '');
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Close WhatsApp modal and show talent form modal
                closeRejectSaveTalentModal();
                showTalentFormModal(applicantId, 'reject_save', applicationId);
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

function processRejectAndSaveTalent(applicantId, attitudeLevel, city, notes) {
    fetch(`/admin/applicants/${applicantId}/reject-save-talent`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            attitude_level: attitudeLevel,
            city: city,
            notes: notes 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRejectSaveTalentModal();
            location.reload();
        } else {
            console.error('Error processing reject save talent:', data.message);
        }
    })
    .catch(error => {
        console.error('Error processing reject save talent:', error);
    });
}

// Resend WhatsApp Function
function resendWhatsApp(applicantId) {
    if (confirm('Are you sure you want to resend WhatsApp invitation for short call?')) {
        // This will use the same logic as Next Step but without changing status
        showNextStepModal(applicantId);
    }
}

// Reject Modal Functions
function showRejectModal(applicantId, applicationId) {
    const modal = document.getElementById('rejectModal');
    document.getElementById('rejectApplicantId').value = applicantId;
    document.getElementById('rejectApplicationId').value = applicationId;
    modal.style.display = 'block';
    
    // Load WhatsApp templates for rejection
    loadRejectTemplates();
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
}

function loadRejectTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('rejectTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'rejection_message') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewRejectTemplate() {
    const select = document.getElementById('rejectTemplateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('rejectTemplatePreview');
    const previewContent = document.getElementById('rejectPreviewContent');
    
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (template) {
        // Replace variables with sample data
        let message = template.template;
        const sampleData = {
            'NAME': 'John Doe',
            'POSITION': 'Software Developer',
            'COMPANY': 'Tech Company',
            'DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'REASON': 'Not meeting requirements'
        };
        
        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(`{${key}}`, 'g');
            message = message.replace(regex, sampleData[key]);
        });
        
        previewContent.textContent = message;
        previewDiv.style.display = 'block';
    }
}

function processReject() {
    const applicantId = document.getElementById('rejectApplicantId').value;
    const applicationId = document.getElementById('rejectApplicationId').value;
    const templateId = document.getElementById('rejectTemplateSelect').value;
    const reason = document.getElementById('rejectReason').value;
    
    if (!templateId) {
        alert('Please select a template');
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant and job data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace template variables with actual data
                let message = template.template;
                const variables = {
                    'NAME': data.applicant.name,
                    'POSITION': data.job.position,
                    'COMPANY': data.job.company_name,
                    'DATE': new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'REASON': reason || 'Not meeting requirements'
                };
                
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, '');
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Update status in background
                updateApplicantReject(applicantId, applicationId, templateId, reason);
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

function updateApplicantReject(applicantId, applicationId, templateId, reason) {
    fetch(`/admin/applicants/${applicantId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            application_id: applicationId,
            template_id: templateId,
            reason: reason 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRejectModal();
            location.reload();
        } else {
            console.error('Error updating status:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

// Talent Form Modal Functions
function showTalentFormModal(applicantId, actionType, applicationId = null) {
    const modal = document.getElementById('talentFormModal');
    const title = document.getElementById('talentFormTitle');
    
    // Debug logging
    console.log('Show Talent Form Modal - Applicant ID:', applicantId);
    console.log('Show Talent Form Modal - Application ID:', applicationId);
    console.log('Show Talent Form Modal - Action Type:', actionType);
    
    document.getElementById('talentApplicantId').value = applicantId;
    document.getElementById('talentApplicationId').value = applicationId || '';
    document.getElementById('talentActionType').value = actionType;
    
    // Verify the values were set correctly
    console.log('Set Applicant ID to:', document.getElementById('talentApplicantId').value);
    console.log('Set Action Type to:', document.getElementById('talentActionType').value);
    
    // Set title based on action type
    if (actionType === 'group_interview') {
        title.textContent = '{{ __("Talent Information - Group Interview") }}';
    } else if (actionType === 'reject_save') {
        title.textContent = '{{ __("Talent Information - Reject Save") }}';
    } else if (actionType === 'ojt') {
        title.textContent = '{{ __("Talent Information - OJT") }}';
    } else if (actionType === 'final_interview') {
        title.textContent = '{{ __("Talent Information - Final Interview") }}';
    } else {
        title.textContent = '{{ __("Talent Information") }}';
    }
    
    // Load applicant data
    loadApplicantDataForTalent(applicantId);
    
    modal.style.display = 'block';
}

function closeTalentFormModal() {
    document.getElementById('talentFormModal').style.display = 'none';
    // Reset form
    document.getElementById('talentForm').reset();
}

function loadApplicantDataForTalent(applicantId) {
    console.log('Loading talent data for applicant:', applicantId);
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            console.log('WhatsApp data response:', data);
            if (data.success) {
                // Load basic applicant data
                document.getElementById('talentName').value = data.applicant.name || '';
                
                // Load existing talent data if available
                if (data.talent) {
                    console.log('Found existing talent data:', data.talent);
                    document.getElementById('talentLevelPotential').value = data.talent.level_potential || '';
                    document.getElementById('talentPotential').value = data.talent.talent_potential || '';
                    document.getElementById('talentPositionPotential').value = data.talent.position_potential || '';
                    document.getElementById('talentCommunication').value = data.talent.communication || '';
                    document.getElementById('talentAttitude').value = data.talent.attitude || '';
                    document.getElementById('talentInitiative').value = data.talent.initiative || '';
                    document.getElementById('talentLeadership').value = data.talent.leadership || '';
                    document.getElementById('talentNotes').value = data.talent.notes || '';
                    console.log('Talent form fields populated');
                } else {
                    console.log('No existing talent data found');
                    // Clear form if no existing talent data
                    document.getElementById('talentLevelPotential').value = '';
                    document.getElementById('talentPotential').value = '';
                    document.getElementById('talentPositionPotential').value = '';
                    document.getElementById('talentCommunication').value = '';
                    document.getElementById('talentAttitude').value = '';
                    document.getElementById('talentInitiative').value = '';
                    document.getElementById('talentLeadership').value = '';
                    document.getElementById('talentNotes').value = '';
                }
            } else {
                console.error('Error loading applicant data:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading applicant data:', error);
        });
}

function saveTalentData() {
    const applicantId = document.getElementById('talentApplicantId').value;
    const applicationId = document.getElementById('talentApplicationId').value;
    const actionType = document.getElementById('talentActionType').value;
    
    // Debug logging
    console.log('Save Talent Data - Applicant ID:', applicantId);
    console.log('Save Talent Data - Application ID:', applicationId);
    console.log('Save Talent Data - Action Type:', actionType);
    console.log('Action Type Type:', typeof actionType);
    console.log('Action Type Length:', actionType ? actionType.length : 'null/undefined');
    
    const talentData = {
        name: document.getElementById('talentName').value,
        level_potential: document.getElementById('talentLevelPotential').value,
        talent_potential: document.getElementById('talentPotential').value,
        position_potential: document.getElementById('talentPositionPotential').value,
        communication: document.getElementById('talentCommunication').value,
        attitude: document.getElementById('talentAttitude').value,
        initiative: document.getElementById('talentInitiative').value,
        leadership: document.getElementById('talentLeadership').value,
        notes: document.getElementById('talentNotes').value
    };
    
    // Add application_id if available
    if (applicationId) {
        talentData.application_id = applicationId;
    }
    
    // Add required fields for reject_save action
    if (actionType === 'reject_save') {
        talentData.application_id = applicationId;
    }
    
    // Determine endpoint based on action type
    let endpoint = '';
    if (actionType === 'group_interview') {
        endpoint = `/admin/applicants/${applicantId}/group-interview`;
    } else if (actionType === 'reject_save') {
        endpoint = `/admin/applicants/${applicantId}/reject-save-talent`;
    } else if (actionType === 'ojt') {
        endpoint = `/admin/applicants/${applicantId}/ojt`;
    } else if (actionType === 'final_interview') {
        endpoint = `/admin/applicants/${applicantId}/final-interview`;
    } else {
        console.error('Invalid action type:', actionType);
        alert('Invalid action type: ' + actionType);
        return;
    }
    
    
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(talentData)
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Server response (not ok):', text);
                console.error('Response status:', response.status);
                console.error('Response headers:', response.headers);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                console.error('Content-Type:', contentType);
                console.error('Response status:', response.status);
                throw new Error('Response is not JSON');
            });
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeTalentFormModal();
            location.reload();
        } else {
            console.error('Error saving talent data:', data.message);
            alert('Error saving talent data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving talent data:', error);
        console.error('Response might be HTML instead of JSON');
        alert('Error saving talent data. Please check the console for details.');
    });
}

// New Status Modal Functions
function showTestPsychologyModal(applicantId, applicationId) {
    // Show WhatsApp modal first
    showNextStepModal(applicantId, applicationId);
    // After WhatsApp is sent, show talent form modal
    // This will be handled in the existing flow
}


// OJT Modal Functions
function showOjtModal(applicantId, applicationId) {
    const modal = document.getElementById('ojtModal');
    document.getElementById('ojtApplicantId').value = applicantId;
    document.getElementById('ojtApplicationId').value = applicationId;
    modal.style.display = 'block';
    
    // Load WhatsApp templates for OJT
    loadOjtTemplates();
}

function closeOjtModal() {
    document.getElementById('ojtModal').style.display = 'none';
}

function loadOjtTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('ojtTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'ojt_invitation') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewOjtTemplate() {
    const select = document.getElementById('ojtTemplateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('ojtTemplatePreview');
    const previewContent = document.getElementById('ojtPreviewContent');
    
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (template) {
        // Replace variables with sample data
        let message = template.template;
        const sampleData = {
            'NAME': 'John Doe',
            'POSITION': 'Software Developer',
            'COMPANY': 'Tech Company',
            'START_DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'END_DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'TIME': new Date().toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            }),
            'LOCATION': 'Malang',
        };
        
        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(`{${key}}`, 'g');
            message = message.replace(regex, sampleData[key]);
        });
        
        previewContent.textContent = message;
        previewDiv.style.display = 'block';
    }
}

function sendOjt() {
    const applicantId = document.getElementById('ojtApplicantId').value;
    const applicationId = document.getElementById('ojtApplicationId').value;
    const templateId = document.getElementById('ojtTemplateSelect').value;
    const notes = document.getElementById('ojtNotes').value;
    const startdate = document.getElementById('ojtStartDate').value || '';
    const enddate = document.getElementById('ojtEndDate').value || '';
    const location = document.getElementById('ojtLocation').value || '';

    
    if (!templateId) {
        alert('Please select a WhatsApp template');
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant and job data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace template variables with actual data
                let message = template.template;
                const variables = {
                    'NAME': data.applicant.name,
                    'POSITION': data.job.position,
                    'COMPANY': data.job.company_name,
                    'START_DATE': new Date(startdate).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'END_DATE': new Date(enddate).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'LOCATION' : location,
                };
                
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Add notes if provided
                if (notes.trim()) {
                    message += '\n\n' + notes.trim();
                }
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, '');
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Update status to next step
                fetch(`/admin/applicants/${applicantId}/next-step`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        application_id: applicationId,
                        template_id: templateId,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close WhatsApp modal and show talent form modal
                        closeOjtModal();
                        showTalentFormModal(applicantId, 'ojt', applicationId);
                    } else {
                        alert('Error updating status: ' + data.message);
                        closeOjtModal();
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('Error updating status');
                    closeOjtModal();
                });
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

// Final Interview Modal Functions
function showFinalInterviewModal(applicantId, applicationId, currentStatus = 'ojt') {
    const modal = document.getElementById('finalInterviewModal');
    const title = modal.querySelector('h3');
    const extraFields = document.getElementById('extraFields4');
    
    document.getElementById('finalInterviewApplicantId').value = applicantId;
    document.getElementById('finalInterviewApplicationId').value = applicationId;
    
    // Set title and template based on current status
    if (currentStatus === 'final_interview') {
        title.textContent = '{{ __("Next Step - Send Offering Letter") }}';
        extraFields.style.display = 'none';
        loadOfferingLetterTemplates();
    } else {
        title.textContent = '{{ __("Next Step - Final Interview") }}';
        loadFinalInterviewTemplates();
    }
    
    modal.style.display = 'block';
}

function closeFinalInterviewModal() {
    document.getElementById('finalInterviewModal').style.display = 'none';
}

function loadFinalInterviewTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('finalInterviewTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'final_interview_invitation') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function loadOfferingLetterTemplates() {
    fetch('/admin/whatsapp-templates/get')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('finalInterviewTemplateSelect');
            select.innerHTML = '<option value="">Select Template</option>';
            if (data.success && data.templates) {
                // Store templates globally for preview
                templatesData = data.templates;
                data.templates.forEach(template => {
                    if (template.type === 'offering_letter_invitation') {
                        const option = document.createElement('option');
                        option.value = template.id;
                        option.textContent = template.name;
                        select.appendChild(option);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading templates:', error);
        });
}

function previewFinalInterviewTemplate() {
    const select = document.getElementById('finalInterviewTemplateSelect');
    const templateId = select.value;
    const previewDiv = document.getElementById('finalInterviewTemplatePreview');
    const previewContent = document.getElementById('finalInterviewPreviewContent');
    
    if (!templateId) {
        previewDiv.style.display = 'none';
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (template) {
        // Replace variables with sample data
        let message = template.template;
        const sampleData = {
            'NAME': 'John Doe',
            'POSITION': 'Software Developer',
            'COMPANY': 'Tech Company',
            'DATE': new Date().toLocaleDateString('id-ID', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            }),
            'TIME': new Date().toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            }), 
            'LOCATION': 'Malang'
        };
        
        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(`{${key}}`, 'g');
            message = message.replace(regex, sampleData[key]);
        });
        
        previewContent.textContent = message;
        previewDiv.style.display = 'block';
    }
}

function sendFinalInterview() {
    const applicantId = document.getElementById('finalInterviewApplicantId').value;
    const applicationId = document.getElementById('finalInterviewApplicationId').value;
    const templateId = document.getElementById('finalInterviewTemplateSelect').value;
    const notes = document.getElementById('finalInterviewNotes').value;
    const date = document.getElementById('interviewDate').value || '';
    const time = document.getElementById('interviewTime').value || '';
    const location = document.getElementById('interviewLocation').value || '';


    
    if (!templateId) {
        alert('Please select a WhatsApp template');
        return;
    }
    
    const template = templatesData.find(t => t.id == templateId);
    if (!template) {
        alert('Template not found');
        return;
    }
    
    // Get applicant and job data
    fetch(`/admin/applicants/${applicantId}/whatsapp-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Replace template variables with actual data
                let message = template.template;
                const variables = {
                    'NAME': data.applicant.name,
                    'POSITION': data.job.position,
                    'COMPANY': data.job.company_name,
                    'DATE': new Date(date).toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    }),
                    'TIME': time,
                    'LOCATION': location
                };
                
                // Replace variables in template
                Object.keys(variables).forEach(key => {
                    const regex = new RegExp(`{${key}}`, 'g');
                    message = message.replace(regex, variables[key]);
                });
                
                // Add notes if provided
                if (notes.trim()) {
                    message += '\n\n' + notes.trim();
                }
                
                // Create WhatsApp URL
                const phoneNumber = data.applicant.whatsapp.replace(/[^0-9]/g, '');
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${phoneNumber}&text=${encodeURIComponent(message)}&type=phone_number&app_absent=0`;
                
                // Open WhatsApp in new tab
                window.open(whatsappUrl, '_blank');
                
                // Update status to next step
                fetch(`/admin/applicants/${applicantId}/next-step`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        application_id: applicationId,
                        template_id: templateId,
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close WhatsApp modal and show talent form modal
                        closeFinalInterviewModal();
                        showTalentFormModal(applicantId, 'final_interview', applicationId);
                    } else {
                        alert('Error updating status: ' + data.message);
                        closeFinalInterviewModal();
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('Error updating status');
                    closeFinalInterviewModal();
                });
                
            } else {
                alert('Error getting applicant data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error getting applicant data');
        });
}

function sendOfferingLetter(applicantId) {
    if (confirm('Are you sure you want to send offering letter to this applicant?')) {
        fetch(`/admin/applicants/${applicantId}/send-offering-letter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Offering letter sent successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending offering letter');
        });
    }
}

function acceptApplicant(applicantId, applicationId) {
    if (confirm('Are you sure you want to accept this applicant?')) {
        fetch(`/admin/applicants/${applicantId}/accept`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                application_id: applicationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Applicant accepted and onboarded successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error accepting applicant');
        });
    }
}

function rejectByApplicant(applicantId, applicationId) {
    if (confirm('Mark this applicant as rejected by applicant?')) {
        fetch(`/admin/applicants/${applicantId}/reject-by-applicant`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                application_id: applicationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Applicant marked as rejected by applicant');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating applicant status');
        });
    }
}

function resendOfferingLetter(applicantId) {
    if (confirm('Resend offering letter to this applicant?')) {
        fetch(`/admin/applicants/${applicantId}/resend-offering-letter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Offering letter resent successfully');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error resending offering letter');
        });
    }
}

// Test Result Modal Functions
function viewTestResult(applicantId, testType) {
    const modal = document.getElementById('testResultModal');
    const modalTitle = document.getElementById('testResultModalTitle');
    const modalContent = document.getElementById('testResultContent');
    
    // Set modal title based on test type
    if (testType === 'screening') {
        modalTitle.textContent = '{{ __("Screening Test Result") }}';
    } else if (testType === 'psychology') {
        modalTitle.textContent = '{{ __("Psychology Test Result") }}';
    }
    
    // Show loading
    modalContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> {{ __("Loading test result...") }}</div>';
    modal.style.display = 'block';
    
    // Fetch test result data
    fetch(`/admin/test-sessions/applicant/${applicantId}/${testType}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            modalContent.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = '<div class="alert alert-danger">{{ __("Error loading test result. Please try again.") }}</div>';
        });
}

function closeTestResultModal() {
    document.getElementById('testResultModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('testResultModal');
    if (event.target == modal) {
        closeTestResultModal();
    }
}

// Initialize Bootstrap dropdowns
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
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
                // Position dropdown
                const rect = button.getBoundingClientRect();
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;
                
                if (spaceBelow < 200 && spaceAbove > 200) {
                    // Show above button
                    menu.style.top = 'auto';
                    menu.style.bottom = '100%';
                    menu.style.transform = 'translate3d(0px, -5px, 0px)';
                } else {
                    // Show below button
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
