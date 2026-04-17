@extends('layouts.app')

@section('content')
    <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            @if ($karya->count() || $terbaru->count() || $rekomendasi->count())
                {{-- Karya Kami --}}
                @if ($karya->isNotEmpty())
                    <div class="md:col-span-2">
                        <h2 class="text-xl md:text-2xl font-bold mb-0">Karya Kami</h2>
                        <p class="text-xs md:text-sm text-[#A8A8A8] mb-1">Kumpulan Karya Karya Terbaik</p>
                        <div class="w-full h-[2px] bg-[#A8A8A8] mb-4"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($karya as $item)
                                <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row gap-4">
                                    <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}"
                                        class="block w-full sm:w-[160px] md:w-full lg:w-[200px] h-[160px] overflow-hidden rounded-md flex-shrink-0">
                                        <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                            class="w-full h-full object-cover" />
                                    </a>
                                    <div class="flex flex-col justify-between w-full">
                                        <div class="space-y-[2px]">
                                            <p class="text-xs md:text-sm mb-1">
                                                <span class="text-[#990505] font-bold">
                                                    {{ strtoupper(str_replace('_', ' ', $item->kategori)) }} |
                                                </span>
                                                <span class="text-[#A8A8A8]">
                                                    {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                                                </span>
                                            </p>
                                            <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}">
                                                <h3 class="text-sm md:text-base font-bold">{{ $item->judul }}</h3>
                                            </a>
                                            <div
                                                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-1">
                                                <p class="text-xs text-[#ABABAB] m-0">{{ $item->user->nama_lengkap ?? '-' }}
                                                </p>
                                                <div class="flex items-center gap-4 text-[#ABABAB] text-xs">
                                                    <div class="flex items-center gap-1">
                                                        <i
                                                            class="fa-regular fa-thumbs-up"></i><span>{{ $item->like_count ?? 0 }}</span>
                                                    </div>
                                                    <button type="button" class="flex items-center gap-1 openShareModal"
                                                        data-url="{{ route('karya.fotografi.read', ['k' => $item->id]) }}">
                                                        <i class="fa-solid fa-share-nodes"></i>
                                                        <span>Share</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}"
                                                class="text-xs md:text-sm text-[#5773FF]">Lihat Gambar</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Terbaru dan Rekomendasi Hari Ini --}}
                <div class="md:col-span-1">
                    {{-- Terbaru --}}
                    @if ($terbaru->isNotEmpty())
                        <div class="flex flex-col mb-8">
                            <div class="flex items-center">
                                <div class="w-[5px] sm:w-[6px] h-[24px] sm:h-[28px] md:h-[36px] bg-[#9A0605] mr-[4px]">
                                </div>
                                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-white px-4 sm:px-6 md:px-8 py-[2px] sm:py-1 bg-[#9A0605]"
                                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                    Terbaru
                                </h2>
                            </div>
                            <div class="w-full h-[2px] bg-gray-300"></div>

                            <div class="flex flex-col gap-3 mt-4">
                                @foreach ($terbaru as $item)
                                    <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}"
                                        class="relative w-full h-[140px] sm:h-[160px] md:h-[170px] overflow-hidden rounded-lg shadow-md block">
                                        <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                            class="w-full h-full object-cover" />
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-[#990505] to-transparent opacity-90">
                                        </div>
                                        <div
                                            class="absolute bottom-0 left-0 right-0 px-3 py-1 text-white text-[11px] sm:text-[12px] md:text-[13px] font-semibold z-10">
                                            {{ $item->judul }}
                                        </div>
                                    </a>
                                @endforeach
                                <div class="mt-6 text-center">
                                    <a href="{{ route('karya.fotografi.semua') }}"
                                        class="text-sm font-semibold text-[#9A0605] hover:underline">
                                        Lihat Karya Lainnya →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Rekomendasi Hari Ini --}}
                    @if ($rekomendasi->isNotEmpty())
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div
                                    class="w-[4px] sm:w-[5px] md:w-[8px] h-[20px] sm:h-[26px] md:h-[36px] bg-[#9A0605] mr-[2px] sm:mr-[3px] md:mr-[4px]">
                                </div>
                                <h2 class="text-xs sm:text-sm md:text-lg font-semibold text-white px-3 sm:px-5 md:px-8 py-[2px] sm:py-[6px] md:py-1 bg-[#9A0605]"
                                    style="clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%)">
                                    Rekomendasi Hari Ini
                                </h2>
                            </div>
                            <div class="w-full h-[2px] bg-gray-300"></div>
                            <div class="grid grid-cols-2 gap-3 md:gap-4 mt-4">
                                @foreach ($rekomendasi as $item)
                                    <div class="flex flex-col items-start gap-1">
                                        <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}"
                                            class="block w-full overflow-hidden rounded-md shadow-md aspect-[4/3]">
                                            <img src="data:image/jpeg;base64,{{ $item->media }}"
                                                alt="{{ $item->judul }}" class="w-full h-full object-cover" />
                                        </a>
                                        <a href="{{ route('karya.fotografi.read', ['k' => $item->id]) }}">
                                            <h3 class="text-xs sm:text-sm font-bold">{{ $item->judul }}</h3>
                                        </a>
                                        <div
                                            class="flex flex-col sm:flex-row md:flex-col items-start gap-1 w-full text-[#ABABAB] text-[10px] sm:text-xs">
                                            <p class="leading-tight">{{ $item->user->nama_lengkap ?? '-' }}</p>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-1">
                                                    <i class="fa-regular fa-thumbs-up"></i>
                                                    <span>{{ $item->like_count ?? 0 }}</span>
                                                </div>
                                                <button type="button" class="flex items-center gap-1 openShareModal"
                                                    data-url="{{ route('karya.fotografi.read', ['k' => $item->id]) }}">
                                                    <i class="fa-solid fa-share-nodes"></i>
                                                    <span>Share</span>
                                                </button>
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
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center text-gray-500">
                    <i class="fa-solid fa-circle-exclamation text-5xl mb-4 text-[#9A0605]"></i>
                    <h2 class="text-lg font-semibold">Tidak ada karya yang tersedia saat ini.</h2>
                    <p class="text-sm mt-1">Silakan cek kembali nanti untuk melihat karya terbaru.</p>
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
