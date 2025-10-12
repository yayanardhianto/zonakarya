<!-- Custom About Section -->
<div class="custom-about-section" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2 class="section-title">{{ $aboutSection?->content?->title ?? 'About Us' }}</h2>
                    <p class="section-description">{!! clean(processText($aboutSection?->content?->sub_title ?? 'Your about content goes here')) !!}</p>
                    <div class="about-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Professional Service</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>24/7 Support</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Quality Guaranteed</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image">
                    @if($aboutSection?->global_content?->image)
                        <img src="{{ asset($aboutSection?->global_content?->image) }}" alt="About Image" class="img-fluid">
                    @else
                        <img src="{{ asset('frontend/img/placeholder-about.jpg') }}" alt="About Image" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-about-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: #333;
}

.section-description {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.about-features {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    color: #333;
}

.feature-item i {
    color: #28a745;
    font-size: 1.2rem;
}

.about-image img {
    border-radius: 15px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}
</style>
@endpush
