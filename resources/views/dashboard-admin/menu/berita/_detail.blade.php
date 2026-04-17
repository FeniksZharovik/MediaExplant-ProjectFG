@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-house mr-1"></i>
            Home
        </a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="/dashboard-admin/berita" class="text-gray-600 hover:text-blue-600">Berita</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700 font-medium">Detail</span>
    </nav>
    
    <!-- Konten Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Kolom Kiri -->
        <div class="md:col-span-2 space-y-6">
            <!-- Konten Berita -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Card Header -->
                <div class="bg-white px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Konten Berita</h2>
                </div>
                <!-- Card Body -->
                <div class="p-6">
                    <!-- Judul dan Cover -->
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $beritas->judul }}</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <span>Oleh: {{ $beritas->user->nama_pengguna }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ date('d M Y - H:i', strtotime($beritas->tanggal_diterbitkan)) }}</span>
                        </div>
                        @if ($beritas->cover_image)
                        <img src="{{ asset($beritas->cover_image) }}" alt="Cover Berita"
                            class="w-full h-64 object-cover rounded-lg shadow-md mt-4">
                        @endif
                    </div>

                    <div class="prose max-w-none text-gray-700">
                        {!! $beritas->konten_berita !!}
                    </div>
                </div>
            </div>            
        </div>

        <!-- Kolom Kanan -->
        <div class="md:col-span-1 space-y-6">
            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Metadata</h3>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium">{{ $beritas->kategori }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Visibilitas:</span>
                        <span class="font-medium">{{ ucfirst($beritas->visibilitas) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">View Count:</span>
                        <span class="font-medium">{{ $beritas->view_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Like:</span>
                        <span class="font-medium">{{ $likeCount }}</span>
                    </div>     
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tidak Suka:</span>
                        <span class="font-medium">{{ $dislikeCount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Komentar:</span>
                        <span class="font-medium">{{ $dislikeCount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Diterbitkan:</span>
                        <span class="font-medium">{{ date('d M Y', strtotime($beritas->tanggal_diterbitkan)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Tags</h3>
                </div>
                <div class="p-6">
                    @if($beritas->tags->isEmpty())
                        <p class="text-gray-500 text-sm">Tidak ada tag untuk berita ini.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach ($beritas->tags as $tag)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $tag->nama_tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Penulis -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Penulis</h3>
                </div>
                <div class="p-6 flex items-center">
                    @php
                    $base64Image = $beritas && $beritas->user?->profile_pic
                        ? 'data:image/jpeg;base64,' . base64_encode($beritas->user?->profile_pic)
                        : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                    @endphp
                    <img src="{{ $base64Image }}" alt="Foto Profil"
                        class="w-16 h-16 rounded-full object-cover border border-gray-300">
                    <div class="ml-4">
                        <p class="font-medium text-gray-800">{{ $beritas->user->nama_pengguna }}</p>
                        <p class="text-sm text-gray-500">{{ $beritas->user->email }}</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
