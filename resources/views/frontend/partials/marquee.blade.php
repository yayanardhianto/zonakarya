@if (marquees()->count())
<div class="container-fluid p-0 overflow-hidden">
    <div class="slider__marquee clearfix marquee-wrap">
        <div class="marquee_mode marquee__group">
            @foreach (marquees() as $marquee)
                <h6 class="item m-item"><span><i class="fas fa-star-of-life"></i> {{$marquee?->title}}</span></h6>
            @endforeach
        </div>
    </div>
</div>
@endif