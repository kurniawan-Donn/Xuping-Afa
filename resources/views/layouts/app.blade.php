<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <link rel="icon" type="image/x-icon" href="/fav-favicon.ico?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="/fav-favicon-32x32.png?v=4">
    <link rel="icon" type="image/png" sizes="16x16" href="/fav-favicon-16x16.png?v=4">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=4">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#faf7f2">

    @stack('meta')

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data class="@yield('body_class')">
    @yield('body')
    @include('components.alert')
    @stack('scripts')
</body>
</html>
