<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
    <div class="max-w-md w-full bg-white p-10 rounded-lg shadow-lg">
        <!-- Judul -->
        <h2 class="text-3xl font-bold text-center text-[#D44040] mb-2">Buat Password</h2>
        <p class="text-center text-gray-600 mb-6 text-sm">
            Sudah hampir selesai! <br>
            Buat password yang aman untuk melindungi akun KabarE!
        </p>

        <!-- Notifikasi Kesalahan -->
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Buat Password -->
        <form action="{{ route('store-password') }}" method="POST">
            @csrf

            <!-- Password Baru -->
            <div class="mb-6 relative">
                <label for="password" class="block text-sm font-medium text-gray-700 uppercase">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-2 pr-10" required>
                <span class="absolute right-4 top-[68%] transform -translate-y-1/2 cursor-pointer text-gray-600 hover:text-gray-900"
                    onclick="togglePassword('password', 'eyeIcon1')">
                    <i id="eyeIcon1" class="fa fa-eye"></i>
                </span>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6 relative">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 uppercase">Ulangi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-2 pr-10" required>
                <span class="absolute right-4 top-[68%] transform -translate-y-1/2 cursor-pointer text-gray-600 hover:text-gray-900"
                    onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                    <i id="eyeIcon2" class="fa fa-eye"></i>
                </span>
            </div>

            <!-- Tombol Simpan Password -->
            <button type="submit" class="w-full bg-[#D44040] text-white py-3 rounded-lg text-lg font-bold hover:bg-red-700 transition">
                Konfirmasi
            </button>
        </form>

        <!-- Tautan Masuk -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-[#D44040] font-bold">Masuk</a>
            </p>
        </div>

        <!-- Catatan Kebijakan -->
        <p class="text-xs text-gray-500 text-center mt-4">
            Dengan membuat password di MediaExplant, kamu setuju untuk menjaga kerahasiaan data dan mematuhi kebijakan keamanan kami.
        </p>
    </div>
</div>

<!-- Script Lihat/Sembunyikan Password -->
<script>
    function togglePassword(inputId, iconId) {
        let input = document.getElementById(inputId);
        let icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

@endsection
