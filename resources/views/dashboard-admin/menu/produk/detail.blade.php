{{-- resources/views/dashboard-admin/menu/produk/detail.blade.php --}}
@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
        <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
            <i class="fa-solid fa-house mr-1"></i> Home
        </a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('admin.produk') }}" class="text-gray-600 hover:text-blue-600">Produk</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700 font-medium">Detail</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Kiri: Detail Produk -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Detail Produk</h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Judul & Info -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">{{ $produk->judul }}</h1>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <span>Oleh: {{ $produk->user->nama_pengguna }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ date('d M Y - H:i', strtotime($produk->release_date)) }}</span>
                        </div>
                    </div>

                    <!-- PDF Preview -->
                    <iframe src="{{ route('admin.pdfPreview', $produk->id) }}#page=1"
                        class="w-full h-72 rounded-lg shadow border" type="application/pdf"></iframe>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-4">
                        <a href="{{ route('admin.downloadPdf', ['id' => $produk->id]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow w-1/2 text-center">
                            <i class="fas fa-download"></i> Unduh Sekarang
                        </a>
                        <button id="toggle-preview" data-id="{{ $produk->id }}"
                            class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-lg shadow w-1/2 text-center">
                            <i class="fas fa-eye"></i> Pratinjau
                        </button>
                    </div>

                    <!-- Deskripsi -->
                    @if($produk->deskripsi)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi Produk</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! $produk->deskripsi !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kanan: Metadata, Tags, Penulis -->
        <div class="md:col-span-1 space-y-6">
            <!-- Metadata -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Metadata</h3>
                </div>
                <div class="p-6 space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Kategori:</span>
                        <span class="font-medium">{{ $produk->kategori }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Visibilitas:</span>
                        <span class="font-medium">{{ ucfirst($produk->visibilitas) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Visibilitas:</span>
                        <span class="font-medium">{{ ucfirst($produk->visibilitas) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">View Count:</span>
                        <span class="font-medium">
                            @if($produk->view_count > 0)
                                {{ $produk->view_count }} 
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
                        <span>Tanggal Rilis:</span>
                        <span class="font-medium">{{ date('d M Y', strtotime($produk->release_date)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Tags</h3>
                </div>
                <div class="p-6">
                    @if($produk->tags->isEmpty())
                        <p class="text-gray-500 text-sm">Tidak ada tag untuk produk ini.</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($produk->tags as $tag)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $tag->nama_tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Penulis -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Penulis</h3>
                </div>
                <div class="p-6 flex items-center">
                    @php
                        $img = $produk->user->profile_pic
                            ? 'data:image/jpeg;base64,'.base64_encode($produk->user->profile_pic)
                            : 'https://via.placeholder.com/64';
                    @endphp
                    <img src="{{ $img }}" alt="Foto Profil"
                        class="w-16 h-16 rounded-full object-cover border border-gray-300">
                    <div class="ml-4">
                        <p class="font-medium text-gray-800">{{ $produk->user->nama_pengguna }}</p>
                        <p class="text-sm text-gray-500">{{ $produk->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- DearFlip CSS -->
 <link rel="stylesheet" href="{{ asset('dflip/css/dflip.min.css') }}">

 <!-- jQuery + DearFlip -->
 <script src="{{ asset('dflip/js/libs/jquery.min.js') }}"></script>
 <script src="{{ asset('dflip/js/dflip.min.js') }}"></script>

 <!-- Modal Script -->
 <script>
     document.addEventListener("DOMContentLoaded", function() {
         const previewBtn = document.getElementById("toggle-preview");
         const modal = document.getElementById("previewModal");
         const closeBtn = document.getElementById("closeModal");
         const modalContent = document.getElementById("modalContent");

         const id = previewBtn.dataset.id;
         const browseUrl = `/produk/majalah/browse?f=${id}`;
         const previewUrl = `/produk/majalah/preview?f=${id}`;

         function openModal() {
             modal.classList.remove("hidden");
             fetch(previewUrl)
                 .then(res => res.text())
                 .then(html => {
                     modalContent.innerHTML = html;
                 });
         }

         function closeModal() {
             modal.classList.add("hidden");
             modalContent.innerHTML =
                 `<div class="w-full h-full flex items-center justify-center text-gray-500">Memuat pratinjau...</div>`;
         }

         previewBtn.addEventListener("click", () => {
             history.pushState({
                 preview: true
             }, '', previewUrl);
             openModal();
         });

         closeBtn.addEventListener("click", () => {
             history.pushState(null, '', browseUrl);
             closeModal();
         });

         modal.addEventListener("click", function(e) {
             if (e.target === modal) {
                 history.pushState(null, '', browseUrl);
                 closeModal();
             }
         });

         window.addEventListener("popstate", function() {
             if (window.location.pathname.includes('/preview')) {
                 openModal();
             } else {
                 closeModal();
             }
         });

         if (window.location.pathname.includes('/preview')) {
             openModal();
         }

         // AJAX pagination
         document.addEventListener('click', function(e) {
             const target = e.target.closest('#rekomendasi-pagination a');
             if (target) {
                 e.preventDefault();
                 const url = target.href;

                 fetch(url, {
                         headers: {
                             'X-Requested-With': 'XMLHttpRequest'
                         }
                     })
                     .then(res => res.text())
                     .then(html => {
                         document.querySelector('#rekomendasi').innerHTML = html;
                     });
             }
         });
     });
 </script>
@endsection
