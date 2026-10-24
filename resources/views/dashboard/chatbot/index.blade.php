@extends('layouts.dashboard')
@section('title', 'Chatbot — Admin Afa Xuping')
@section('header_title', 'Chatbot AI')

@php
    $statusBadge = ['ok' => ['badge-green', 'Dijawab'], 'limit' => ['badge-amber', 'Kuota habis'], 'error' => ['badge-red', 'Gagal'], 'disabled' => ['badge-gray', 'Nonaktif']];
    $usd = fn ($v) => '$' . number_format($v, $v < 1 ? 4 : 2);
@endphp

@section('content')
<x-dash.header title="Chatbot Tanya Afa" subtitle="Pemakaian, perkiraan biaya, dan pertanyaan pengunjung.">
    <a href="{{ route('settings.index') }}#chatbot" class="btn btn-outline"><x-icon name="settings" class="h-4 w-4" /> Pengaturan</a>
</x-dash.header>

@if (! $enabled)
    <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
        Chatbot <b>tidak tampil</b> di website:
        {{ $hasKey ? 'aktifkan di Pengaturan → Chatbot AI.' : 'GEMINI_API_KEY belum diisi di file .env server.' }}
    </div>
@endif

<div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
    @foreach ([
        ['Pesan hari ini', $stats['today'], 'kuota terpakai ' . $stats['used_today'] . ' / ' . $stats['daily_limit'], 'chat'],
        ['Pesan bulan ini', $stats['month'], $stats['errors'] . ' gagal hari ini', 'trending'],
        ['Perkiraan biaya', $usd($stats['month_cost']), '±Rp ' . number_format($stats['month_cost'] * 16000, 0, ',', '.') . ' bulan ini', 'tag'],
        ['Rata-rata token', number_format($stats['avg_tokens'], 0, ',', '.'), 'per pesan · ' . $model, 'sparkles'],
    ] as [$label, $value, $hint, $icon])
        <div class="card flex flex-col gap-4 p-4 lg:p-5">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-stone-500">{{ $label }}</span>
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gold-50 text-gold-600"><x-icon :name="$icon" class="h-4.5 w-4.5" /></span>
            </div>
            <div>
                <p class="font-display text-3xl font-bold leading-none lg:text-4xl">{{ $value }}</p>
                <p class="mt-1.5 truncate text-xs text-stone-500">{{ $hint }}</p>
            </div>
        </div>
    @endforeach
</div>
<p class="mt-2 text-xs text-stone-400">Biaya dihitung dari jumlah token × harga di <code>config/services.php</code> (kurs ±Rp 16.000/USD). Tagihan resmi tetap mengacu ke Google Cloud Billing.</p>

<div class="card mt-6 overflow-hidden">
    <div class="border-b border-stone-100 px-5 py-4">
        <p class="font-semibold">Riwayat percakapan</p>
        <p class="text-xs text-stone-500">Tanpa data pribadi pengunjung. Dihapus otomatis setelah 30 hari.</p>
    </div>
    <ul class="divide-y divide-stone-100">
        @forelse ($logs as $log)
            @php [$badge, $label] = $statusBadge[$log->status] ?? ['badge-gray', $log->status]; @endphp
            <li class="px-5 py-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-semibold">{{ $log->question }}</p>
                    <span class="flex items-center gap-2 text-xs text-stone-400">
                        <span class="badge {{ $badge }}">{{ $label }}</span>
                        {{ $log->created_at->diffForHumans() }}
                    </span>
                </div>
                @if ($log->answer)
                    <p class="mt-1.5 text-sm text-stone-600">{{ $log->answer }}</p>
                @endif
                <p class="mt-1.5 text-xs text-stone-400">
                    {{ $log->input_tokens + $log->output_tokens }} token
                    @if ($log->product_ids) · merekomendasikan {{ count($log->product_ids) }} produk @endif
                </p>
            </li>
        @empty
            <li class="px-5 py-16 text-center text-sm text-stone-500">Belum ada percakapan.</li>
        @endforelse
    </ul>
    @if ($logs->hasPages())
        <div class="border-t border-stone-100 px-4 py-3">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
