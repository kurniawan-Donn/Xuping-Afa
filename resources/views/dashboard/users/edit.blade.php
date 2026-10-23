@extends('layouts.dashboard')
@section('title', 'Edit Pengguna — Admin Afa Xuping')
@section('header_title', 'Pengguna')

@section('content')
<x-dash.header title="Edit pengguna" :subtitle="$user->email" :back="route('users.index')" />

<form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('dashboard.users._form')
</form>
@endsection
