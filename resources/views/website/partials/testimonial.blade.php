<figure class="flex min-w-[82%] snap-start flex-col rounded-2xl border border-stone-200/70 bg-white p-6 shadow-soft sm:min-w-[45%] md:min-w-0">
    <div class="flex gap-0.5 text-gold-400" aria-label="Rating {{ $t->rating }} dari 5">
        @for ($s = 1; $s <= 5; $s++)
            <svg class="h-4 w-4 {{ $s <= $t->rating ? 'fill-current' : 'fill-stone-200 text-stone-200' }}" viewBox="0 0 24 24" aria-hidden="true"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/></svg>
        @endfor
    </div>
    <blockquote class="mt-4 flex-1 font-display text-xl leading-snug text-stone-700">&ldquo;{{ $t->content }}&rdquo;</blockquote>
    <figcaption class="mt-6 flex items-center gap-3 border-t border-stone-100 pt-4">
        <span class="grid h-10 w-10 place-items-center rounded-full bg-gold-100 font-semibold text-gold-700">{{ mb_strtoupper(mb_substr($t->name, 0, 1)) }}</span>
        <span>
            <span class="block text-sm font-semibold">{{ $t->name }}</span>
            @if ($t->location)<span class="block text-xs text-stone-500">{{ $t->location }}</span>@endif
        </span>
    </figcaption>
</figure>
