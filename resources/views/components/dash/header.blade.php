@props(['title', 'subtitle' => null, 'back' => null])
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div class="flex min-w-0 items-center gap-3">
        @if ($back)
            <a href="{{ $back }}" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-stone-200 bg-white text-stone-500 transition hover:border-stone-300 hover:text-ink" aria-label="Kembali">
                <x-icon name="arrow-left" class="h-4.5 w-4.5" />
            </a>
        @endif
        <div class="min-w-0">
            <h1 class="truncate font-display text-3xl font-bold tracking-tight lg:text-4xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-0.5 text-sm text-stone-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    @if ($slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
    @endif
</div>
