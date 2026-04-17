@extends('layouts.admin-layouts')
@section('content')
<div class="container mx-auto px-1 py-1">
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-house mr-1"></i>
                <span>Home</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Produk</span>
        </nav>
    
        <!-- Title -->
        <h1 class="mt-3 text-2xl font-bold text-gray-800">Daftar Produk</h1>
    </div>    

    <div class="p-6 bg-white shadow rounded-lg">
        <!-- Controls: Show Entries, Search, Filter -->
        <div class="flex items-center justify-between mb-4">
            <!-- Show Entries -->
            <div class="relative flex items-center gap-2">
                <form method="GET" id="perPageForm" class="flex items-center gap-2">
                    <label for="perPage">Show</label>
                    <select name="perPage" id="perPage" onchange="document.getElementById('perPageForm').submit()"
                            class="py-1 pl-2 pr-7 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <div>entries</div>
                </form>
            </div>

            <!-- Search + Filter -->
            <div class="flex items-center ml-auto">
                <form method="GET" class="relative max-w-lg mr-4">
                    <input type="text" name="search" value="{{ $searchTerm }}" placeholder="Search..."
                           class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                    <input type="hidden" name="perPage" value="{{ $perPage }}">

                    <!-- Hidden inputs for preserving filters -->
                    @foreach(request()->only(['kategori', 'order', 'tanggal_dari', 'tanggal_sampai', 'status']) as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $v)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <i class="fa fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </form>

                {{-- Filter Dropdown --}}
                <div class="relative group mr-2 inline-block">
                    <form method="GET" class="relative group mr-2 inline-block">
                        <!-- Hidden inputs to preserve current state -->
                        <input type="hidden" name="perPage" value="{{ $perPage }}">
                        <input type="hidden" name="search" value="{{ $searchTerm }}">

                        <button id="filterDropdownBtn"
                                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600">
                            <i class="fa fa-filter"></i>
                            <span>Filter</span>
                            <svg class="w-2.5 h-2.5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                  viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <div id="filterDropdown"
                             class="z-10 hidden absolute right-0 mt-2 w-90 bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-4">
                            <!-- Kategori -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Kategori</p>
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                                    @foreach(['Majalah', 'Buletin'] as $kategori)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="kategori-{{ $kategori }}" name="kategori[]" value="{{ $kategori }}"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                {{ in_array($kategori, request('kategori', [])) ? 'checked' : '' }}>
                                            <label for="kategori-{{ $kategori }}"
                                                   class="ml-2 capitalize">{{ str_replace('-', ' ', $kategori) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Urutan -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Urutkan berdasarkan</p>
                                <div class="flex flex-col gap-2 text-sm text-gray-700">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="order" value="terbaru" class="text-blue-600 focus:ring-blue-500"
                                            {{ request('order') === 'terbaru' ? 'checked' : '' }}>
                                        <span class="ml-2">Terbaru</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="order" value="terlama" class="text-blue-600 focus:ring-blue-500"
                                            {{ request('order') === 'terlama' ? 'checked' : '' }}>
                                        <span class="ml-2">Terlama</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Tanggal Diterbitkan</p>
                                <div class="flex items-center gap-2">
                                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                           class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <i class="fa-solid fa-arrow-right text-gray-500 text-sm"></i>
                                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                           class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Status</p>
                                <div class="flex gap-4 text-sm text-gray-700">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status" value="public" class="text-blue-600 focus:ring-blue-500"
                                            {{ request('status') === 'public' ? 'checked' : '' }}>
                                        <span class="ml-2">Publik</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="status" value="private" class="text-blue-600 focus:ring-blue-500"
                                            {{ request('status') === 'private' ? 'checked' : '' }}>
                                        <span class="ml-2">Private</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex pt-2">
                                <div class="ml-auto">
                                    <button type="button"
                                            onclick="window.location.href='{{ route('admin.produk') }}?perPage={{ $perPage }}'"
                                            class="bg-yellow-400 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm">Kembalikan</button>
                                </div>
                                <div class="ml-2">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">Terapkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Tambahkan script jika pakai Alpine atau toggle manual -->
                <script>
                    document.getElementById('filterDropdownBtn').addEventListener('click', function (e) {
                        e.preventDefault();
                        const dropdown = document.getElementById('filterDropdown');
                        dropdown.classList.toggle('hidden');
                    });

                    // Close dropdown on outside click
                    document.addEventListener('click', function (e) {
                        const dropdown = document.getElementById('filterDropdown');
                        const btn = document.getElementById('filterDropdownBtn');
                        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });

                </script>
                </div>
            </div>
        </div>

        <!-- Produk Table -->
        <div class="overflow-x-auto">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border border-gray-200 table-auto min-w-[900px]">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-600">
                        <tr>
                            <th class="p-3">Cover</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Penulis</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Visibilitas</th>
                            <th class="p-3">Tanggal Terbit</th>
                            <th class="p-3 text-center w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($produks as $produk)
                            @php
                                $highlight = function($text) use ($searchTerm) {
                                    if (!$searchTerm) return e($text);
                                    return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', e($text));
                                };
                                $judulFormatted = wordwrap($produk->judul ?? 'N/A', 20, "<br>");
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">
                                    <canvas id="pdf-thumbnail-{{ $produk->id }}" class="w-20 h-32 object-cover rounded-lg shadow-md"></canvas>
                                    <script src="https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.min.js"></script>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const productId = "{{ $produk->id }}";
                                            const canvas = document.getElementById('pdf-thumbnail-' + productId);
                                            const pdfUrl = "{{ route('admin.pdfPreview', ['id' => $produk->id]) }}";
                                        
                                            if (!canvas) return;
                                        
                                            const ctx = canvas.getContext('2d');
                                        
                                            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                                                pdf.getPage(1).then(function(page) {
                                                    const viewport = page.getViewport({ scale: 1.5 });
                                                    canvas.width = viewport.width;
                                                    canvas.height = viewport.height;
                                        
                                                    const renderContext = {
                                                        canvasContext: ctx,
                                                        viewport: viewport
                                                    };
                                        
                                                    page.render(renderContext);
                                                });
                                            }).catch(function(error) {
                                                console.error("Error rendering PDF:", error);
                                            });
                                        });
                                    </script>
                                </td>
            
                                <td class="p-3 font-medium whitespace-normal break-words leading-snug max-w-[200px]">
                                    {!! nl2br(e($produk->judul)) !!}
                                </td>
            
                                <td class="p-3 h-16">
                                    <div class="flex items-center gap-3 h-full">
                                        @php
                                            $base64Image = $produk && $produk->user?->profile_pic 
                                                ? 'data:image/jpeg;base64,' . base64_encode($produk->user?->profile_pic ) 
                                                : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                                        @endphp
                                        <img src="{{ $base64Image }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-300">
                                        <span class="text-sm">{!! $highlight($produk->user?->nama_pengguna, $searchTerm) !!}</span>
                                    </div>
                                </td>
            
                                <td class="p-3">{{ $produk->kategori ?? 'N/A' }}</td>
                                <td class="p-3">
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full
                                        {{ $produk->visibilitas === 'public' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                        {{ ucfirst($produk->visibilitas) }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-gray-500">{{ $produk->release_date }}</td>
            
                                <td class="p-3 flex justify-center items-center space-x-2">
                                    <!-- Detail Button -->
                                    <div class="relative group">
                                        <button class="w-9 h-9 flex items-center justify-center rounded-full border border-blue-500 text-blue-500 hover:bg-blue-50 transition"
                                                onclick="window.location.href='{{ route('admin.produk.detail', $produk->id) }}'">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <div
                                            class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition">
                                            Lihat Detail
                                        </div>
                                    </div>
                                    <!-- Delete Button -->
                                    <div class="relative group">
                                        <button type="button"
                                                class="delete-btn w-9 h-9 flex items-center justify-center rounded-full border border-red-500 text-red-500 hover:bg-red-50 transition"
                                                data-id="{{ $produk->id }}" data-url="{{ route('admin.produk.delete', $produk->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition">
                                            Hapus
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <div class="flex items-center justify-between mt-4 text-sm text-gray-600">
                <div>
                    Showing {{ $produks->firstItem() }} to {{ $produks->lastItem() }} of {{ $produks->total() }} entries
                </div>
                <div class="flex space-x-1">
                    @if ($produks->onFirstPage())
                        <button class="px-3 py-1 border rounded text-gray-600 cursor-not-allowed">Previous</button>
                    @else
                        <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                                onclick="window.location.href='{{ $produks->previousPageUrl() }}'">
                            Previous
                        </button>
                    @endif

                    @for ($i = 1; $i <= $produks->lastPage(); $i++)
                        @if ($i == $produks->currentPage())
                            <button class="px-3 py-1 border rounded bg-blue-500 text-white">{{ $i }}</button>
                        @else
                            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-100"
                                    onclick="window.location.href='{{ $produks->url($i) }}'">
                                {{ $i }}
                            </button>
                        @endif
                    @endfor

                    @if ($produks->hasMorePages())
                        <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                                onclick="window.location.href='{{ $produks->nextPageUrl() }}'">
                            Next
                        </button>
                    @else
                        <button class="px-3 py-1 border rounded text-gray-600 cursor-not-allowed">Next</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms for Delete -->
@foreach ($produks as $produk)
    <form id="delete-form-{{ $produk->id }}" action="{{ route('admin.produk.delete', $produk->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<!-- SweetAlert2 + Delete Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = btn.getAttribute('data-id');
                    const form = document.getElementById('delete-form-' + id);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        // Set background color (optional)
                        confirmButtonColor: '#d33', // Still use this if needed
                        cancelButtonColor: '#3085d6',
                        // Apply Tailwind classes to buttons
                        customClass: {
                            confirmButton: 'bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded',
                            cancelButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded ml-2'
                        },
                        buttonsStyling: false // Disable default SweetAlert2 styling
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });

</script>
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000, // auto close 3 detik
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3b82f6', // warna biru Tailwind 'blue-500'
            buttonsStyling: false,
            customClass: {
                confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded'
            }
        });
    });
</script>
@endif

{{-- <!-- Highlight Function -->
@php
    $highlight = function($text, $searchTerm) {
        if (!$searchTerm) return e($text);
        return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<mark class="bg-yellow-200 px-1 rounded">$1</mark>', e($text));
    };
@endphp --}}

@endsection