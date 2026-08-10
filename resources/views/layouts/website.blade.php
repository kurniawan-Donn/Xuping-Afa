@extends('layouts.app')

@section('body')
@php
$settings = \App\Models\Setting::pluck('value', 'key')->toArray();
@endphp
@include('components.website.navbar')
<main class="pt-20 min-h-screen">
    @yield('content')
</main>
@include('components.website.footer')
@endsection
