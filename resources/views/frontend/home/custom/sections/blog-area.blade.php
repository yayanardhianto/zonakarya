<!-- Custom Blog Section -->
<div class="custom-blog-section" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center">
                    <h2 class="section-title">Latest Blog Posts</h2>
                    <p class="section-description">Stay updated with our latest news and insights</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($latest_blogs as $blog)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="blog-card">
                        <div class="blog-image">
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->translation?->title }}" class="img-fluid">
                            <div class="blog-date">
                                <span class="day">{{ $blog->created_at->format('d') }}</span>
                                <span class="month">{{ $blog->created_at->format('M') }}</span>
                            </div>
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-category">{{ $blog->category?->translation?->title ?? 'Uncategorized' }}</span>
                            </div>
                            <h4 class="blog-title">
                                <a href="{{ route('single.blog', $blog->slug) }}">{{ $blog->translation?->title ?? 'Blog Title' }}</a>
                            </h4>
                            <p class="blog-excerpt">{{ Str::limit(strip_tags($blog->translation?->description ?? 'Blog content goes here'), 100) }}</p>
                            <a href="{{ route('single.blog', $blog->slug) }}" class="blog-link">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No blog posts available</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-blog-section {
    padding: 80px 0;
    background-color: white;
}

.blog-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-5px);
}

.blog-image {
    position: relative;
    overflow: hidden;
}

.blog-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .blog-image img {
    transform: scale(1.1);
}

.blog-date {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #667eea;
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    text-align: center;
    line-height: 1;
}

.blog-date .day {
    display: block;
    font-size: 1.2rem;
    font-weight: 700;
}

.blog-date .month {
    display: block;
    font-size: 0.8rem;
    text-transform: uppercase;
}

.blog-content {
    padding: 25px;
}

.blog-meta {
    margin-bottom: 10px;
}

.blog-category {
    background: #f8f9fa;
    color: #667eea;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.blog-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.4;
}

.blog-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-title a:hover {
    color: #667eea;
}

.blog-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 15px;
}

.blog-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
}

.blog-link:hover {
    color: #764ba2;
}
</style>
@endpush
