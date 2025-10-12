<li>
    <div class="comments-box">
        <div class="comments-avatar">
            <img src="{{ asset($comment['image'] ?? $setting?->default_avatar) }}" alt="{{ $comment['name'] }}">
        </div>
        <div class="comments-text">
            <div class="avatar-name">
                <span class="date">{{ formattedDateTime($comment['created_at']) }}</span>
                <h6 class="name position-relative d-inline-block pe-1">{{ $comment['name'] }} @if($comment['is_admin']) <small class="badge badge-secondary wsus-badge">{{ __('Admin') }}</small>@endif</h6>
            </div>
            <p>{{ $comment['comment'] }}</p>
            @auth('web')
                <a href="javascript:;" class="link-btn replay-item-{{ $comment['id'] }} blog-comment-reply-toggle" data-selector="replay-item-{{ $comment['id'] }}">
                    <span class="link-effect">
                        <span class="effect-1">{{ __('Reply') }}</span>
                        <span class="effect-1">{{ __('Reply') }}</span>
                    </span>
                    <img src="{{ asset('frontend/images/arrow-left-top.svg') }}" alt="icon">
                </a>
                <div class="comment-respond d-none replay-item-{{ $comment['id'] }}">
                    <form action="{{ route('blog.comment.store', ['slug' => $slug, 'parent_id' => $comment['id']]) }}"
                        method="post" class="comment-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <textarea name="comment" placeholder="{{ __('Write your comment') }}*" class="form-control style-border style2"></textarea>
                                </div>
                            </div>
                            @if ($setting->recaptcha_status == 'active')
                                <div class="col-lg-12">
                                    <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                </div>
                            @endif
                        </div>
                        <div class="form-btn col-12">
                            <button type="submit" class="btn py-2 px-3">
                                <span class="link-effect">
                                    <span class="effect-1">{{ __('Replay') }}</span>
                                    <span class="effect-1">{{ __('Replay') }}</span>
                                </span>
                            </button>
                            <button type="button" class="btn style2 py-2 px-3 blog-comment-reply-toggle" data-selector="replay-item-{{ $comment['id'] }}">
                                <span class="link-effect">
                                    <span class="effect-1">{{ __('Close') }}</span>
                                    <span class="effect-1">{{ __('Close') }}</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>
    @if (!empty($comment['children']) && count($comment['children']) > 0)
        <ul class="children">
            @foreach ($comment['children'] as $child)
                <x-blog-comment :comment="$child" :slug="$slug" />
            @endforeach
        </ul>
    @endif
</li>
