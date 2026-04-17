@extends('layouts.app')

@section('content')
    <main class="py-8">
        <div class="max-w-[1600px] mx-auto px-12 md:px-24 lg:px-32">

            @if ($terbaru->count() || $rekomendasi->count() || $terpopuler->count())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    {{-- KIRI: TERBARU & REKOMENDASI --}}
                    <div class="md:col-span-1">
                        {{-- TERBARU --}}
                        @if ($terbaru->count())
                            <div class="flex flex-col mb-8">
                                <div class="flex items-center">
                                    <div class="w-[5px] sm:w-[6px] h-[24px] sm:h-[28px] md:h-[36px] bg-[#9A0605] mr-[4px]">
                                    </div>
                                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-white px-4 sm:px-6 md:px-8 py-[2px] sm:py-1 bg-[#9A0605]"
                                        style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                        Terbaru
                                    </h2>
                                </div>
                                <div class="w-full h-[1px] sm:h-[2px] bg-gray-300 mb-4"></div>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 gap-3">
                                @foreach ($terbaru as $item)
                                    <div>
                                        <a href="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                            <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                                class="w-full h-24 sm:h-28 md:h-32 object-cover mb-1 rounded">
                                        </a>
                                        <a href="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                            <h3 class="text-xs sm:text-[13px] font-semibold leading-tight">
                                                {{ Str::limit($item->judul, 40) }}</h3>
                                        </a>
                                        <div
                                            class="flex flex-wrap items-center justify-start gap-2 sm:gap-3 mt-1 text-[10px] sm:text-[11px] text-[#ABABAB] font-semibold">
                                            <span>{{ $item->user->nama_lengkap ?? '-' }}</span>
                                            <div class="flex gap-2 text-xs">
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-regular fa-thumbs-up"></i>
                                                    <span>{{ $item->like_count ?? 0 }}</span>
                                                </div>
                                                <button class="flex items-center gap-1 openShareModal"
                                                    data-url="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                                    <i class="fa-solid fa-share-nodes"></i>
                                                    <span>Share</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Tombol lihat berita lainnya --}}
                            <div class="mt-4 text-center">
                                <a href="{{ route('kesehatan.semua') }}"
                                    class="text-sm font-semibold text-[#9A0605] hover:underline">
                                    Lihat Berita Lainnya →
                                </a>
                            </div>
                        @endif

                        {{-- REKOMENDASI --}}
                        @if ($rekomendasi->count())
                            <div class="flex flex-col mt-8 mb-4">
                                <div class="flex items-center">
                                    <div class="w-[5px] sm:w-[6px] h-[24px] sm:h-[28px] md:h-[36px] bg-[#9A0605] mr-[4px]">
                                    </div>
                                    <h2 class="text-sm sm:text-base md:text-lg font-semibold text-white px-4 sm:px-6 md:px-8 py-[2px] sm:py-1 bg-[#9A0605]"
                                        style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                        Rekomendasi Berita
                                    </h2>
                                </div>
                                <div class="w-full h-[1px] sm:h-[2px] bg-gray-300 mb-4"></div>
                            </div>

                            <div class="flex flex-col gap-4">
                                @foreach ($rekomendasi as $item)
                                    <div>
                                        <a href="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                            <h3 class="text-sm sm:text-[15px] font-semibold leading-tight">
                                                {{ Str::limit($item->judul, 50) }}</h3>
                                        </a>
                                        <div
                                            class="flex flex-wrap items-center justify-start gap-2 sm:gap-3 mt-1 text-[10px] sm:text-[11px] text-[#ABABAB] font-semibold">
                                            <span>{{ $item->user->nama_lengkap ?? '-' }}</span>
                                            <div class="flex gap-2 text-xs">
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-regular fa-thumbs-up"></i>
                                                    <span>{{ $item->like_count ?? 0 }}</span>
                                                </div>
                                                <button class="flex items-center gap-1 openShareModal"
                                                    data-url="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                                    <i class="fa-solid fa-share-nodes"></i>
                                                    <span>Share</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- KANAN: TERPOPULER --}}
                    @if ($terpopuler->count())
                        <div class="md:col-span-2 -mt-2">
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-1">Berita</h2>
                            <p class="text-sm text-gray-500 mb-2">Kumpulan Berita Terbaik</p>
                            <div class="w-full h-[1px] sm:h-[2px] bg-[#A8A8A8] mb-4"></div>

                            <div class="flex flex-col gap-6">
                                @foreach ($terpopuler as $item)
                                    <div class="flex flex-col md:flex-col lg:flex-row gap-4">
                                        <a href="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                            <div class="w-full lg:w-52 h-32 sm:h-36 overflow-hidden rounded">
                                                <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        </a>
                                        <div class="flex-1">
                                            <div
                                                class="flex flex-wrap items-center text-[11px] sm:text-xs font-semibold mb-1">
                                                <span class="text-[#990505]">{{ strtoupper($item->kategori) }}</span>
                                                <span class="mx-2 text-[#990505]">|</span>
                                                <span
                                                    class="text-[#A8A8A8]">{{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}</span>
                                            </div>
                                            <a href="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                                <h3 class="text-base sm:text-lg font-bold mb-1">{{ $item->judul }}</h3>
                                            </a>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ Str::limit(strip_tags(str_replace('&nbsp;', ' ', $item->konten_berita)), 150) }}
                                            </p>
                                            <div
                                                class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-[13px] text-[#ABABAB] font-semibold">
                                                <span>{{ $item->user->nama_lengkap ?? '-' }}</span>
                                                <div class="flex gap-2 text-xs">
                                                    <div class="flex items-center gap-1">
                                                        <i class="fa-regular fa-thumbs-up"></i>
                                                        <span>{{ $item->like_count ?? 0 }}</span>
                                                    </div>
                                                    <button class="flex items-center gap-1 openShareModal"
                                                        data-url="{{ route('kesehatan.detail', ['a' => $item->id]) }}">
                                                        <i class="fa-solid fa-share-nodes"></i>
                                                        <span>Share</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                {{-- Fallback jika semua kosong --}}
                <div class="flex flex-col items-center justify-center py-20 text-center text-gray-500">
                    <i class="fa-solid fa-circle-exclamation text-5xl mb-4 text-[#9A0605]"></i>
                    <h2 class="text-lg font-semibold">Tidak ada konten yang tersedia saat ini.</h2>
                    <p class="text-sm mt-1">Silakan cek kembali nanti untuk konten terbaru.</p>
                </div>
            @endif

        </div>
    </main>
    @include('kategori.components.share-modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareModal = document.getElementById('shareModal');
            const closeShareModal = document.getElementById('closeShareModal');
            const copyLinkBtn = document.getElementById('copyLink');
            const shareLinkInput = document.getElementById('shareLink');
            const iconContainer = document.getElementById('iconContainer');
            const slideLeftBtn = document.getElementById('slideLeft');
            const slideRightBtn = document.getElementById('slideRight');

            document.querySelectorAll('.openShareModal').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const url = button.dataset.url;
                    shareLinkInput.value = url;

                    iconContainer.querySelectorAll('a').forEach(link => {
                        const baseHref = link.dataset.base;
                        if (baseHref) {
                            link.href = baseHref + encodeURIComponent(url);
                        }
                    });

                    shareModal.classList.remove('hidden');
                });
            });

            closeShareModal.addEventListener('click', () => {
                shareModal.classList.add('hidden');
            });

            shareModal.addEventListener('click', (e) => {
                if (e.target === shareModal) {
                    shareModal.classList.add('hidden');
                }
            });

            copyLinkBtn.addEventListener('click', () => {
                shareLinkInput.select();
                document.execCommand('copy');
                copyLinkBtn.textContent = 'Disalin!';
                setTimeout(() => {
                    copyLinkBtn.textContent = 'Salin';
                }, 2000);
            });

            slideLeftBtn?.addEventListener('click', () => {
                iconContainer.scrollBy({
                    left: -150,
                    behavior: 'smooth'
                });
            });

            slideRightBtn?.addEventListener('click', () => {
                iconContainer.scrollBy({
                    left: 150,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
