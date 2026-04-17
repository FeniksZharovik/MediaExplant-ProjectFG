<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
    <div class="max-w-md w-full bg-white p-10 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold text-center text-[#D44040] mb-2">Ubah Password</h2>
        <p class="text-center text-gray-600 mb-6 text-sm">
            Lupa password? Jangan khawatir, atur ulang <br>
            password-mu dengan mudah di sini
        </p>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.updatePassword') }}" method="POST">
            @csrf
            <div class="mb-6 relative">
                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">PASSWORD BARU</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-1 pr-12" required>
                <span class="absolute right-4 top-[68%] transform -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-900"
                    onclick="togglePassword('password', 'eyeIcon1')">
                    <i id="eyeIcon1" class="fa fa-eye"></i>
                </span>
            </div>

            <div class="mb-6 relative">
                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">ULANGI PASSWORD</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-1 pr-12" required>
                <span class="absolute right-4 top-[68%] transform -translate-y-1/2 cursor-pointer text-gray-500 hover:text-gray-900"
                    onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                    <i id="eyeIcon2" class="fa fa-eye"></i>
                </span>
            </div>

            <button type="submit" class="w-full bg-[#D44040] text-white py-3 rounded-lg hover:bg-red-700 focus:outline-none text-lg font-semibold">
                Konfirmasi
            </button>
        </form>

        <div class="text-center mt-4 text-sm">
            Sudah Memiliki akun?
            <a href="{{ route('login') }}" class="text-[#D44040] font-semibold hover:underline">
                Masuk
            </a>
        </div>

        <p class="text-xs text-center text-gray-500 mt-4">
            Dengan membuat password di MediaExplant, kamu setuju untuk<br>
            menjaga kerahasiaan data dan mematuhi kebijakan keamanan kami.
        </p>
    </div>
</div>

<!-- Script untuk toggle password visibility -->
<script>
    function togglePassword(inputId, iconId) {
        const passwordField = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

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
