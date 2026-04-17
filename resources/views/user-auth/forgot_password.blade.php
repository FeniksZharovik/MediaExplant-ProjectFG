<link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
@extends('layouts.auth-layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12">
    <div class="max-w-md w-full bg-white p-10 rounded-lg shadow-lg">
        <!-- Judul -->
        <h2 class="text-3xl font-bold text-center text-[#D44040] mb-2">Lupa Password?</h2>
        <p class="text-center text-gray-600 mb-6 text-sm">
            Masukkan email akunmu, dan kami akan mengirimkan link untuk Anda verifikasi.
        </p>

        <!-- Notifikasi -->
        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('password.sendOtp') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 uppercase">Alamat Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D44040] mt-1" required>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="w-full bg-[#D44040] text-white py-3 rounded-lg hover:bg-red-700 focus:outline-none">Kirim</button>

            <!-- Tombol Batal -->
            <button type="button" onclick="window.history.back()" class="w-full text-[#D44040] py-3 rounded-lg mt-2 hover:text-red-700 focus:outline-none">Batal</button>
        </form>

        <!-- Catatan -->
        <p class="text-xs text-center text-gray-500 mt-6">
            Dengan mengirim email melalui MediaExplant, kamu memberikan persetujuan untuk penggunaan data sesuai dengan kebijakan privasi kami dan menyetujui syarat dan ketentuan yang berlaku.
        </p>
    </div>
</div>
@endsection
