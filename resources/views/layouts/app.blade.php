<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

	<!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/fav-favicon.ico?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="/fav-favicon-32x32.png?v=4">
    <link rel="icon" type="image/png" sizes="16x16" href="/fav-favicon-16x16.png?v=4">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=4">
    <link rel="manifest" href="/site.webmanifest">
    
<meta name="theme-color" content="#ffffff">
    
    @vite(['resources/css/app.css'])
    <!-- STACK META AGAR HALAMAN LAIN BISA MENGIRIM DATA GAMBAR/JUDUL -->
    @stack('meta')
    @stack('styles')
</head>
<body>
    @yield('body')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.alert')
    @stack('scripts')
</body>
</html>