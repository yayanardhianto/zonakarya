@extends('frontend.layouts.master')
@section('title', __('Job Vacancies'))
@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')

    <div class="d-lg-none">
        <x-breadcrumb :image="$setting?->service_page_breadcrumb_image" :title="__('Karir')" />
    </div>

    <div class="job-vacancy-section py-50 mt-25 mt-lg-5">
        <div class="container pt-lg-5">
            <div class="row">
                <div class="d-flex justify-content-between w-100 mb-45 gap-3">
                    <div class="col-md-6">
                        <h2 class="h2 fw-bold mb-3">{{ html_entity_decode($setting->job_listing_title ?? __('Rise Together'), ENT_QUOTES, 'UTF-8') }}</h2>
                    </div>
                    <div class="col-md-6">
                        <p class="lead text-muted mb-0">{{ html_entity_decode($setting->job_listing_description ?? __('Mulai perjalanan Anda dengan perusahaan kami, mari bergabung bersama kami.'), ENT_QUOTES, 'UTF-8') }}</p>
                    </div>
                </div>
            </div>
            <div class="row mt-25">
                <div class="col-lg-3">
                    <!-- Filter Sidebar -->
                    <div class="filter-sidebar filter-search">
                        <div class="card rounded-0">
                            <div class="card-header bg-black">
                                <h5 class="mb-0 pt-2 pb-2 text-white d-flex justify-content-between align-items-center">
                                    <span>{{ __('Filter') }}</span>
                                    <button class="btn btn-sm btn-outline-light d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                        <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
                                    </button>
                                </h5>
                            </div>
                            <div class="collapse d-lg-block" id="filterCollapse">
                                <div class="card-body pt-4">
                                <form method="GET" action="{{ route('jobs.index') }}">
                                    <div class="form-group mb-4">
                                        <label>{{ __('Position') }}</label>
                                        <input type="text" class="form-control" name="position" 
                                               value="{{ request('position') }}" 
                                               placeholder="{{ __('Search by position') }}">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label>{{ __('Location') }}</label>
                                        <input type="text" class="form-control" name="location" 
                                               value="{{ request('location') }}" 
                                               placeholder="{{ __('Search by location') }}">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label>{{ __('Work Type') }}</label>
                                        <select class="form-control" name="work_type">
                                            <option value="">{{ __('All Work Types') }}</option>
                                            <option value="Full-Time" {{ request('work_type') == 'Full-Time' ? 'selected' : '' }}>{{ __('Full-Time') }}</option>
                                            <option value="Part-Time" {{ request('work_type') == 'Part-Time' ? 'selected' : '' }}>{{ __('Part-Time') }}</option>
                                            <option value="Contract" {{ request('work_type') == 'Contract' ? 'selected' : '' }}>{{ __('Contract') }}</option>
                                            <option value="Freelance" {{ request('work_type') == 'Freelance' ? 'selected' : '' }}>{{ __('Freelance') }}</option>
                                            <option value="Internship" {{ request('work_type') == 'Internship' ? 'selected' : '' }}>{{ __('Internship') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label>{{ __('Education') }}</label>
                                        <select class="form-control" name="education">
                                            <option value="">{{ __('All Education Levels') }}</option>
                                            <option value="SMA" {{ request('education') == 'SMA' ? 'selected' : '' }}>{{ __('SMA') }}</option>
                                            <option value="D3" {{ request('education') == 'D3' ? 'selected' : '' }}>{{ __('D3') }}</option>
                                            <option value="S1" {{ request('education') == 'S1' ? 'selected' : '' }}>{{ __('S1') }}</option>
                                            <option value="S2" {{ request('education') == 'S2' ? 'selected' : '' }}>{{ __('S2') }}</option>
                                            <option value="S3" {{ request('education') == 'S3' ? 'selected' : '' }}>{{ __('S3') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label>{{ __('Experience') }}</label>
                                        <select class="form-control" name="experience">
                                            <option value="">{{ __('All Experience Levels') }}</option>
                                            <option value="0" {{ request('experience') == '0' ? 'selected' : '' }}>{{ __('Fresh Graduate') }}</option>
                                            <option value="1" {{ request('experience') == '1' ? 'selected' : '' }}>{{ __('1+ Years') }}</option>
                                            <option value="3" {{ request('experience') == '3' ? 'selected' : '' }}>{{ __('3+ Years') }}</option>
                                            <option value="5" {{ request('experience') == '5' ? 'selected' : '' }}>{{ __('5+ Years') }}</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block me-2">{{ __('Filter') }}</button>
                                    <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-block">{{ __('Clear') }}</a>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 ps-lg-5">
                    <!-- Search Bar -->
                    <div class="sidebar__search mb-40 mt-5 mt-md-0">
                        <form method="GET" action="{{ route('jobs.search') }}" id="search-form">
                            <div class="search-container position-relative">
                                <input class="search-input" 
                                       name="q" 
                                       id="search-input"
                                       value="{{ request('q') }}" 
                                       type="text" 
                                       placeholder="Cari lowongan, lokasi, posisi..."
                                       autocomplete="off">
                                <button class="search-product-btn" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none">
                                        <path d="M19.0002 19.0002L14.6572 14.6572M14.6572 14.6572C15.4001 13.9143 15.9894 13.0324 16.3914 12.0618C16.7935 11.0911 17.0004 10.0508 17.0004 9.00021C17.0004 7.9496 16.7935 6.90929 16.3914 5.93866C15.9894 4.96803 15.4001 4.08609 14.6572 3.34321C13.9143 2.60032 13.0324 2.01103 12.0618 1.60898C11.0911 1.20693 10.0508 1 9.00021 1C7.9496 1 6.90929 1.20693 5.93866 1.60898C4.96803 2.01103 4.08609 2.60032 3.34321 3.34321C1.84288 4.84354 1 6.87842 1 9.00021C1 11.122 1.84288 13.1569 3.34321 14.6572C4.84354 16.1575 6.87842 17.0004 9.00021 17.0004C11.122 17.0004 13.1569 16.1575 14.6572 14.6572Z" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                
                                <!-- Search Suggestions Dropdown -->
                                <div id="search-suggestions" class="search-suggestions" style="display: none;">
                                    <div class="suggestions-content">
                                        <div class="suggestion-item" data-suggestion="developer">
                                            Developer
                                        </div>
                                        <div class="suggestion-item" data-suggestion="admin">
                                            Admin
                                        </div>
                                        <div class="suggestion-item" data-suggestion="marketing">
                                            Marketing
                                        </div>
                                        <div class="suggestion-item" data-suggestion="malang">
                                            <i class="fas fa-map-marker-alt"></i> Malang
                                        </div>
                                        <div class="suggestion-item" data-suggestion="surabaya">
                                            <i class="fas fa-map-marker-alt"></i> Surabaya
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                   

                    <!-- Search Results Info -->
                    @if(request('q'))
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-search me-2"></i>
                            {{ __('Search results for') }}: <strong>"{{ request('q') }}"</strong>
                            <span class="badge bg-primary ms-2">{{ $jobs->total() }} {{ __('results found') }}</span>
                            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i> {{ __('Clear Search') }}
                            </a>
                        </div>
                    @endif

                    <!-- Job Listings -->
                    <div class="job-listings">
                        @if($jobs->count() > 0)
                            <div class="row">
                                @foreach($jobs as $job)
                                    <div class="col-md-6 mb-5">
                                        <div class="job-card card h-100 p-3 rounded-0">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start mb-3">
                                                    @if($job->company_logo)
                                                        <img src="{{ asset('storage/' . $job->company_logo) }}" 
                                                             alt="{{ $job->company_name }}" 
                                                             class="company-logo mr-3" 
                                                             width="50" height="50">
                                                    @else
                                                        <div class="company-logo-placeholder mr-3 d-flex align-items-center justify-content-center bg-dark rounded" 
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-building text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                            <h5 class="job-title mb-0 me-2">
                                                                <a href="{{ route('jobs.show', $job->unique_code) }}" class="text-decoration-none">
                                                                    {{ $job->position }}
                                                                </a>
                                                            </h5>
                                                            <span class="position-relative mb-3 badge badge-sm badge-light-{{ $job->work_type == 'Full-Time' ? 'primary' : ($job->work_type == 'Part-Time' ? 'success' : ($job->work_type == 'Contract' ? 'warning' : ($job->work_type == 'Freelance' ? 'info' : 'secondary'))) }}">
                                                                {{ $job->work_type }}
                                                            </span>
                                                        </div>
                                                        <p class="job-location text-muted mb-0">
                                                            <i class="ki-outline ki-geolocation me-2"></i> {{ $job->location }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- <div class="job-meta mb-3">
                                                    <span class="badge badge-primary mr-2">{{ $job->work_type }}</span>
                                                    <span class="badge badge-info mr-2">{{ $job->education }}</span>
                                                    @if($job->experience_years > 0)
                                                        <span class="badge badge-warning">{{ $job->experience_years }} {{ __('years exp') }}</span>
                                                    @endif
                                                </div> -->

                                                <div class="job-details mb-3">
                                                    <div class="row">
                                                        @if($job->show_salary)
                                                        <div class="col-6">
                                                            <small class="text-muted">{{ __('Salary') }}:</small>
                                                            <div class="font-weight-bold">{{ $job->formatted_salary }}</div>
                                                        </div>
                                                        @endif
                                                        @if($job->show_age)
                                                        <div class="col-6">
                                                            <small class="text-muted">{{ __('Age') }}:</small>
                                                            <div class="font-weight-bold">{{ $job->formatted_age }}</div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="job-description mb-3">
                                                    <p class="text-muted small">
                                                        {{ Str::limit(strip_tags($job->description), 120) }}
                                                    </p>
                                                </div>

                                                <!-- @if($job->specific_requirements && count($job->specific_requirements) > 0)
                                                    <div class="job-requirements mb-3">
                                                        <small class="text-muted">{{ __('Requirements') }}:</small>
                                                        <div class="mt-1">
                                                            @foreach(array_slice($job->specific_requirements, 0, 3) as $requirement)
                                                                <span class="badge badge-light mr-1 mb-1">{{ $requirement }}</span>
                                                            @endforeach
                                                            @if(count($job->specific_requirements) > 3)
                                                                <span class="badge badge-light">+{{ count($job->specific_requirements) - 3 }} {{ __('more') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif -->

                                                <div class="job-footer d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="ki-outline ki-eye me-1"></i> {{ $job->views }} {{ __('views') }}
                                                    </small>
                                                    <small class="text-muted">
                                                        {{ $job->created_at->diffForHumans() }}
                                                    </small>
                                                </div>

                                                <div class="mt-4 d-flex justify-content-end">
                                                    <a href="{{ route('jobs.show', $job->unique_code) }}" 
                                                       class="btn btn-primary btn-sm btn-block">
                                                        {{ __('Lihat Detail') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $jobs->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                                <h4>{{ __('No Job Vacancies Found') }}</h4>
                                <p class="text-muted">{{ __('Try adjusting your search criteria or check back later for new opportunities.') }}</p>
                                <a href="{{ route('jobs.index') }}" class="btn btn-primary">{{ __('View All Jobs') }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
/* Mobile Filter Accordion Styles */
@media (max-width: 991.98px) {
    .filter-sidebar .card-header {
        cursor: pointer;
    }
    
    .filter-sidebar .card-header:hover {
        background-color: #333 !important;
    }
    
    .filter-sidebar .btn-outline-light {
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
    }
    
    .filter-sidebar .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .filter-sidebar .collapse {
        transition: all 0.3s ease;
    }
    
    .filter-sidebar .card-body {
        border-top: 1px solid #dee2e6;
    }
}

/* Desktop - Always show filter */
@media (min-width: 992px) {
    .filter-sidebar .collapse {
        display: block !important;
    }
}

    .job-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e9ecef;
    }
    
    .job-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .company-logo {
        object-fit: cover;
        border-radius: 8px;
    }
    
    .company-logo-placeholder {
        border-radius: 8px;
    }
    
    .job-title a {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .job-title a:hover {
        color: #3498db;
    }
    
    .filter-sidebar .card {
        position: sticky;
        top: 20px;
    }
    
    .search-bar .form-control {
        border-radius: 25px 0 0 25px;
    }
    
    .search-bar .btn {
        border-radius: 0 25px 25px 0;
    }
    
    .search-input {
        border-radius: 25px 0 0 25px;
        padding: 12px 20px;
        font-size: 16px !important;
        transition: border-color 0.3s ease;
    }
    
    .search-input:focus {
        /* border-color: #007bff; */
        outline: none;
        /* box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); */
    }
    
    .search-product-btn {
        border: 2px solid #007bff;
        background: #007bff;
        color: white;
        border-radius: 0 25px 25px 0;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }
    
    .search-product-btn:hover {
        background: #0056b3;
        border-color: #0056b3;
    }
    
    .search-results-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }
    
    .search-container {
        position: relative;
    }
    
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e9ecef;
        /* border-radius: 0 0 8px 8px; */
        /* box-shadow: 0 4px 12px rgba(0,0,0,0.1); */
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.2s ease;
        display: flex;
        align-items: center;
    }
    
    .suggestion-item:hover {
        background-color: #f8f9fa;
    }
    
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    .suggestion-item i {
        margin-right: 8px;
        color: #6c757d;
        width: 16px;
    }
    .filter-search input {
    width: 100%;
    border-bottom: 1px solid var(--title-color);
    border-radius: 0;
    background: 0 0;
    font-size: 15px;
    color: var(--body-color);
    padding: 0 60px 0 0;
    height: 45px;
    }  

    .filter-search select {
    font-size: 15px;
    height: 45px;
    }  

     .filter-search label {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 0px;
     }
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    const suggestionItems = document.querySelectorAll('.suggestion-item');
    
    if (searchInput) {
        // Auto-submit search form on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
        
        // Show/hide suggestions on focus/blur
        searchInput.addEventListener('focus', function() {
            if (this.value.length === 0) {
                searchSuggestions.style.display = 'block';
            }
        });
        
        searchInput.addEventListener('blur', function() {
            // Delay hiding to allow clicking on suggestions
            setTimeout(() => {
                searchSuggestions.style.display = 'none';
            }, 200);
        });
        
        // Filter suggestions based on input
        searchInput.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            if (value.length > 0) {
                searchSuggestions.style.display = 'block';
                filterSuggestions(value);
            } else {
                searchSuggestions.style.display = 'block';
                showAllSuggestions();
            }
        });
    }
    
    // Handle suggestion clicks
    suggestionItems.forEach(item => {
        item.addEventListener('click', function() {
            const suggestion = this.getAttribute('data-suggestion');
            searchInput.value = suggestion;
            searchSuggestions.style.display = 'none';
            searchInput.focus();
        });
    });
    
    // Clear search functionality
    const clearSearchBtn = document.querySelector('[href*="jobs.index"]');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Clear search input
            if (searchInput) {
                searchInput.value = '';
            }
            // Redirect to jobs index
            window.location.href = this.href;
        });
    }
    
    // Highlight search terms in results
    const searchTerm = '{{ request("q") }}';
    if (searchTerm) {
        highlightSearchTerms(searchTerm);
    }
});

function filterSuggestions(searchTerm) {
    const suggestionItems = document.querySelectorAll('.suggestion-item');
    suggestionItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function showAllSuggestions() {
    const suggestionItems = document.querySelectorAll('.suggestion-item');
    suggestionItems.forEach(item => {
        item.style.display = 'flex';
    });
}

function highlightSearchTerms(term) {
    const jobCards = document.querySelectorAll('.job-card');
    const searchTerm = term.toLowerCase();
    
    jobCards.forEach(card => {
        const textElements = card.querySelectorAll('.job-title, .job-location, .job-description');
        
        textElements.forEach(element => {
            const originalText = element.innerHTML;
            const regex = new RegExp(`(${searchTerm})`, 'gi');
            const highlightedText = originalText.replace(regex, '<mark class="bg-warning">$1</mark>');
            element.innerHTML = highlightedText;
        });
    });
}

// Filter accordion functionality for mobile
document.addEventListener('DOMContentLoaded', function() {
    const filterCollapse = document.getElementById('filterCollapse');
    const filterToggleIcon = document.getElementById('filterToggleIcon');
    
    if (filterCollapse && filterToggleIcon) {
        filterCollapse.addEventListener('show.bs.collapse', function () {
            filterToggleIcon.classList.remove('fa-chevron-down');
            filterToggleIcon.classList.add('fa-chevron-up');
        });
        
        filterCollapse.addEventListener('hide.bs.collapse', function () {
            filterToggleIcon.classList.remove('fa-chevron-up');
            filterToggleIcon.classList.add('fa-chevron-down');
        });
    }
});
</script>
@endpush
