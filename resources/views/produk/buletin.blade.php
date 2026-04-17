@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 lg:px-16 xl:px-24 2xl:px-32 py-6 max-w-screen-2xl">
        <!-- Bagian Produk Kami & Terbaru dalam Satu Baris -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Produk Kami --}}
            @if ($buletins->isNotEmpty())
                <div>
                    <h1 class="text-3xl font-semibold">Produk Kami</h1>
                    <p class="text-gray-600 text-lg">Kumpulan Produk Terbaik</p>
                    <div class="w-full h-[2px] bg-gray-300 my-6"></div>

                    <div class="grid grid-cols-1 gap-10 mt-6">
                        @foreach ($buletins as $buletin)
                            <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}"
                                class="relative block rounded-lg overflow-hidden shadow-md">
                                <img src="{{ $buletin->cover }}" alt="Thumbnail {{ $buletin->judul }}"
                                    class="w-full h-96 object-cover" />

                                <div class="absolute inset-0 bg-gradient-to-t from-[#990505] to-transparent opacity-90">
                                </div>

                                <div class="absolute bottom-0 left-0 p-4 text-white w-full">
                                    <p class="text-sm font-medium flex items-center gap-2">
                                        <span>BULETIN</span> |
                                        <span>{{ \Carbon\Carbon::parse($buletin->release_date)->translatedFormat('d M Y') }}</span>
                                    </p>

                                    <h2 class="text-lg font-semibold mt-1">{{ $buletin->judul }}</h2>

                                    <p class="text-sm mt-1 line-clamp-2">
                                        {{ strip_tags(str_replace('&nbsp;', ' ', $buletin->deskripsi)) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Terbaru --}}
            @if ($buletinsTerbaru->isNotEmpty())
                <div class="mt-6">
                    <div class="flex flex-col mb-8">
                        <div class="flex items-center">
                            <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                            <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] flex items-center justify-center text-center"
                                style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                Terbaru
                            </h2>
                        </div>
                        <div class="w-full h-[2px] bg-gray-300"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($buletinsTerbaru as $buletin)
                            <div>
                                <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}" class="block">
                                    <div class="relative rounded-lg overflow-hidden shadow-md mb-4">
                                        <img src="{{ $buletin->cover }}" alt="Thumbnail {{ $buletin->judul }}"
                                            class="w-full h-96 object-cover" />
                                    </div>
                                </a>

                                <p class="text-sm font-semibold flex items-center text-[#990505]">
                                    <span>BULETIN</span> <span class="mx-1">|</span>
                                    <span class="text-[#ABABAB]">
                                        {{ \Carbon\Carbon::parse($buletin->release_date)->translatedFormat('d M Y') }}
                                    </span>
                                </p>

                                <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}" class="block">
                                    <h3 class="text-lg font-semibold mt-1">{{ $buletin->judul }}</h3>
                                </a>

                                <div class="flex items-center mt-1">
                                    <i class="fa-solid fa-download text-[#5773FF] mr-2"></i>
                                    <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}"
                                        class="text-[#5773FF] text-lg font-medium">Lihat Buletin</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('buletin.semua') }}"
                            class="text-sm font-semibold text-[#9A0605] hover:underline">
                            Lihat Buletin Lainnya →
                        </a>
                    </div>
                </div>
            @endif
        </section>

        {{-- Rekomendasi Hari Ini --}}
        @if ($buletinsRekomendasi->isNotEmpty())
            <section class="mt-12">
                <div class="flex flex-col mb-6">
                    <div class="flex items-center">
                        <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                        <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] flex items-center justify-center text-center"
                            style="clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%)">
                            Rekomendasi Hari Ini
                        </h2>
                    </div>
                    <div class="w-full h-[2px] bg-gray-300"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    @foreach ($buletinsRekomendasi as $buletin)
                        <div>
                            <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}" class="block">
                                <div class="relative rounded-lg overflow-hidden shadow-md mb-2">
                                    <img src="{{ $buletin->cover }}" alt="Thumbnail {{ $buletin->judul }}"
                                        class="w-full h-96 object-cover" />
                                </div>
                            </a>

                            <p class="text-sm font-semibold flex items-center">
                                <span class="text-[#990505]">BULETIN</span>
                                <span class="mx-1 text-[#990505]">|</span>
                                <span class="text-[#ABABAB]">
                                    {{ \Carbon\Carbon::parse($buletin->release_date)->translatedFormat('d M Y') }}
                                </span>
                            </p>

                            <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}" class="block">
                                <h3 class="text-lg font-semibold mt-1">{{ $buletin->judul }}</h3>
                            </a>

                            <div class="flex items-center mt-1">
                                <i class="fa-solid fa-download text-[#5773FF] mr-2"></i>
                                <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}"
                                    class="text-[#5773FF] text-lg font-medium">Unduh Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Fallback jika semuanya kosong --}}
        @if ($buletins->isEmpty() && $buletinsTerbaru->isEmpty() && $buletinsRekomendasi->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center text-gray-500">
                <i class="fa-solid fa-circle-exclamation text-5xl mb-4 text-[#9A0605]"></i>
                <h2 class="text-lg font-semibold">Tidak ada konten yang tersedia saat ini.</h2>
                <p class="text-sm mt-1">Silakan cek kembali nanti untuk konten terbaru.</p>
            </div>
        @endif
    </div>
@endsection
