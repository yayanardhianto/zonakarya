<!-- Custom Hero Section -->
<div class="custom-hero-section" id="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">{{ $hero?->content?->title ?? 'Welcome to Our Website' }}</h1>
                    <p class="hero-description">{!! clean(processText($hero?->content?->sub_title ?? 'Your custom content goes here')) !!}</p>
                    <div class="hero-buttons">
                        <a href="#contact" class="btn btn-primary">Get Started</a>
                        <a href="#about" class="btn btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    @if($hero?->global_content?->image)
                        <img src="{{ asset($hero?->global_content?->image) }}" alt="Hero Image" class="img-fluid">
                    @else
                        <img src="{{ asset('frontend/img/placeholder-hero.jpg') }}" alt="Hero Image" class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS untuk template Anda -->
@push('styles')
<style>
.custom-hero-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.hero-buttons .btn {
    padding: 12px 30px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-image img {
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-buttons {
        justify-content: center;
    }
}
</style>
@endpush
