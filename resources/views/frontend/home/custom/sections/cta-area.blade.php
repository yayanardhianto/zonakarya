<!-- Custom Call to Action Section -->
<div class="custom-cta-section" id="cta">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cta-content text-center">
                    <h2 class="cta-title">Ready to Get Started?</h2>
                    <p class="cta-description">Let's work together to bring your vision to life</p>
                    <div class="cta-buttons">
                        <a href="#contact" class="btn btn-primary btn-lg">Contact Us</a>
                        <a href="#about" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-cta-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-buttons .btn {
    padding: 15px 40px;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 1rem;
}

.btn-outline-light {
    border: 2px solid white;
    color: white;
}

.btn-outline-light:hover {
    background: white;
    color: #667eea;
}

@media (max-width: 768px) {
    .cta-title {
        font-size: 2rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-buttons .btn {
        width: 200px;
    }
}
</style>
@endpush
