<!-- Custom Project Section -->
<div class="custom-project-section" id="projects">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center">
                    <h2 class="section-title">Our Projects</h2>
                    <p class="section-description">Check out our latest work and achievements</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($projects as $project)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="project-card">
                        <div class="project-image">
                            <img src="{{ asset($project->image) }}" alt="{{ $project->translation?->title }}" class="img-fluid">
                            <div class="project-overlay">
                                <a href="{{ route('single.portfolio', $project->slug) }}" class="project-link">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="project-content">
                            <h4 class="project-title">{{ $project->translation?->title ?? 'Project Title' }}</h4>
                            <p class="project-category">{{ $project->service?->translation?->title ?? 'Category' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No projects available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-project-section {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.project-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.project-card:hover {
    transform: translateY(-5px);
}

.project-image {
    position: relative;
    overflow: hidden;
}

.project-image img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.project-card:hover .project-image img {
    transform: scale(1.1);
}

.project-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(102, 126, 234, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-card:hover .project-overlay {
    opacity: 1;
}

.project-link {
    color: white;
    font-size: 2rem;
    text-decoration: none;
}

.project-content {
    padding: 20px;
}

.project-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.project-category {
    color: #666;
    font-size: 0.9rem;
}
</style>
@endpush
