@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-1 py-1">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center text-sm text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-house mr-1"></i>
                <span>Home</span>
            </a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="/dashboard-admin/kotak-masuk" class="text-gray-600 hover:text-blue-600">Kotak Masuk</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Detail</span>
        </nav>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">Detail Kotak Masuk</h1>
    </div>

    <div class="flex gap-4">
        <!-- Main Inbox -->
        <div class="flex-1 bg-white rounded-xl shadow p-4">
            <!-- Toolbar actions -->
            <div class="flex items-center gap-2 mb-4 border-b pb-2">
                <button onclick="window.history.back()" class="p-1 hover:bg-gray-100 rounded" title="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                {{-- <button class="p-1 hover:bg-gray-100 rounded"><i class="fa-solid fa-trash"></i></button> --}}
                {{-- <button class="p-1 hover:bg-gray-100 rounded">📥</button>
                <button class="p-1 hover:bg-gray-100 rounded">📁</button>
                <button class="p-1 hover:bg-gray-100 rounded">✉️</button> --}}

            </div>

            <!-- Email detail -->
            <div class="space-y-4">
                <h1 class="font-bold text-lg">{{ $pesan->status}}</h1>
                <div class="flex items-center gap-3">
                    @php
                    $base64Image = $pesan->user && $pesan->user->profile_pic
                    ? 'data:image/jpeg;base64,' . base64_encode($pesan->user->profile_pic)
                    : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                    @endphp
                    <img src="{{ $base64Image }}" alt="avatar" class="w-10 h-10 rounded-full" />
                    <div>
                        <h2 class="font-semibold">
                            {{ $pesan->user ? $pesan->user->nama_pengguna : $pesan->nama }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ $pesan->user ? $pesan->user->email : $pesan->email }}
                        </p>
                    </div>
                </div>

                <!-- Timestamp -->
                <p class="text-xs text-black mt-1">
                    @php
                    $createdAt = \Carbon\Carbon::parse($pesan->created_at);
                    @endphp
                    {{ $createdAt->isoFormat('DD MMM YYYY HH:mm') }}
                </p>

                <div class="space-y-4 text-sm text-gray-700 leading-relaxed">
                    <!-- Kontak Karena -->
                    <p><strong>Kontak Karena:</strong> {{ $pesan->pesan }}</p>

                    <!-- Detail Pesan (jika ada) -->
                    @if (!empty($pesan->detail_pesan))
                    <p><strong>Detail:</strong> {{ $pesan->detail_pesan }}</p>
                    @endif
                </div>

                <!-- Attachments -->
                @if (!empty($pesan->media))
                <div class="mt-4">
                    <h3 class="font-semibold mb-2">📎 Lampiran Gambar</h3>
                    <div class="flex gap-4 flex-wrap">
                        <div class="bg-gray-100 px-4 py-2 rounded-lg flex items-center gap-2">
                            <img src="data:image/jpeg;base64,{{ $pesan->media }}" alt="{{ $pesan->status }}"
                                class="w-auto h-64 object-cover rounded-lg shadow-md" />
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            {{-- <div class="flex gap-2 mt-6">
                    <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">↩️ Reply</button>
                    <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">⤴️ Reply All</button>
                    <button class="bg-gray-100 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">➡️ Forward</button>
                </div> --}}
        </div>
    </div>
</div>
</div>
@endsection
