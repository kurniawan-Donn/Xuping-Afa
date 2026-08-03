@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('body')
<div class="min-h-screen bg-gray-50 flex" x-data="{ sidebarOpen: false }">
    @include('components.dashboard.sidebar')
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" style="display: none;"></div>
    <div class="flex-1 flex flex-col lg:ml-64 min-w-0">
        @include('components.dashboard.navbar')
        <main class="flex-1 p-4 sm:p-6 overflow-x-hidden">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@endsection
