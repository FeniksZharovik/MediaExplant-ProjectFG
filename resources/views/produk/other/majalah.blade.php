@extends('layouts.app')

@section('content')
<main class="py-8">
    <div class="max-w-[1600px] mx-auto px-12 md:px-24 lg:px-32">
        <h1 class="text-xl font-bold mb-6 text-[#9A0605]">Semua Majalah</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($majalahs as $majalah)
                <div>
                    <a href="{{ route('majalah.browse', ['f' => $majalah->id]) }}" class="block">
                        <div class="relative rounded-lg overflow-hidden shadow-md mb-4">
                            <img src="{{ $majalah->cover }}" alt="Thumbnail {{ $majalah->judul }}"
                                class="w-full h-96 object-cover" />
                        </div>
                    </a>

                    <p class="text-sm font-semibold flex items-center text-[#990505]">
                        <span>MAJALAH</span> <span class="mx-1">|</span>
                        <span class="text-[#ABABAB]">
                            {{ \Carbon\Carbon::parse($majalah->release_date)->translatedFormat('d M Y') }}
                        </span>
                    </p>

                    <a href="{{ route('majalah.browse', ['f' => $majalah->id]) }}" class="block">
                        <h3 class="text-lg font-semibold mt-1">{{ $majalah->judul }}</h3>
                    </a>

                    <div class="flex items-center mt-1">
                        <i class="fa-solid fa-download text-[#5773FF] mr-2"></i>
                        <a href="{{ route('majalah.browse', ['f' => $majalah->id]) }}"
                            class="text-[#5773FF] text-lg font-medium">Lihat Majalah</a>
                    </div>
                </div>
            @empty
                <p>Tidak ada majalah yang tersedia.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $majalahs->links('pagination::tailwind') }}
        </div>
    </div>
</main>
@endsection
