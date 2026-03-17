@if ($paginator->hasPages())
    <div class="pagination-bar">
        <div class="page-info">
            Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
        </div>
        <div class="page-links">
            @if ($paginator->onFirstPage())
                <span class="page-link is-disabled">Prec.</span>
            @else
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prec.</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-link is-disabled">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-link active">{{ $page }}</span>
                        @else
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suiv.</a>
            @else
                <span class="page-link is-disabled">Suiv.</span>
            @endif
        </div>
    </div>
@endif