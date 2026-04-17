<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
    <div class="max-w-md w-full bg-white p-10 rounded-lg shadow-lg text-center">
        <h2 class="text-2xl font-bold text-[#D44040]">Selamat Datang Kembali!</h2>
        <p class="text-gray-600 mb-6">Ayo masuk dan jangan lewatkan berita penting di kampusmu!</p>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                {{ $errors->first('message') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="text-left">
            @csrf
            <div class="mb-4">
                <label for="identifier" class="block text-sm font-medium text-gray-700 uppercase">Nama Pengguna atau Email</label>
                <input type="text" id="identifier" name="identifier" placeholder="Masukkan nama pengguna atau email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-1" required>
            </div>

            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700 uppercase">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-1 pr-10" required>
                <span class="absolute right-4 top-12 transform -translate-y-1/2 cursor-pointer text-gray-600 hover:text-gray-900" onclick="togglePassword()">
                    <i id="eyeIcon" class="fa fa-eye text-lg"></i>
                </span>
            </div>

            <div class="flex justify-between text-sm mb-6">
                <div></div>
                <a href="{{ route('password.request') }}" class="text-[#D44040] hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" class="w-full bg-[#D44040] text-white py-3 rounded-lg hover:bg-red-700 focus:outline-none text-lg font-semibold">Masuk</button>
        </form>

        <div class="flex items-center my-4">
            <span class="flex-1 border-b"></span>
            <span class="mx-2 text-sm text-gray-500 font-semibold whitespace-nowrap">ATAU MASUK DENGAN</span>
            <span class="flex-1 border-b"></span>
        </div>

        <p class="text-sm">Belum Memiliki akun? <a href="{{ route('register') }}" class="text-[#D44040] font-semibold hover:underline">Daftar</a></p>

        <p class="text-xs text-gray-500 mt-4">Dengan login di MediaExplant, kamu menyetujui kebijakan kami terkait pengelolaan data, penggunaan aplikasi, dan ketentuan layanan yang berlaku.</p>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
