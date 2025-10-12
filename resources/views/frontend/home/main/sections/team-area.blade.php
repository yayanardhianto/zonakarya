<div class="team-area-1 space overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="title-area text-center">
                    <h2 class="sec-title">{{ __('Our Team Behind The Studio') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center">
            @foreach ($teams as $team)
                <div class="col-lg-3 col-md-6">
                    <div class="team-card">
                        <div class="team-card_img">
                            <img src="{{ asset($team?->image) }}" alt="{{ $team?->name }}">
                        </div>
                        <div class="team-card_content">
                            <h3 class="team-card_title"><a
                                    href="{{ route('single.team', $team?->slug) }}">{{ $team?->name }}</a></h3>
                            <span class="team-card_desig">{{ $team?->designation }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
