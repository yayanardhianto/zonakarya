@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Talent') }} - {{ $talent->name }}</title>
@endsection

@section('admin-content')
<div class="main-content">
    <section class="section">
        <x-admin.breadcrumb title="{{ __('Edit Talent') }}" :list="[
            __('Dashboard') => route('admin.dashboard'),
            __('Talents') => route('admin.talents.index'),
            $talent->name => route('admin.talents.show', $talent),
            __('Edit') => '#',
        ]" />

        <div class="section-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Edit Talent Information') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.talents.update', $talent) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('Name') }}</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="name" 
                                                   value="{{ $talent->name }}" 
                                                   disabled>
                                            <small class="form-text text-muted">{{ __('Name cannot be changed') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">{{ __('City') }}</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="city" 
                                                   value="{{ $talent->city }}" 
                                                   disabled>
                                            <small class="form-text text-muted">{{ __('City cannot be changed') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Basic Information -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="level_potential">{{ __('Level Potential') }}</label>
                                            <select class="form-control @error('level_potential') is-invalid @enderror" 
                                                    id="level_potential" 
                                                    name="level_potential">
                                                <option value="">{{ __('Select level potential') }}</option>
                                                <option value="Junior Staff" {{ old('level_potential', $talent->level_potential) == 'Junior Staff' ? 'selected' : '' }}>Junior Staff</option>
                                                <option value="Senior Staff" {{ old('level_potential', $talent->level_potential) == 'Senior Staff' ? 'selected' : '' }}>Senior Staff</option>
                                                <option value="Koordinator" {{ old('level_potential', $talent->level_potential) == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
                                                <option value="Supervisor" {{ old('level_potential', $talent->level_potential) == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                                <option value="Manager" {{ old('level_potential', $talent->level_potential) == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            </select>
                                            @error('level_potential')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="talent_potential">{{ __('Talent Potential') }}</label>
                                            <select class="form-control @error('talent_potential') is-invalid @enderror" 
                                                    id="talent_potential" 
                                                    name="talent_potential">
                                                <option value="">{{ __('Select talent potential') }}</option>
                                                <option value="Frontline" {{ old('talent_potential', $talent->talent_potential) == 'Frontline' ? 'selected' : '' }}>Frontline</option>
                                                <option value="Backline" {{ old('talent_potential', $talent->talent_potential) == 'Backline' ? 'selected' : '' }}>Backline</option>
                                                <option value="Officer" {{ old('talent_potential', $talent->talent_potential) == 'Officer' ? 'selected' : '' }}>Officer</option>
                                            </select>
                                            @error('talent_potential')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="position_potential">{{ __('Position Potential') }}</label>
                                    <input type="text" 
                                           class="form-control @error('position_potential') is-invalid @enderror" 
                                           id="position_potential" 
                                           name="position_potential" 
                                           value="{{ old('position_potential', $talent->potential_position) }}" 
                                           placeholder="e.g., Kasir, Packer, SPG, Picker">
                                    @error('position_potential')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <!-- Assessment Scores -->
                                <h5>{{ __('Assessment Scores') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="communication">{{ __('Communication') }}</label>
                                            <select class="form-control @error('communication') is-invalid @enderror" 
                                                    id="communication" 
                                                    name="communication">
                                                <option value="">{{ __('Select score') }}</option>
                                                <option value="1" {{ old('communication', $talent->communication) == 1 ? 'selected' : '' }}>1 - Poor</option>
                                                <option value="2" {{ old('communication', $talent->communication) == 2 ? 'selected' : '' }}>2 - Below Average</option>
                                                <option value="3" {{ old('communication', $talent->communication) == 3 ? 'selected' : '' }}>3 - Average</option>
                                                <option value="4" {{ old('communication', $talent->communication) == 4 ? 'selected' : '' }}>4 - Good</option>
                                                <option value="5" {{ old('communication', $talent->communication) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                            </select>
                                            @error('communication')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="attitude">{{ __('Attitude') }}</label>
                                            <select class="form-control @error('attitude') is-invalid @enderror" 
                                                    id="attitude" 
                                                    name="attitude">
                                                <option value="">{{ __('Select score') }}</option>
                                                <option value="1" {{ old('attitude', $talent->attitude_level) == 1 ? 'selected' : '' }}>1 - Poor</option>
                                                <option value="2" {{ old('attitude', $talent->attitude_level) == 2 ? 'selected' : '' }}>2 - Below Average</option>
                                                <option value="3" {{ old('attitude', $talent->attitude_level) == 3 ? 'selected' : '' }}>3 - Average</option>
                                                <option value="4" {{ old('attitude', $talent->attitude_level) == 4 ? 'selected' : '' }}>4 - Good</option>
                                                <option value="5" {{ old('attitude', $talent->attitude_level) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                            </select>
                                            @error('attitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="initiative">{{ __('Initiative') }}</label>
                                            <select class="form-control @error('initiative') is-invalid @enderror" 
                                                    id="initiative" 
                                                    name="initiative">
                                                <option value="">{{ __('Select score') }}</option>
                                                <option value="1" {{ old('initiative', $talent->initiative) == 1 ? 'selected' : '' }}>1 - Poor</option>
                                                <option value="2" {{ old('initiative', $talent->initiative) == 2 ? 'selected' : '' }}>2 - Below Average</option>
                                                <option value="3" {{ old('initiative', $talent->initiative) == 3 ? 'selected' : '' }}>3 - Average</option>
                                                <option value="4" {{ old('initiative', $talent->initiative) == 4 ? 'selected' : '' }}>4 - Good</option>
                                                <option value="5" {{ old('initiative', $talent->initiative) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                            </select>
                                            @error('initiative')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="leadership">{{ __('Leadership') }}</label>
                                            <select class="form-control @error('leadership') is-invalid @enderror" 
                                                    id="leadership" 
                                                    name="leadership">
                                                <option value="">{{ __('Select score') }}</option>
                                                <option value="1" {{ old('leadership', $talent->leadership) == 1 ? 'selected' : '' }}>1 - Poor</option>
                                                <option value="2" {{ old('leadership', $talent->leadership) == 2 ? 'selected' : '' }}>2 - Below Average</option>
                                                <option value="3" {{ old('leadership', $talent->leadership) == 3 ? 'selected' : '' }}>3 - Average</option>
                                                <option value="4" {{ old('leadership', $talent->leadership) == 4 ? 'selected' : '' }}>4 - Good</option>
                                                <option value="5" {{ old('leadership', $talent->leadership) == 5 ? 'selected' : '' }}>5 - Excellent</option>
                                            </select>
                                            @error('leadership')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="notes">{{ __('Notes') }}</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="4" 
                                              placeholder="{{ __('Enter additional notes about this talent...') }}">{{ old('notes', $talent->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('Update Talent') }}
                                    </button>
                                    <a href="{{ route('admin.talents.show', $talent) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> {{ __('Cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Talent Info') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                @if($talent->applicant && $talent->applicant->photo_path)
                                    <img src="{{ asset('uploads/store/' . $talent->applicant->photo_path) }}" 
                                         alt="{{ $talent->name }}" 
                                         class="rounded-circle mb-3" 
                                         width="100" height="100"
                                         style="object-fit: cover;">
                                @elseif($talent->applicant && $talent->applicant->user && $talent->applicant->user->avatar)
                                    <img src="{{ $talent->applicant->user->avatar }}" 
                                         alt="{{ $talent->name }}" 
                                         class="rounded-circle mb-3" 
                                         width="100" height="100"
                                         style="object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                                        {{ substr($talent->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <h5>{{ $talent->name }}</h5>
                                <p class="text-muted">{{ $talent->city }}</p>
                            </div>

                            <hr>

                            <div class="talent-summary">
                                <h6>{{ __('Current Assessment') }}</h6>
                                <p><strong>{{ __('Attitude Level') }}:</strong> 
                                    <span class="badge badge-{{ $talent->attitude_level >= 4 ? 'success' : ($talent->attitude_level >= 3 ? 'warning' : 'danger') }}">
                                        {{ $talent->attitude_level }}/5
                                    </span>
                                </p>
                                <p><strong>{{ __('Potential Level') }}:</strong> 
                                    @if($talent->potential_level)
                                        <span class="badge badge-{{ $talent->potential_level >= 4 ? 'success' : ($talent->potential_level >= 3 ? 'warning' : 'danger') }}">
                                            {{ $talent->potential_level }}/5
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                    @endif
                                </p>
                                <p><strong>{{ __('Potential Position') }}:</strong> 
                                    @if($talent->potential_position)
                                        <span class="badge badge-info">{{ $talent->potential_position }}</span>
                                    @else
                                        <span class="text-muted">{{ __('Not Set') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('css')
<style>
.talent-summary p {
    margin-bottom: 0.5rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
