@extends('admin.master_layout')
@section('title')
    <title>{{ __('Talents Management') }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Talents Management') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Talents') => '#',
        ]" />

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Talents Database') }}</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('admin.talents.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="search">{{ __('Search by Name') }}</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="search" 
                                                   name="search" 
                                                   value="{{ request('search') }}" 
                                                   placeholder="{{ __('Enter talent name...') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="city">{{ __('Filter by City') }}</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="city" 
                                                   name="city" 
                                                   value="{{ request('city') }}" 
                                                   placeholder="{{ __('Enter city...') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="potential_position">{{ __('Filter by Potential Position') }}</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="potential_position" 
                                                   name="potential_position" 
                                                   value="{{ request('potential_position') }}" 
                                                   placeholder="{{ __('Enter position...') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-block">
                                                    <i class="fas fa-search"></i> {{ __('Filter') }}
                                                </button>
                                                <a href="{{ route('admin.talents.index') }}" class="btn btn-dark">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- Results Count -->
                            <!-- <div class="mb-3">
                                <p class="text-muted">
                                    {{ __('Showing') }} {{ $talents->firstItem() ?? 0 }} {{ __('to') }} {{ $talents->lastItem() ?? 0 }} 
                                    {{ __('of') }} {{ $talents->total() }} {{ __('results') }}
                                </p>
                            </div> -->

                            <!-- Talents Table -->
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('City') }}</th>
                                            <th>{{ __('Level Potential') }}</th>
                                            <th>{{ __('Talent Potential') }}</th>
                                            <th>{{ __('Position Potential') }}</th>
                                            <th>{{ __('Communication') }}</th>
                                            <th>{{ __('Attitude') }}</th>
                                            <th>{{ __('Initiative') }}</th>
                                            <th>{{ __('Leadership') }}</th>
                                            <th>{{ __('Original Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($talents as $talent)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($talent->user && $talent->user->avatar)
                                                            <img src="{{ $talent->user->avatar }}" 
                                                                 alt="{{ $talent->name }}" 
                                                                 class="rounded-circle me-2" 
                                                                 width="40" height="40"
                                                                 style="object-fit: cover;">
                                                        @elseif($talent->applicant && $talent->applicant->photo_path)
                                                            <img src="{{ asset('uploads/store/' . $talent->applicant->photo_path) }}" 
                                                                 alt="{{ $talent->name }}" 
                                                                 class="rounded-circle me-2" 
                                                                 width="40" height="40"
                                                                 style="object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 40px; height: 40px;">
                                                                {{ substr($talent->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong>{{ $talent->name }}</strong>
                                                            @if($talent->user)
                                                                <br><small class="text-muted">{{ $talent->user->email }}</small>
                                                            @elseif($talent->applicant)
                                                                <br><small class="text-muted">{{ $talent->applicant->email }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $talent->city }}</td>
                                                <td>
                                                    @if($talent->level_potential)
                                                        <span class="badge badge-primary">{{ $talent->level_potential }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->talent_potential)
                                                        <span class="badge badge-info">{{ $talent->talent_potential }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->potential_position)
                                                        <span class="badge badge-secondary">{{ $talent->potential_position }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->communication)
                                                        <span class="badge badge-{{ $talent->communication >= 4 ? 'success' : ($talent->communication >= 3 ? 'warning' : 'danger') }}">
                                                            {{ $talent->communication }}/5
                                                        </span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->attitude_level)
                                                        <span class="badge badge-{{ $talent->attitude_level >= 4 ? 'success' : ($talent->attitude_level >= 3 ? 'warning' : 'danger') }}">
                                                            {{ $talent->attitude_level }}/5
                                                        </span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->initiative)
                                                        <span class="badge badge-{{ $talent->initiative >= 4 ? 'success' : ($talent->initiative >= 3 ? 'warning' : 'danger') }}">
                                                            {{ $talent->initiative }}/5
                                                        </span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->leadership)
                                                        <span class="badge badge-{{ $talent->leadership >= 4 ? 'success' : ($talent->leadership >= 3 ? 'warning' : 'danger') }}">
                                                            {{ $talent->leadership }}/5
                                                        </span>
                                                    @else
                                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($talent->applications && $talent->applications->count() > 0)
                                                        @php
                                                            $latestApplication = $talent->applications->first();
                                                        @endphp
                                                        <span class="badge badge-{{ $latestApplication->status_badge }}">
                                                            {{ $latestApplication->status_text }}
                                                        </span>
                                                        <br><small class="text-muted">{{ $latestApplication->jobVacancy->position ?? 'N/A' }}</small>
                                                    @else
                                                        <span class="text-muted">{{ __('N/A') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.talents.show', $talent) }}" 
                                                           class="btn btn-sm btn-info" 
                                                           title="{{ __('View Details') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.talents.edit', $talent) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="{{ __('Edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @php
                                                            $latestApplication = $talent->applications->first();
                                                        @endphp
                                                        @if($latestApplication && $latestApplication->status != 'onboard')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success" 
                                                                title="{{ __('Reapply') }}"
                                                                data-toggle="modal" 
                                                                data-target="#reapplyModal{{ $talent->id }}"
                                                                data-talent-id="{{ $talent->id }}"
                                                                data-talent-name="{{ $talent->name }}">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                        @endif
                                                        <form action="{{ route('admin.talents.destroy', $talent) }}" 
                                                              method="POST" 
                                                              style="display: inline-block;"
                                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this talent?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    title="{{ __('Delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">{{ __('No talents found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $talents->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Reapply Modal -->
@foreach($talents as $talent)
<div class="modal fade" id="reapplyModal{{ $talent->id }}" tabindex="-1" role="dialog" aria-labelledby="reapplyModalLabel{{ $talent->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reapplyModalLabel{{ $talent->id }}">{{ __('Reapply for') }} {{ $talent->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.talents.reapply', $talent) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="job_vacancy_id{{ $talent->id }}">{{ __('Select Job Position') }}</label>
                        <select class="form-control" id="job_vacancy_id{{ $talent->id }}" name="job_vacancy_id" required>
                            <option value="">{{ __('Choose a position...') }}</option>
                            @foreach(\App\Models\JobVacancy::available()->get() as $job)
                                <option value="{{ $job->id }}">{{ $job->position }} - {{ $job->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status{{ $talent->id }}">{{ __('Select Status') }}</label>
                        <select class="form-control" id="status{{ $talent->id }}" name="status" required>
                            <option value="">{{ __('Choose a status...') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="sent">{{ __('Test Screening') }}</option>
                            <option value="check">{{ __('Check') }}</option>
                            <option value="short_call">{{ __('Short Call') }}</option>
                            <option value="group_interview">{{ __('Group Interview') }}</option>
                            <option value="test_psychology">{{ __('Test Psychology') }}</option>
                            <option value="ojt">{{ __('OJT') }}</option>
                            <option value="final_interview">{{ __('Final Interview') }}</option>
                            <option value="sent_offering_letter">{{ __('Sent Offering Letter') }}</option>
                            <option value="onboard">{{ __('Onboard') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes{{ $talent->id }}">{{ __('Notes') }} ({{ __('Optional') }})</label>
                        <textarea class="form-control" id="notes{{ $talent->id }}" name="notes" rows="3" placeholder="{{ __('Add any notes about this reapplication...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('Create Reapplication') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
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

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.table td {
    vertical-align: middle;
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    // Handle reapply modal
    $('[data-toggle="modal"]').on('click', function() {
        const targetModal = $(this).data('target');
        $(targetModal).modal('show');
    });
    
    // Handle modal close
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush
