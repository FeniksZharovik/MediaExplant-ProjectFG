@extends('layouts.admin-layouts')
@section('content')
@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
      <!-- Breadcrumb -->
      {{-- <div class="mb-6">
        <nav class="flex items-center text-sm text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-house mr-1"></i>
                <span>Home</span>
            </a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="/dashboard-admin/pengguna" class="text-gray-600 hover:text-blue-600">Pengguna</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Detail</span>
        </nav>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">Detail Akun</h1>
    </div> --}}
</div>
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('
            success ') }}',
            timer: 3000, // auto close 3 detik
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b82f6', // warna biru Tailwind 'blue-500'
            buttonsStyling: false,
            customClass: {
                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded'
            }
        });
    });
</script>
@endif
@endsection
