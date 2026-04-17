@extends('layouts.app')

@section('content')
<main class="py-8">
    <div class="max-w-[1600px] mx-auto px-12 md:px-24 lg:px-32">
        <h1 class="text-xl font-bold mb-6 text-[#9A0605]">Semua Karya Desain Grafis</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($karya as $item)
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
                                <i class="fa-regular fa-thumbs-up"></i>
                                <span>{{ $item->like_count ?? 0 }}</span>
                            </div>
                            <button type="button" class="flex items-center gap-1 openShareModal"
                                data-url="{{ route('karya.desain-grafis.read', ['k' => $item->id]) }}">
                                <i class="fa-solid fa-share-nodes"></i>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p>Tidak ada karya tersedia.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $karya->links('pagination::tailwind') }}
        </div>
    </div>
</main>
@endsection
