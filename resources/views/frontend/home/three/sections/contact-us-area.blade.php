<div class="contact-area-1 space bg-theme">
    <div class="contact-map shape-mockup wow img-custom-anim-left" data-wow-duration="1.5s" data-wow-delay="0.2s"
        data-left="0" data-top="-100px" data-bottom="140px">
        <div id="map-container" style="position: relative; width: 100%; height: 100%;">
            <iframe id="branch-map" 
                    src="https://www.google.com/maps/d/embed?mid=1stBv3YSDUtYZQ6J6DuaCTcuqwMqgRw0&ehbc=2E312F" 
                    allowfullscreen="" 
                    loading="lazy"
                    style="width: 100%; height: 100%; border: 0; border-radius: 0px;"></iframe>
            <div id="map-overlay" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); color: white; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; border-radius: 10px;">
                <h4 style="margin-bottom: 15px;">Map Preview Not Available</h4>
                <p style="margin-bottom: 20px;">This location cannot be previewed in the map.</p>
                <a id="open-map-link" href="#" target="_blank" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Open in Google Maps</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-end">
            <div class="col-lg-6">
                <div class="branch-list-wrap">
                    <div class="title-area mb-30">
                        <h2 class="sec-title">{{ $branches->first()?->section_title ?? __('Our Store Locations') }}</h2>
                        <p>{{ $branches->first()?->section_description ?? __('Select a store to view its location and details') }}</p>
                    </div>
                    
                    @if($branches->count() > 0)
                        <div class="branch-list row">
                            @foreach($branches as $branch)
                                <div class="branch-item col-md-6" 
                                     data-branch-id="{{ $branch->id }}"
                                     data-branch-name="{{ $branch->name }}"
                                     data-branch-address="{{ $branch->address }}"
                                     data-branch-city="{{ $branch->city }}"
                                     data-branch-province="{{ $branch->province }}"
                                     data-branch-map="{{ $branch->map }}"
                                     data-branch-description="{{ $branch->description }}"
                                     data-service-name="{{ $branch->service->translation?->title ?? 'N/A' }}">
                                    <div class="branch-header">
                                        <h4 class="branch-name mb-1">{{ $branch->name }}</h4>
                                        <!-- <span class="branch-service">{{ $branch->service->translation?->title ?? 'N/A' }}</span> -->
                                    </div>
                                    <div class="branch-details">
                                        <p class="branch-address mb-1">
                                            <i class="ki-solid ki-geolocation me-1"></i>
                                            {{ $branch->address }}
                                        </p>
                                        <p class="branch-location">
                                            <i class="ki-solid ki-shop me-1"></i>
                                            {{ $branch->city }}, {{ $branch->province }}
                                        </p>
                                        <!-- @if($branch->description)
                                            <p class="branch-description">{{ Str::limit($branch->description, 100) }}</p>
                                        @endif -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-branches">
                            <p>{{ __('No branches available at the moment.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.branch-list-wrap {
    background: rgba(255, 255, 255, 0.1);
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.branch-list {
    max-height: 400px;
    overflow-y: auto;
}

.branch-item {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid transparent;
    border-radius: 0px;
    padding: 20px;
    margin-bottom: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.branch-item:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.branch-item.active {
    background: rgba(255, 255, 255, 0.2);
    border-color: #fff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.branch-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.branch-name {
    color: #fff;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}

.branch-service {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.branch-details p {
    color: rgba(255, 255, 255, 0.9);
    margin: 8px 0;
    font-size: 0.9rem;
}

.branch-details i {
    margin-right: 8px;
    width: 16px;
    color: rgba(255, 255, 255, 0.7);
}

.branch-description {
    font-style: italic;
    color: rgba(255, 255, 255, 0.8) !important;
}

.no-branches {
    text-align: center;
    padding: 40px 20px;
    color: rgba(255, 255, 255, 0.8);
}

/* Custom scrollbar for branch list */
.branch-list::-webkit-scrollbar {
    width: 6px;
}

.branch-list::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.branch-list::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.branch-list::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
.i4ewOd-pzNkMb-haAclf {
    display: none;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const branchItems = document.querySelectorAll('.branch-item');
    const branchMap = document.getElementById('branch-map');
    
    // Function to update map based on branch data
    function updateMap(branchItem) {
        const branchMapUrl = branchItem.getAttribute('data-branch-map');
        const branchName = branchItem.getAttribute('data-branch-name');
        const branchAddress = branchItem.getAttribute('data-branch-address');
        
        // Show loading effect
        branchMap.style.opacity = '0.5';
        
        // Update iframe source
        if (branchMapUrl && branchMapUrl.trim() !== '') {
            let finalMapUrl = branchMapUrl;
            
            // Check if it's a Google Maps embed or just a link
            if (branchMapUrl.includes('<iframe') || branchMapUrl.includes('embed')) {
                // It's already an embed code - extract src
                const srcMatch = branchMapUrl.match(/src="([^"]*)"/);
                if (srcMatch) {
                    finalMapUrl = srcMatch[1];
                }
            } else if (branchMapUrl.includes('maps.app.goo.gl') || branchMapUrl.includes('goo.gl/maps')) {
                // Google Maps short URLs cannot be embedded due to X-Frame-Options
                // Show a message or use fallback map
                console.warn('Google Maps short URL cannot be embedded:', branchMapUrl);
                finalMapUrl = 'https://www.google.com/maps/d/embed?mid=1stBv3YSDUtYZQ6J6DuaCTcuqwMqgRw0&ehbc=2E312F';
            } else if (branchMapUrl.includes('maps.google.com')) {
                // It's a Google Maps link, convert to embed
                if (branchMapUrl.includes('/maps?')) {
                    finalMapUrl = branchMapUrl.replace('/maps?', '/maps/embed?');
                } else if (!branchMapUrl.includes('/embed')) {
                    finalMapUrl = branchMapUrl + (branchMapUrl.includes('?') ? '&' : '?') + 'output=embed';
                }
            } else if (branchMapUrl.includes('google.com/maps') || branchMapUrl.includes('maps.google.com')) {
                // Handle other Google Maps URL formats
                finalMapUrl = branchMapUrl + (branchMapUrl.includes('?') ? '&' : '?') + 'output=embed';
            }
            
            // Set the map source
            branchMap.src = finalMapUrl;
            
            console.log('Map updated for branch:', branchName, 'URL:', finalMapUrl);
        } else {
            // Fallback to default map
            branchMap.src = 'https://www.google.com/maps/d/embed?mid=1stBv3YSDUtYZQ6J6DuaCTcuqwMqgRw0&ehbc=2E312F';
            console.log('Using fallback map for branch:', branchName);
        }
        
        // Hide loading effect after a delay
        setTimeout(function() {
            branchMap.style.opacity = '1';
        }, 500);
    }
    
    // Set default branch on page load
    function setDefaultBranch() {
        if (branchItems.length > 0) {
            // Remove active class from all items first
            branchItems.forEach(function(branchItem) {
                branchItem.classList.remove('active');
            });
            
            // Don't set any branch as active by default
            // Let the default map show first
            console.log('Branches available, but no default branch selected. Using default map.');
        } else {
            console.log('No branches found, using fallback map');
            // If no branches, ensure we have a fallback map
            if (branchMap && !branchMap.src.includes('maps.google.com')) {
                branchMap.src = 'https://www.google.com/maps/d/embed?mid=1stBv3YSDUtYZQ6J6DuaCTcuqwMqgRw0&ehbc=2E312F';
            }
        }
    }
    
    // Initialize default branch when DOM is ready
    setDefaultBranch();
    
    // Also set default branch after a short delay to ensure everything is loaded
    setTimeout(function() {
        setDefaultBranch();
    }, 100);
    
    // Add click event listeners to all branch items
    branchItems.forEach(function(item) {
        item.addEventListener('click', function() {
            // Remove active class from all items
            branchItems.forEach(function(branchItem) {
                branchItem.classList.remove('active');
            });
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Update map with clicked branch data
            updateMap(this);
            
            console.log('Branch selected:', this.getAttribute('data-branch-name'));
        });
    });
});
</script>
@endpush