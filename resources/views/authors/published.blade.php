@extends('layouts.app')

@section('content')
    <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-10">
        <div class="flex justify-between items-center pb-2 border-b border-black mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Publikasi Konten</h1>
                <p class="text-sm text-gray-500 italic">Kumpulan konten yang sudah dipublikasikan</p>
            </div>

            <div class="flex items-center space-x-2 relative z-30">
                <form method="GET" action="{{ route('published-media') }}" class="relative z-30">
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
                        class="bg-white border border-gray-300 px-3 py-2 rounded-full w-64 focus:outline-none focus:ring-1 focus:ring-gray-400 pr-10 text-sm">
                    <span class="absolute right-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                        </svg>
                    </span>
                </form>

                <div class="relative z-30">
                    @php
                        $sortText = match (request('sort')) {
                            'asc' => 'A-Z Judul',
                            'desc' => 'Z-A Judul',
                            default => 'Terbaru',
                        };
                    @endphp

                    <button onclick="toggleDropdown('sortDropdown')"
                        class="flex items-center space-x-1 bg-red-700 text-white px-4 py-2 rounded-full text-sm shadow-sm hover:bg-red-800 focus:outline-none">
                        <span>{{ $sortText }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="sortDropdown"
                        class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-md hidden z-50">
                        <a href="{{ route('published-media', ['sort' => 'asc']) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-100">A-Z Judul</a>
                        <a href="{{ route('published-media', ['sort' => 'desc']) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-100">Z-A Judul</a>
                        <a href="{{ route('published-media', ['sort' => 'recent']) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-100">Terbaru</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-y-scroll h-[75vh] pr-2 space-y-4 relative z-0">
            @forelse ($berita as $item)
                <div class="flex items-start space-x-4 bg-white p-4 rounded shadow-sm relative z-10">
                    <img src="{{ $item['thumbnail'] }}" alt="Thumbnail" class="w-28 h-20 object-cover rounded">
                    <div class="flex-1">
                        <p class="text-xs font-semibold">
                            <span class="text-[#990505]">{{ strtoupper(str_replace('_', ' ', $item['kategori'])) }}</span>
                            <span class="text-[#990505] mx-1">|</span>
                            <span class="text-[#A8A8A8] font-normal">Dibuat
                                {{ \Carbon\Carbon::parse($item['tanggal_dibuat'])->translatedFormat('d F Y') }}</span>
                        </p>
                        <p class="font-medium">{{ $item['judul'] }}</p>
                        <p class="text-xs text-[#A8A8A8] mt-1">Dipublikasikan {{ $item['published_ago'] }}</p>
                    </div>
                    <div class="relative z-40">
                        <button onclick="toggleDropdown('menu-{{ $item['id'] }}')"
                            class="text-black text-2xl font-bold focus:outline-none">&#8942;</button>
                        <div id="menu-{{ $item['id'] }}"
                            class="absolute right-0 mt-2 w-24 bg-white border border-gray-200 rounded shadow-md hidden z-50">
                            <a href="#" class="block px-3 py-2 hover:bg-gray-100 text-sm edit-button"
                                data-id="{{ $item['id'] }}" data-type="{{ $item['tipe'] }}">Edit</a>
                            <button
                                onclick="openModal('{{ route('published.destroy', [$item['id'], 'tipe' => $item['tipe']]) }}')"
                                class="block w-full text-left px-3 py-2 hover:bg-gray-100 text-red-500 text-sm">Hapus</button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Tidak ada publikasi yang tersedia.</p>
            @endforelse
            @if ($paginate)
                <div class="mt-4">
                    {{ $berita->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity duration-300 ease-out">
        <div id="modalContent"
            class="mx-auto mt-40 bg-white rounded-2xl shadow-xl p-6 w-96 transform scale-95 opacity-0 transition-all duration-300 ease-out">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h2>
                <button onclick="closeModal()"
                    class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
            </div>
            <div class="mt-5 flex items-center justify-center">
                <svg class="w-14 h-14 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </div>
            <p class="text-center mt-4 text-gray-600">Apakah Anda yakin ingin menghapus item yang ini?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.edit-button');

            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const type = this.dataset.type;

                    switch (type) {
                        case 'berita':
                            targetUrl = `/authors/edit/create-edit/${this.dataset.id}`;
                            break;
                        case 'produk':
                            targetUrl = `/authors/edit/createProduct-edit/${this.dataset.id}`;
                            break;
                        case 'karya':
                            targetUrl = `/authors/edit/creation-edit/${this.dataset.id}`;
                            break;
                        default:
                            alert('Tipe tidak dikenali.');
                            return;
                    }

                    window.location.href = targetUrl;
                });
            });
        });

        function toggleDropdown(id) {
            document.querySelectorAll('[id^="menu-"], #sortDropdown').forEach(el => {
                if (el.id !== id) el.classList.add('hidden');
            });
            const dropdown = document.getElementById(id);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', function(e) {
            const isDropdownClick = e.target.closest(
                '[id^="menu-"], #sortDropdown, button[onclick^="toggleDropdown"]');
            if (!isDropdownClick) {
                document.querySelectorAll('[id^="menu-"], #sortDropdown').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });

        document.addEventListener('click', function(e) {
            const clickedInsideDropdown = e.target.closest(
                '[id^="menu-"], #sortDropdown, [onclick^="toggleDropdown"], #deleteModal');
            if (!clickedInsideDropdown) {
                document.querySelectorAll('[id^="menu-"], #sortDropdown').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });

        function openModal(actionUrl) {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('modalContent');
            document.getElementById('deleteForm').action = actionUrl;

            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('modalContent');

            content.classList.remove('opacity-100', 'scale-100');
            content.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        document.addEventListener('click', function(e) {
            const clickedInsideDropdown = e.target.closest(
                '[id^="menu-"], #sortDropdown, [onclick^="toggleDropdown"], #deleteModal > *');
            if (!clickedInsideDropdown) {
                document.querySelectorAll('[id^="menu-"], #sortDropdown').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.id === 'deleteModal') {
                closeModal();
            }
        });
    </script>
@endsection
