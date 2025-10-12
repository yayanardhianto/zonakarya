<div class="overflow-hidden space-bottom">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <ul class="client-list-wrap style2">
                    @foreach ($brands->take(6) as $brand)
                        <li>
                            <a href="{{ $brand?->url }}">
                                <span class="link-effect">
                                    <span class="effect-1"><img src="{{ asset($brand?->image) }}"
                                            alt="{{ $brand?->name }}"></span>
                                    <span class="effect-1"><img src="{{ asset($brand?->image) }}"
                                            alt="{{ $brand?->name }}"></span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
