@forelse ($blogs as $blog)
    <div class="col-md-12">
        <div class="blog-post-item">
            <div class="blog-post-thumb">
                <a href="{{ route('single.blog', $blog?->slug) }}"><img src="{{ asset($blog?->image) }}"
                        alt="{{ $blog?->title }}"></a>
            </div>
            <div class="blog-post-content">
                <div class="blog-post-meta">
                    <ul class="list-wrap">
                        <li>{{ formattedDate($blog?->created_at) }}</li>
                        <li>
                            <a
                                href="{{ route('blogs', ['category' => $blog?->category?->slug]) }}">{{ $blog?->category?->title }}</a>
                        </li>
                    </ul>
                </div>
                <h2 class="title"><a href="{{ route('single.blog', $blog?->slug) }}">{{ $blog?->title }}</a>
                </h2>
                <a href="{{ route('single.blog', $blog?->slug) }}" class="link-btn">
                    <span class="link-effect">
                        <span class="effect-1">{{ __('Read More') }}</span>
                        <span class="effect-1">{{ __('Read More') }}</span>
                    </span>
                    <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="{{ $blog?->title }}">
                </a>
            </div>
        </div>
    </div>
@empty
    <x-data-not-found />
@endforelse
