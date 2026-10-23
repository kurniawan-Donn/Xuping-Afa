@extends('layouts.dashboard')
@section('title', 'Tambah Produk — Admin Afa Xuping')
@section('header_title', 'Tambah Produk')

@section('content')
<x-dash.header title="Tambah produk" subtitle="Lengkapi detail agar pembeli yakin memesan." :back="route('products.index')" />

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('dashboard.products._form')
</form>
@endsection
