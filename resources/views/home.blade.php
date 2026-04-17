@extends('layouts.app')

@section('content')
    <!-- SLIDER BERITA TERBARU -->
    <section class="mt-8 mb-10">
        <div class="max-w-7xl mx-auto px-5">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($sliderNews as $item)
                        <div class="swiper-slide relative">
                            <a href="{{ $item->article_url }}" class="block relative">
                                <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                    class="w-full h-96 object-cover rounded-lg">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#990505] via-transparent to-transparent opacity-80 rounded-lg">
                                </div>
                                <div class="absolute bottom-0 p-6 text-white">
                                    <div class="flex items-center gap-2 mb-2 text-xs font-semibold uppercase">
                                        {{ strtoupper($item->kategori) }}
                                        <div class="w-[2px] h-3.5 bg-white"></div>
                                        <span class="text-gray-100">
                                            {{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h3 class="text-2xl font-bold leading-snug">
                                        {{ $item->judul }}
                                    </h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination bullet -->
                <div class="swiper-pagination mt-4"></div>
            </div>
        </div>
    </section>

    @if ($newsList->isNotEmpty())
        <section class="mt-6 mb-6 pt-4 px-5">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-semibold mb-2">Berita Teratas</h2>
                <p class="text-sm text-gray-600 mb-6">Kumpulan Berita Terbaik</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4">
                    @foreach ($newsList as $index => $item)
                        @php
                            $isHeadline = $index <= 2;
                            $isSecondRow = $index >= 3;
                        @endphp

                        @if ($isHeadline)
                            <div class="col-span-12 sm:col-span-1 lg:col-span-4 relative">
                                <a href="{{ $item->article_url }}" class="block relative">
                                    <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                        class="w-full h-80 object-cover rounded-lg">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-[#990505] via-transparent to-transparent opacity-80 rounded-lg">
                                    </div>
                                    <div class="absolute bottom-0 p-4 text-white">
                                        <div class="flex items-center gap-2 mb-1 text-xs font-semibold uppercase">
                                            {{ strtoupper($item->kategori) }}
                                            <div class="w-[2px] h-3.5 bg-white"></div>
                                            <span
                                                class="text-gray-100">{{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold leading-snug">
                                            {{ $item->judul }}
                                        </h3>
                                    </div>
                                </a>
                            </div>
                        @elseif($isSecondRow)
                            <div class="col-span-12 sm:col-span-1 lg:col-span-3">
                                <a href="{{ $item->article_url }}">
                                    <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                        class="w-full h-40 object-cover rounded-lg">
                                </a>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="text-xs font-semibold uppercase text-[#990505]">
                                        {{ strtoupper($item->kategori) }}</div>
                                    <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}</div>
                                </div>
                                <h3 class="text-sm font-bold leading-snug mt-1 line-clamp-2">
                                    <a href="{{ $item->article_url }}">{{ $item->judul }}</a>
                                </h3>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Bagian Majalah -->
    @if ($majalahList->isNotEmpty())
        <section class="mt-12 mb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Judul Atas -->
                <div class="mb-6">
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">Produk Kami</h1>
                    <p class="text-sm md:text-base text-gray-700">Kumpulan Produk Terbaik</p>
                    <div class="w-full h-[1px] bg-black"></div>
                </div>

                <!-- Heading Majalah -->
                <div class="flex flex-col mb-6">
                    <div class="flex items-center">
                        <div class="w-2 h-9 bg-[#9A0605] mr-1"></div>
                        <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] text-center"
                            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                            Majalah
                        </h2>
                    </div>
                    <div class="w-full h-[2px] bg-gray-300"></div>
                </div>

                <!-- Grid Majalah -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-6">
                    @foreach ($majalahList as $majalah)
                        <div class="flex flex-col">
                            <a href="{{ route('majalah.browse', ['f' => $majalah->id]) }}">
                                <div class="aspect-[3/4] w-full rounded-lg overflow-hidden shadow-md">
                                    <img src="{{ $majalah->cover }}" alt="Cover {{ $majalah->judul }}"
                                        class="w-full h-full object-cover rounded-lg shadow-md" />
                                </div>
                            </a>

                            <div class="mt-3 text-sm text-gray-700">
                                <div class="flex items-center space-x-2 text-xs mb-1">
                                    <span class="text-[#990505] font-semibold uppercase">MAJALAH</span>
                                    <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                    <span>{{ \Carbon\Carbon::parse($majalah->release_date)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="text-base font-semibold leading-tight mb-1">{{ $majalah->judul }}</h3>
                                <a href="{{ route('majalah.browse', ['f' => $majalah->id]) }}"
                                    class="text-[#5773FF] font-medium text-sm">Lihat Majalah</a>
                            </div>
                        </div>
                    @endforeach

                    @if ($majalahList->count() > 5)
                        <div class="col-span-full flex justify-end">
                            <a href="{{ url('/produk/majalah') }}" class="text-red-700 font-semibold text-sm">Selengkapnya
                                >></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Bagian Buletin -->
    @if ($buletinList->isNotEmpty())
        <section class="mt-16 mb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col mb-6">
                    <div class="flex items-center">
                        <div class="w-2 h-9 bg-[#9A0605] mr-1"></div>
                        <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] text-center"
                            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                            Buletin
                        </h2>
                    </div>
                    <div class="w-full h-[2px] bg-gray-300"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-6">
                    @foreach ($buletinList as $buletin)
                        <div class="flex flex-col">
                            <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}">
                                <div class="aspect-[3/4] w-full rounded-lg overflow-hidden shadow-md">
                                    <img src="{{ $buletin->cover }}" alt="Cover {{ $buletin->judul }}"
                                        class="w-full h-full object-cover" />
                                </div>
                            </a>

                            <div class="mt-3 text-sm text-gray-700">
                                <div class="flex items-center space-x-2 text-xs mb-1">
                                    <span class="text-[#990505] font-semibold uppercase">BULETIN</span>
                                    <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                    <span>{{ \Carbon\Carbon::parse($buletin->release_date)->setTimezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="text-base font-semibold leading-tight mb-1">{{ $buletin->judul }}</h3>
                                <a href="{{ route('buletin.browse', ['f' => $buletin->id]) }}"
                                    class="text-[#5773FF] font-medium text-sm">Lihat Buletin</a>
                            </div>
                        </div>
                    @endforeach

                    @if ($buletinList->count() > 5)
                        <div class="col-span-full flex justify-end">
                            <a href="{{ url('/produk/buletin') }}" class="text-red-700 font-semibold text-sm">Selengkapnya
                                >></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Komponen Karya (Bisa untuk Puisi, Pantun, Syair) -->
    @php
        $sections = [
            ['title' => 'Puisi', 'list' => $puisiList, 'route' => 'karya.puisi.read', 'url' => '/karya/puisi'],
            ['title' => 'Pantun', 'list' => $pantunList, 'route' => 'karya.pantun.read', 'url' => '/karya/pantun'],
            ['title' => 'Syair', 'list' => $syairList, 'route' => 'karya.syair.read', 'url' => '/karya/syair'],
        ];
    @endphp

    @foreach ($sections as $section)
        @if ($section['list']->isNotEmpty())
            <section class="mt-12 mb-12 {{ $loop->first ? 'mb-12' : '' }}">
                <div class="max-w-7xl mx-auto px-5">
                    @if ($loop->first)
                        <!-- Judul Utama -->
                        <div class="mb-6">
                            <h1 class="text-xl md:text-2xl font-bold text-gray-900">Karya Kami</h1>
                            <p class="text-sm md:text-base text-gray-700">Kumpulan Karya Terbaik</p>
                            <div class="w-full h-[1px] bg-black"></div>
                        </div>
                    @endif

                    <!-- Subjudul -->
                    <div class="flex flex-col mb-6">
                        <div class="flex items-center">
                            <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                            <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605]"
                                style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                                {{ $section['title'] }}
                            </h2>
                        </div>
                        <div class="w-full h-[2px] bg-gray-300"></div>
                    </div>

                    <!-- Grid Konten -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-6">
                        @foreach ($section['list'] as $item)
                            <div class="flex flex-col h-full">
                                <a href="{{ route($section['route'], ['k' => $item->id]) }}" class="w-full">
                                    <img src="data:image/jpeg;base64,{{ $item->media }}" alt="{{ $item->judul }}"
                                        class="w-full h-64 object-cover rounded-lg shadow-md" />
                                </a>
                                <div class="mt-3 text-sm text-gray-700 flex flex-col justify-between h-full">
                                    <div>
                                        <div class="flex items-center space-x-2 text-xs mb-1">
                                            <span
                                                class="text-[#990505] font-semibold uppercase">{{ $section['title'] }}</span>
                                            <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                            <span>{{ \Carbon\Carbon::parse($item->release_date)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-base font-semibold leading-tight mb-1">{{ $item->judul }}</h3>
                                    </div>
                                    <div class="text-xs italic font-medium text-gray-800">
                                        <span>Oleh : {{ $item->creator ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($section['list']->count() > 5)
                            <div class="col-span-full flex justify-end items-end w-full">
                                <a href="{{ url($section['url']) }}"
                                    class="text-red-700 font-semibold text-sm">Selengkapnya
                                    >></a>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    <!-- Bagian Fotografi -->
    @if ($fotografiList->isNotEmpty())
        <section class="mt-12 mb-12">
            <div class="max-w-7xl mx-auto px-5">
                <div class="flex flex-col mb-6">
                    <div class="flex items-center">
                        <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                        <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] flex items-center justify-center text-center"
                            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                            Fotografi
                        </h2>
                    </div>
                    <div class="w-full h-[2px] bg-gray-300"></div>
                </div>

                <div class="grid grid-cols-12 gap-4">
                    @foreach ($fotografiList as $index => $fotografi)
                        @php
                            $isHighlight = $index <= 2;
                        @endphp

                        @if ($isHighlight)
                            <div class="col-span-12 md:col-span-4 relative">
                                <a href="{{ route('karya.fotografi.read', ['k' => $fotografi->id]) }}"
                                    class="block relative">
                                    <img src="data:image/jpeg;base64,{{ $fotografi->media }}"
                                        alt="{{ $fotografi->judul }}"
                                        class="w-full h-80 object-cover rounded-lg aspect-[4/3]" />
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-[#990505] via-transparent to-transparent opacity-80 rounded-lg">
                                    </div>
                                    <div class="absolute bottom-0 p-4 text-white">
                                        <div class="flex items-center gap-2 mb-1 text-xs font-semibold uppercase">
                                            FOTOGRAFI
                                            <div class="w-[2px] h-3.5 bg-white"></div>
                                            <span>{{ \Carbon\Carbon::parse($fotografi->release_date)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold leading-snug">
                                            {{ $fotografi->judul }}
                                        </h3>
                                        <div class="text-xs italic font-medium">
                                            Oleh: {{ $fotografi->creator ?? '-' }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="col-span-6 md:col-span-4 lg:col-span-2 flex flex-col items-start">
                                <a href="{{ route('karya.fotografi.read', ['k' => $fotografi->id]) }}">
                                    <img src="data:image/jpeg;base64,{{ $fotografi->media }}"
                                        alt="{{ $fotografi->judul }}"
                                        class="w-full h-40 object-cover rounded-lg shadow-md aspect-[4/3]" />
                                </a>

                                <div class="mt-2 text-xs text-gray-700 w-full">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-[#990505] font-semibold uppercase">FOTOGRAFI</span>
                                        <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                        <span>{{ \Carbon\Carbon::parse($fotografi->release_date)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold leading-tight mb-1">{{ $fotografi->judul }}</h3>
                                    <div class="text-xs italic font-medium text-gray-800">
                                        <span>Oleh: {{ $fotografi->creator ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach


                    @if ($totalFotografiCount > 9)
                        <div class="col-span-12 flex justify-end items-end w-full">
                            <a href="{{ url('/karya/fotografi') }}"
                                class="text-red-700 font-semibold text-sm">Selengkapnya
                                >></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif


    <!-- Bagian Desain Grafis -->
    @if ($desainGrafisList->isNotEmpty())
        <section class="mt-12 mb-12">
            <div class="max-w-7xl mx-auto px-5">
                <div class="flex flex-col mb-6">
                    <div class="flex items-center">
                        <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                        <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] flex items-center justify-center text-center"
                            style="clip-path: polygon(0 0, 100% 0, 90% 100%, 0% 100%)">
                            Desain Grafis
                        </h2>
                    </div>
                    <div class="w-full h-[2px] bg-gray-300"></div>
                </div>

                <div class="grid grid-cols-12 gap-4">
                    @foreach ($desainGrafisList as $index => $desainGrafis)
                        @php
                            $isHighlight = $index <= 2;
                        @endphp

                        @if ($isHighlight)
                            <div class="col-span-12 md:col-span-4 relative">
                                <a href="{{ route('karya.desain-grafis.read', ['k' => $desainGrafis->id]) }}"
                                    class="block relative">
                                    <img src="data:image/jpeg;base64,{{ $desainGrafis->media }}"
                                        alt="{{ $desainGrafis->judul }}"
                                        class="w-full h-80 object-cover rounded-lg aspect-[4/3]" />
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-[#990505] via-transparent to-transparent opacity-80 rounded-lg">
                                    </div>
                                    <div class="absolute bottom-0 p-4 text-white">
                                        <div class="flex items-center gap-2 mb-1 text-xs font-semibold uppercase">
                                            DESAIN GRAFIS
                                            <div class="w-[2px] h-3.5 bg-white"></div>
                                            <span>{{ \Carbon\Carbon::parse($desainGrafis->release_date)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold leading-snug">
                                            {{ $desainGrafis->judul }}
                                        </h3>
                                        <div class="text-xs italic font-medium">
                                            Oleh: {{ $desainGrafis->creator ?? '-' }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="col-span-6 md:col-span-4 lg:col-span-2 flex flex-col items-start">
                                <a href="{{ route('karya.desain-grafis.read', ['k' => $desainGrafis->id]) }}">
                                    <img src="data:image/jpeg;base64,{{ $desainGrafis->media }}"
                                        alt="{{ $desainGrafis->judul }}"
                                        class="w-full h-40 object-cover rounded-lg shadow-md aspect-[4/3]" />
                                </a>

                                <div class="mt-2 text-xs text-gray-700 w-full">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <span class="text-[#990505] font-semibold uppercase">DESAIN GRAFIS</span>
                                        <div class="w-[2px] h-3.5 bg-[#990505]"></div>
                                        <span>{{ \Carbon\Carbon::parse($desainGrafis->release_date)->timezone('Asia/Jakarta')->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold leading-tight mb-1">{{ $desainGrafis->judul }}</h3>
                                    <div class="text-xs italic font-medium text-gray-800">
                                        <span>Oleh: {{ $desainGrafis->creator ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($totalDesainGrafisCount > 9)
                        <div class="col-span-12 flex justify-end items-end w-full">
                            <a href="{{ url('/karya/desain-grafis') }}"
                                class="text-red-700 font-semibold text-sm">Selengkapnya >></a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if (
        $sliderNews->isEmpty() &&
            $newsList->isEmpty() &&
            $majalahList->isEmpty() &&
            $buletinList->isEmpty() &&
            $puisiList->isEmpty() &&
            $pantunList->isEmpty() &&
            $syairList->isEmpty() &&
            $fotografiList->isEmpty() &&
            $desainGrafisList->isEmpty())
        <section class="my-20 flex flex-col items-center justify-center text-center text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18M4.121 4.121A1.5 1.5 0 005 3h4l2 2h6a2 2 0 012 2v4m0 4v3a2 2 0 01-2 2h-7.5M3 7v11a2 2 0 002 2h11" />
            </svg>
            <h3 class="text-lg font-semibold">Belum Ada Konten</h3>
            <p class="text-sm">Konten untuk halaman ini belum tersedia saat ini.</p>
        </section>
    @endif

    <!-- Tambahkan Library PDF.js sekali di akhir halaman -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- SwiperJS Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            loop: true,
            spaceBetween: 10,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
@endsection
