@if ($paginator->hasPages())
    <div class="pagination-wrap mt-50">
        <nav aria-label="Page navigation example">
            <ul class="pagination list-wrap">
                @if (! $paginator->onFirstPage())
                    <li class="page-item next-page"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i
                                class="fas fa-arrow-left"></i></a>
                @endif
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item"><a class="page-link" href="javascript:;">{{ $element }}</a></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><a class="page-link"
                                        href="javascript:;">{{ $page }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item next-page"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"><i class="fas fa-arrow-right"></i></a></li>
                @endif
            </ul>
        </nav>
    </div>
@endif
