<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl font-extrabold text-center text-[#D44040] mb-2">Belum Punya Akun?</h2>
        <p class="text-center text-gray-600 mb-6">
            Daftar sekarang di Media Explant dan nikmati akses penuh ke berita terkini dan fitur menarik dengan langkah mudah!
        </p>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-2" required>
            </div>

            <div class="mb-4">
                <label for="nama_pengguna" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" placeholder="Masukkan username"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-2" required>
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan alamat email"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-2" required>
            </div>

            <button type="submit" class="w-full bg-[#D44040] text-white py-3 rounded-lg text-lg font-semibold hover:bg-red-700 focus:outline-none">
                Konfirmasi
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Sudah Memiliki akun? <a href="{{ route('login') }}" class="text-[#D44040] font-semibold hover:underline">Masuk</a>
            </p>
        </div>

        <p class="text-xs text-gray-500 text-center mt-4">
            Dengan mendaftar di MediaExplant, kamu menyetujui kebijakan privasi serta syarat dan ketentuan layanan yang berlaku.
        </p>
    </div>
</div>
@endsection
