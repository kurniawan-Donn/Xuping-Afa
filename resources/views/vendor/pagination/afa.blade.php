@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex items-center justify-between gap-3">
        <p class="hidden text-sm text-stone-500 sm:block">
            Halaman <b class="text-ink">{{ $paginator->currentPage() }}</b> dari {{ $paginator->lastPage() }}
        </p>

        <div class="flex flex-1 items-center justify-center gap-1 sm:flex-none">
            @if ($paginator->onFirstPage())
                <span class="grid h-10 w-10 place-items-center rounded-full text-stone-300" aria-disabled="true"><x-icon name="chevron-left" class="h-4 w-4" /></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="grid h-10 w-10 place-items-center rounded-full text-stone-600 transition hover:bg-white hover:shadow-soft" aria-label="Sebelumnya"><x-icon name="chevron-left" class="h-4 w-4" /></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="grid h-10 w-8 place-items-center text-sm text-stone-400">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="grid h-10 min-w-10 place-items-center rounded-full bg-ink px-3 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="grid h-10 min-w-10 place-items-center rounded-full px-3 text-sm font-medium text-stone-600 transition hover:bg-white hover:shadow-soft" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="grid h-10 w-10 place-items-center rounded-full text-stone-600 transition hover:bg-white hover:shadow-soft" aria-label="Berikutnya"><x-icon name="chevron-right" class="h-4 w-4" /></a>
            @else
                <span class="grid h-10 w-10 place-items-center rounded-full text-stone-300" aria-disabled="true"><x-icon name="chevron-right" class="h-4 w-4" /></span>
            @endif
        </div>
    </nav>
@endif
