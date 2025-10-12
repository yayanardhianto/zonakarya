<!-- Custom Testimonial Section -->
<div class="custom-testimonial-section" id="testimonials">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center">
                    <h2 class="section-title">What Our Clients Say</h2>
                    <p class="section-description">Read testimonials from our satisfied customers</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="quote-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <p class="testimonial-text">{{ $testimonial->translation?->comment ?? 'Great service and professional team!' }}</p>
                        </div>
                        <div class="testimonial-author">
                            <h5 class="author-name">{{ $testimonial->translation?->name ?? 'Client Name' }}</h5>
                            <p class="author-designation">{{ $testimonial->translation?->designation ?? 'Client' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No testimonials available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-testimonial-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.testimonial-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    height: 100%;
}

.quote-icon {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 20px;
}

.testimonial-text {
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 25px;
    font-style: italic;
}

.author-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.author-designation {
    font-size: 0.9rem;
    opacity: 0.8;
}
</style>
@endpush
