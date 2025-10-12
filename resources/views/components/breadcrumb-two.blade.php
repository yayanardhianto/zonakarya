@props([
    'links' => [],
    'title' => cache('setting.app_name') ?? config('app.name'),
])
<div class="breadcumb-wrapper style2 bg-smoke">
    <div class="container-fluid">
        <div class="breadcumb-content">
            <ul class="breadcumb-menu">
                @foreach ($links as $key => $link)
                    <li><a href="{{ $link['url'] }}">{{ $link['text'] }}</a></li>
                @endforeach
                <li>{{ $title }}</li>
            </ul>
        </div>
    </div>
</div>
