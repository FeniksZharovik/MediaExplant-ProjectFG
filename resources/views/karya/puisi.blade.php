@extends('layouts.app')

@section('content')
    <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @if ($terbaru->count() || $karya->count())

                {{-- TERBARU --}}
                @if ($terbaru->isNotEmpty())
                    <div class="md:col-span-1">
                        <div class="flex flex-col mb-8">
                            <div class="flex items-center">
                                <div class="w-[5px] sm:w-[6px] h-[24px] sm:h-[28px] md:h-[36px] bg-[#9A0605] mr-[4px]"></div>
                                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-white px-4 sm:px-6 md:px-8 py-[2px] sm:py-1 bg-[#9A0605]"
                                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                    Terbaru
                                </h2>
                            </div>
                            <div class="w-full h-[2px] bg-gray-300 mb-4"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach ($terbaru as $item)
                                <div class="flex flex-col items-start w-full">
                                    <a href="{{ route('karya.puisi.read', ['k' => $item->id]) }}" class="w-full">
                                        <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                            class="w-full aspect-square lg:w-[280px] lg:h-[240px] object-cover object-center rounded-lg shadow-md" />
                                    </a>
                                    <span class="text-[#990505] font-bold text-xs sm:text-sm mt-2 text-left w-full">
                                        {{ strtoupper(str_replace('_', ' ', $item->kategori)) }} |
                                    </span>
                                    <span class="text-[#A8A8A8] text-xs sm:text-sm text-left w-full">
                                        {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                                    </span>
                                    <a href="{{ route('karya.puisi.read', ['k' => $item->id]) }}" class="w-full">
                                        <h3 class="text-sm sm:text-base font-bold mb-1 mt-1 text-left">{{ $item->judul }}
                                        </h3>
                                    </a>
                                    <p class="text-xs sm:text-sm text-gray-700 text-left w-full">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 50) }}
                                    </p>
                                </div>
                            @endforeach
                            <div class="mt-6 w-full flex justify-center col-span-full">
                                <a href="{{ route('karya.puisi.semua') }}"
                                    class="text-sm font-semibold text-[#9A0605] hover:underline">
                                    Lihat Karya Lainnya →
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- KARYA KAMI --}}
                @if ($karya->isNotEmpty())
                    <div class="md:col-span-2 -mt-2">
                        <h2 class="text-xl md:text-2xl font-bold mb-1">Karya Kami</h2>
                        <p class="text-xs md:text-sm text-gray-500 mb-2">Kumpulan Karya Karya Terbaik</p>
                        <div class="w-full h-[2px] bg-[#A8A8A8] mb-4"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($karya as $item)
                                <div class="flex flex-col gap-4 lg:flex-row">
                                    <a href="{{ route('karya.puisi.read', ['k' => $item->id]) }}">
                                        <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                            class="w-[100%] h-[200px] sm:w-[100%] sm:h-[200px] md:w-[100%] md:h-[240px] lg:w-[280px] lg:h-[240px] object-cover object-center rounded-lg shadow-md" />
                                    </a>
                                    <div class="flex flex-col justify-between text-left w-full">
                                        <div>
                                            <p class="text-xs sm:text-sm mb-1 mt-1 md:mt-2">
                                                <span class="text-[#990505] font-bold">
                                                    {{ strtoupper(str_replace('_', ' ', $item->kategori)) }} |
                                                </span>
                                                <span class="text-[#A8A8A8]">
                                                    {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                                                </span>
                                            </p>
                                            <a href="{{ route('karya.puisi.read', ['k' => $item->id]) }}">
                                                <h3 class="text-sm sm:text-base font-bold mb-1">{{ $item->judul }}</h3>
                                            </a>
                                            <p class="text-xs sm:text-sm text-gray-700 mb-2">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 80) }}
                                            </p>
                                            <div class="flex justify-between items-center">
                                                <p class="text-xs sm:text-sm text-[#ABABAB] font-semibold">
                                                    {{ $item->user->nama_lengkap ?? '-' }}
                                                </p>
                                                <div class="flex gap-3 text-[#ABABAB] text-xs">
                                                    <div class="flex items-center gap-1">
                                                        <i class="fa-regular fa-thumbs-up"></i>
                                                        <span>{{ $item->like_count ?? 0 }}</span>
                                                    </div>
                                                    <button type="button" class="flex items-center gap-1 openShareModal"
                                                        data-url="{{ route('karya.puisi.read', ['k' => $item->id]) }}">
                                                        <i class="fa-solid fa-share-nodes"></i>
                                                        <span>Share</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                {{-- Fallback jika tidak ada data --}}
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center text-gray-500">
                    <i class="fa-solid fa-circle-exclamation text-5xl mb-4 text-[#9A0605]"></i>
                    <h2 class="text-lg font-semibold">Tidak ada karya puisi yang tersedia.</h2>
                    <p class="text-sm mt-1">Silakan kembali lagi nanti untuk melihat karya terbaru.</p>
                </div>
            @endif
        </div>
    </div>
    @include('karya.components.share-modal')
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
