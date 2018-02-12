@if ($paginator->hasPages())
    <ol class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="pagination__box disabled"><span>&laquo;</span></li>
        @else
            <li class="pagination__box"><a href="{{ $paginator->previousPageUrl() }}" class="pagination__box__link"
                                           rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="pagination__box disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination__box is-active"><span>{{ $page }}</span></li>
                    @else
                        <li class="pagination__box"><a href="{{ $url }}" class="pagination__box__link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="pagination__box"><a href="{{ $paginator->nextPageUrl() }}" class="pagination__box__link"
                                           rel="next">&raquo;</a></li>
        @else
            <li class="pagination__box disabled"><span>&raquo;</span></li>
        @endif

    </ol>
@endif