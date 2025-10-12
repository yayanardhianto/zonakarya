<div class="award-area-1 space-bottom overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <ul class="award-wrap-area">
                    @foreach ($awards as $award)
                      <li class="single-award-list">
                        <span class="award-year">{{$award?->year}}</span>
                        <div class="award-details">
                            <h4><a href="{{$award?->url}}">{{$award?->title}}</a></h4>
                            <p>{{$award?->sub_title}}</p>
                        </div>
                        <span class="award-tag">{{$award?->tag}}</span>
                    </li>  
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>