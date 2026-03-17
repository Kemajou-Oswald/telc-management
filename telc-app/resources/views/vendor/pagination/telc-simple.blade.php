@if ($paginator->hasPages())
    <div class="pagination-bar">
        <div class="page-info">Page {{ $paginator->currentPage() }}</div>
        <div class="page-links">
            @if ($paginator->onFirstPage())
                <span class="page-link is-disabled">Prec.</span>
            @else
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prec.</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suiv.</a>
            @else
                <span class="page-link is-disabled">Suiv.</span>
            @endif
        </div>
    </div>
@endif