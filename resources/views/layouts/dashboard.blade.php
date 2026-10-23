@extends('layouts.app')

@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('body')
<div class="min-h-screen bg-stone-50" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    @include('components.dashboard.sidebar')
    <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-ink/50 backdrop-blur-sm lg:hidden"></div>

    <div class="flex min-h-screen min-w-0 flex-col lg:pl-64">
        @include('components.dashboard.navbar')
        <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mx-auto max-w-7xl">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection
