@if ($paginator->hasPages())
    <div id="paginator">
        <div id="item-count">
            @if ($paginator->firstItem())
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                -
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('of') !!}
            <span class="font-medium">{{ $paginator->total() }}</span>
        </div>

        <div class="pagination">
            {{-- Previous Page Link --}}
            {{--            <a class="arrow {{ $paginator->onFirstPage() ? 'disabled' : '' }}"--}}
            {{--               href="{{ $paginator->previousPageUrl() }}" rel="prev"--}}
            {{--               aria-label="@lang('pagination.previous')">{{ __('back') }}</a>--}}

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Separator --}}
                @if (is_string($element))
                    <span class="disabled" aria-disabled="true">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            {{--            <a class="arrow {{ $paginator->hasMorePages() ? '' : 'disabled' }}"--}}
            {{--               href="{{ $paginator->nextPageUrl() }}" rel="next"--}}
            {{--               aria-label="@lang('pagination.next')">{{ __('next') }}</a>--}}
        </div>
    </div>
@endif
