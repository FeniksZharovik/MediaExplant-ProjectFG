@extends('layouts.setting-layout')

@section('title', 'Notifikasi')

@section('setting-content')
    @php
        $userUid = Cookie::get('user_uid');
        $user = $userUid ? \App\Models\User::where('uid', $userUid)->first() : null;
    @endphp

@section('setting-content')
    @if (!$user)
        <!-- Tampilan jika belum login -->
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center space-y-4">
            <i class="fas fa-user-lock text-6xl text-gray-400"></i>
            <h2 class="text-xl font-semibold text-gray-700">Anda belum login</h2>
            <p class="text-sm text-gray-500">Silakan login terlebih dahulu untuk mengatur notifikasi Anda.</p>
            <a href="{{ route('login') }}" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 transition-all">
                Login Sekarang
            </a>
        </div>
    @else
        <div class="p-6 max-w-xl">
            <!-- Notifikasi Desktop -->
            <div class="border-b border-gray-300 pb-4 mb-4">
                <p class="text-sm text-red-600 font-semibold mb-1">Notifikasi Desktop</p>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">Dapatkan notifikasi di browser</p>
                        <p class="text-sm text-gray-500">Terima notifikasi di komputer, sekalipun Anda tidak sedang membuka
                            situs
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-red-500 transition-all"></div>
                        <div
                            class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Notifikasi Email -->
            <div class="border-b border-gray-300 pb-4 mb-4">
                <p class="text-sm text-red-600 font-semibold mb-1">Notifikasi Email</p>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">Dapatkan notifikasi</p>
                        <p class="text-sm text-gray-500">Terima notifikasi yang akan di kirimkan ke email anda</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-red-500 transition-all"></div>
                        <div
                            class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition peer-checked:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-4">Pengaturan ini membantu kami mengirimkan notifikasi yang sesuai dengan
                preferensi Anda.</p>
        </div>
    @endif
@endsection
