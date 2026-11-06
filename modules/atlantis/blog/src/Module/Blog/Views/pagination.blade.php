
<ul class="pagination" role="navigation" aria-label="Pagination">

 <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="disabled"><span>&laquo;</span></li>
    @else
        <li class=""><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
    @endif


 @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
        @if (is_string($element))
            <li class="ellipsis"><span>{{ $element }}</span></li>
        @endif

    <!-- Array Of Links -->
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="current "><span class="show-for-sr">You're on page</span> {{ $page }}</li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li class="disabled"><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
    @else
        <li class=""><span>&raquo;</span></li>
    @endif
</ul>
