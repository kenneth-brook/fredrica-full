
<ul class="pagination pd-5" role="navigation" aria-label="Pagination">

 <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <li class="disabled"><a class = "btn btn-blue left" href="" rel="prev">Previous</a></li>
    @else
        <li class=""><a class = "btn btn-blue left" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
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
                    <li class="current ">{{ $page }}</li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <li class=""><a class = "btn btn-blue" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
    @else
        <li class="disabled"><a class = "btn btn-blue" href="javascript:void(0)" rel="next">Next</a></li>
    @endif
</ul>
