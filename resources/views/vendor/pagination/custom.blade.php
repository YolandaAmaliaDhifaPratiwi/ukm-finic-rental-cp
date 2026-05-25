@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <button class="page-btn" disabled style="opacity:.4;cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15,18 9,12 15,6"/></svg>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15,18 9,12 15,6"/></svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-btn" style="cursor:default;border:none;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,18 15,12 9,6"/></svg>
            </a>
        @else
            <button class="page-btn" disabled style="opacity:.4;cursor:not-allowed;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9,18 15,12 9,6"/></svg>
            </button>
        @endif
    </div>
@endif