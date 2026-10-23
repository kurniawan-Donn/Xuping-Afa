@extends('layouts.dashboard')
@section('title', 'Tambah Pengguna — Admin Afa Xuping')
@section('header_title', 'Pengguna')

@section('content')
<x-dash.header title="Pengguna baru" subtitle="Buat akun untuk admin toko." :back="route('users.index')" />

<form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('dashboard.users._form', ['user' => new \App\Models\User])
</form>
@endsection
