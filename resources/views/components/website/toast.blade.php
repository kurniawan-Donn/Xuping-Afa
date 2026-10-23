<div class="pointer-events-none fixed inset-x-0 top-4 z-[60] flex flex-col items-center gap-2 px-4" aria-live="polite">
    <template x-for="toast in $store.toast.items" :key="toast.id">
        <div x-transition.opacity.duration.300ms
            :class="toast.type === 'error' ? 'bg-red-600' : 'bg-ink'"
            class="pointer-events-auto flex items-center gap-2 rounded-full px-5 py-3 text-sm font-medium text-white shadow-lift">
            <svg x-show="toast.type !== 'error'" class="h-4 w-4 text-gold-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
            <span x-text="toast.message"></span>
            <button x-show="toast.type !== 'error'" type="button" @click="$store.cart.open = true" class="ml-1 font-semibold text-gold-300 underline-offset-2 hover:underline">Lihat</button>
        </div>
    </template>
</div>
