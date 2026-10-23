@php
    $flash = session('success')
        ? ['icon' => 'success', 'title' => 'Berhasil', 'text' => session('success')]
        : (session('error')
            ? ['icon' => 'error', 'title' => 'Gagal', 'text' => session('error')]
            : ($errors->any() ? ['icon' => 'error', 'title' => 'Periksa kembali', 'text' => $errors->first()] : null));
@endphp
@if ($flash)
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const flash = @json($flash);
        Swal.fire({
            ...flash,
            confirmButtonColor: '#1c1917',
            ...(flash.icon === 'success' ? { timer: 2200, showConfirmButton: false } : {}),
        });
    });
</script>
@endif
