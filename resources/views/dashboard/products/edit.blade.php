@extends('layouts.dashboard')
@section('title', 'Edit Produk — Admin Afa Xuping')
@section('header_title', 'Edit Produk')

@section('content')
<x-dash.header title="Edit produk" :subtitle="$product->name" :back="route('products.index')">
    @if ($product->is_active)
        <a href="{{ route('website.products.show', $product) }}" target="_blank" class="btn btn-outline"><x-icon name="external" class="h-4 w-4" /> Lihat di website</a>
    @endif
</x-dash.header>

<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('dashboard.products._form')
</form>
@endsection
