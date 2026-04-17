@extends('layouts.app')

@section('content')
    <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-10">
        @if ($karya->isNotEmpty())
            <h2 class="text-2xl font-bold mb-1">Karya Kami</h2>
            <p class="text-sm text-gray-500 mb-2">Kumpulan Karya Karya Terbaik</p>
            <div class="w-full h-[2px] bg-[#A8A8A8] mb-4"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($karya as $item)
                    <div class="flex flex-col">
                        <a href="{{ route('karya.desain-grafis.read', ['k' => $item->id]) }}">
                            <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                class="w-full h-[240px] object-cover rounded-lg shadow-md" />
                        </a>
                        <p class="mt-2 text-sm">
                            <span class="text-[#990505] font-bold">
                                {{ strtoupper(str_replace('_', ' ', $item->kategori)) }}
                            </span>
                            <span class="text-[#990505] font-bold"> | </span>
                            <span class="text-[#A8A8A8]">
                                {{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}
                            </span>
                        </p>
                        <a href="{{ route('karya.desain-grafis.read', ['k' => $item->id]) }}">
                            <h3 class="text-base font-bold mt-1">"{{ $item->judul }}"</h3>
                        </a>
                        <p class="text-sm text-gray-700 mb-2">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 80) }}
                        </p>
                        <div class="flex justify-between items-center text-sm text-[#ABABAB] font-semibold">
                            <span>{{ $item->user->nama_lengkap ?? '-' }}</span>
                            <div class="flex gap-3 text-xs">
                                <div class="flex items-center gap-1">
                                    <i class="fa-regular fa-thumbs-up"></i><span>{{ $item->like_count ?? 0 }}</span>
                                </div>
                                <button type="button" class="flex items-center gap-1 openShareModal"
                                    data-url="{{ route('karya.desain-grafis.read', ['k' => $item->id]) }}">
                                    <i class="fa-solid fa-share-nodes"></i>
                                    <span>Share</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="mt-6 w-full text-right col-span-full">
                    <a href="{{ route('karya.desain-grafis.semua') }}"
                        class="text-sm font-semibold text-[#9A0605] hover:underline">
                        Lihat Karya Lainnya →
                    </a>
                </div>
            </div>

            <div class="mt-6">
                {{ $karya->links() }}
            </div>
        @else
            {{-- Fallback jika tidak ada karya --}}
            <div class="flex flex-col items-center justify-center py-20 text-center text-gray-500">
                <i class="fa-solid fa-circle-exclamation text-5xl mb-4 text-[#9A0605]"></i>
                <h2 class="text-lg font-semibold">Belum ada karya yang tersedia saat ini.</h2>
                <p class="text-sm mt-1">Silakan cek kembali nanti untuk melihat karya terbaru dari kami.</p>
            </div>
        @endif
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
