<!-- Custom Service Section -->
<div class="custom-service-section" id="services">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-description">We provide the best services for your business needs</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($services as $service)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="{{ $service->icon ?? 'fas fa-cog' }}"></i>
                        </div>
                        <h4 class="service-title">{{ $service->translation?->title ?? 'Service Title' }}</h4>
                        <p class="service-description">{{ $service->translation?->short_description ?? 'Service description goes here' }}</p>
                        <a href="{{ route('single.service', $service->slug) }}" class="service-link">Learn More</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No services available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-service-section {
    padding: 80px 0;
    background-color: white;
}

.section-header {
    margin-bottom: 60px;
}

.service-card {
    text-align: center;
    padding: 30px 20px;
    border-radius: 15px;
    background: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-10px);
}

.service-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-icon i {
    font-size: 2rem;
    color: white;
}

.service-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.service-description {
    color: #666;
    margin-bottom: 20px;
    line-height: 1.6;
}

.service-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.service-link:hover {
    color: #764ba2;
}
</style>
@endpush
