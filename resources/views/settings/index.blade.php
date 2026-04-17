@extends('layouts.setting-layout')

@section('title', 'Pengaturan Akun')

@section('setting-content')
<div class="container">
    <h2 class="text-lg font-bold text-red-600 mb-4">Pengaturan Akun</h2>

    @foreach (['otp_success', 'otp_error', 'email_update_success', 'email_update_error', 'password_update_success', 'password_update_error'] as $msg)
        @if(session($msg))
            <div class="bg-{{ str_contains($msg, 'success') ? 'green' : 'red' }}-100 text-{{ str_contains($msg, 'success') ? 'green' : 'red' }}-700 px-4 py-2 rounded mb-2">
                {{ session($msg) }}
            </div>
        @endif
    @endforeach

    <form method="POST" action="{{ route('settings.sendOtpToCurrentEmail') }}" class="mb-4">
        @csrf
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Kirim OTP ke Email Saat Ini</button>
    </form>

    @if(session('otp_verified'))
        <form method="POST" action="{{ route('settings.updateEmail') }}" class="mb-4 space-y-2">
            @csrf
            <div>
                <label for="new_email" class="block text-sm font-medium text-gray-700">Email Baru</label>
                <input type="email" name="new_email" class="form-input w-full border rounded p-2" required>
            </div>
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" class="form-input w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Perbarui Email</button>
        </form>
    @endif

    <form method="POST" action="{{ route('settings.verifyOtp') }}" class="mb-4 space-y-2">
        @csrf
        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700">Masukkan Kode OTP</label>
            <input type="text" name="otp" class="form-input w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Verifikasi OTP</button>
    </form>

    <form method="POST" action="{{ route('settings.updatePassword') }}" class="space-y-2">
        @csrf
        <div>
            <label for="old_password" class="block text-sm font-medium text-gray-700">Kata Sandi Lama</label>
            <input type="password" name="old_password" class="form-input w-full border rounded p-2" required>
        </div>
        <div>
            <label for="new_password" class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
            <input type="password" name="new_password" class="form-input w-full border rounded p-2" required>
        </div>
        <div>
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="new_password_confirmation" class="form-input w-full border rounded p-2" required>
        </div>
        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Perbarui Kata Sandi</button>
    </form>
</div>
@endsection
