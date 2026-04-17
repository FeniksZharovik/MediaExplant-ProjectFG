{{-- resources/views/dashboard-admin/menu/karya/detail.blade.php --}}
@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-house mr-1"></i> Home
        </a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('admin.karya') }}" class="text-gray-600 hover:text-blue-600">Karya</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700 font-medium">Detail</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Left column: Content & Media -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Card Header -->
                <div class="bg-white px-6 py-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Detail Karya</h2>
                </div>

                <!-- Card Body -->
                <div class="p-8 space-y-6">
                    <!-- Judul & Info -->
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $karya->judul }}</h1>
                        <div class="flex items-center text-sm text-gray-500 space-x-3">
                            <span>Pengunggah: <strong class="text-gray-700">{{ $karya->user->nama_pengguna }}</strong></span>
                            <span>•</span>
                            <span>{{ date('j F Y, H:i', strtotime($karya->release_date)) }}</span>
                        </div>
                    </div>

                    <!-- Media -->
                    <div>
                        @if(in_array($karya->kategori, ['fotografi', 'desain grafis']))
                            <img src="{{ $karya->media_url ?? 'https://via.placeholder.com/280x240' }}" 
                                 alt="Media" class="w-[280px] h-[240px] object-cover rounded-lg shadow-md">
                        @else
                            <div class="w-[280px] h-[240px] flex items-center justify-center bg-gray-200 rounded">
                                <img src="data:image/jpeg;base64,{{ $karya->media }}" 
                                     alt="{{ $karya->judul }}"
                                     class="w-[90px] h-[70px] object-cover rounded-lg shadow-md" />
                            </div>
                        @endif
                    </div>
                    

                    <!-- Konten Karya -->
                    @if($karya->konten)
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Konten {{ ucfirst($karya->kategori) }}</h3>                        
                        <div class="prose max-w-none text-gray-700">
                            {!! $karya->konten !!}
                        </div>
                    </div>
                    @endif

                    <!-- Deskripsi Karya -->
                    @if($karya->deskripsi)
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Caption {{ ucfirst($karya->kategori) }}</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! $karya->deskripsi !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right column: Metadata, Tags, Pengunggah -->
        <div class="md:col-span-1 space-y-6">
            <!-- Metadata Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Metadata</h3>
                </div>
                <div class="p-6 space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Kategori:</span>
                        <span class="font-medium">{{ ucfirst($karya->kategori) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Visibilitas:</span>
                        <span class="font-medium">{{ ucfirst($karya->visibilitas) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">View Count:</span>
                        <span class="font-medium">
                            @if($karya->view_count > 0)
                                {{ $karya->view_count }} 
                            @else
                               0
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Like</span>
                        <span class="font-medium">{{ $likeCount }}</span>
                    </div>     
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tidak Suka</span>
                        <span class="font-medium">{{ $dislikeCount }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Terbit:</span>
                        <span class="font-medium">{{ date('d M Y', strtotime($karya->release_date)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tags Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Tags</h3>
                </div>
                <div class="p-6">
                    @if($karya->tags->isEmpty())
                        <p class="text-gray-500 text-sm">Tidak ada tag untuk karya ini.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($karya->tags as $tag)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                  {{ $tag->nama_tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pengunggah Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-white px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Pengunggah</h3>
                </div>
                <div class="p-6 flex items-center">
                    @php
                        $img = $karya->user->profile_pic
                              ? 'data:image/jpeg;base64,'.base64_encode($karya->user->profile_pic)
                              : 'https://via.placeholder.com/64';
                    @endphp
                    <img src="{{ $img }}" alt="Foto Profil"
                         class="w-16 h-16 rounded-full object-cover border border-gray-300">
                    <div class="ml-4">
                        <p class="font-medium text-gray-800">{{ $karya->user->nama_pengguna }}</p>
                        <p class="text-sm text-gray-500">{{ $karya->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
