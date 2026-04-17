@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Profil -->
        <div class="bg-gradient-to-r from-red-500 to-red-700 text-white p-6 text-center">
            @if($user)
                <h1 class="text-3xl font-bold mb-2">{{ $user->nama_lengkap }}</h1>
                <p class="text-lg">{{ '@' . $user->nama_pengguna }}</p>
                <span class="bg-white text-red-600 font-semibold px-3 py-1 rounded-full text-sm">
                    {{ ucfirst($user->role) }}
                </span>
            @else
                <h1 class="text-3xl font-bold mb-2">Pengguna Tidak Dikenal</h1>
                <p class="text-lg">Silakan login untuk melihat profil Anda</p>
            @endif
        </div>

        <!-- Konten Profil -->
        <div class="p-6">
            @if($user)
                <!-- Detail Profil -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Nama Pengguna</h2>
                        <p class="text-gray-800 text-base">{{ $user->nama_pengguna }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Nama Lengkap</h2>
                        <p class="text-gray-800 text-base">{{ $user->nama_lengkap }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Role</h2>
                        <p class="text-gray-800 text-base">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>

                <!-- Aksi Profil -->
                <div class="mt-6 flex justify-center gap-4">
                    <a href="{{ route('settings') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
                        Edit Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center">
                    <p class="text-gray-600">Data pengguna tidak tersedia. Silakan login terlebih dahulu.</p>
                    <a href="{{ route('login') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600 mt-4 inline-block">
                        Login
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tambahan Bagian Informasi -->
    <div class="mt-10">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h2>
            <ul class="space-y-4">
                <li class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 text-white flex justify-center items-center rounded-full">
                        <i class="fa-solid fa-pencil-alt"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-800 font-semibold">Menulis artikel baru: <span class="text-red-500">"Judul Artikel"</span></p>
                        <p class="text-sm text-gray-600">5 hari yang lalu</p>
                    </div>
                </li>
                <li class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 text-white flex justify-center items-center rounded-full">
                        <i class="fa-solid fa-comment-dots"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-800 font-semibold">Bergabung dalam diskusi: <span class="text-green-500">"Topik Diskusi"</span></p>
                        <p class="text-sm text-gray-600">1 minggu yang lalu</p>
                    </div>
                </li>
                <li class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-500 text-white flex justify-center items-center rounded-full">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-800 font-semibold">Mendapatkan badge baru: <span class="text-yellow-500">"Kontributor Aktif"</span></p>
                        <p class="text-sm text-gray-600">2 minggu yang lalu</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
