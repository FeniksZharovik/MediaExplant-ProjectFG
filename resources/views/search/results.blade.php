@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 md:px-12 xl:px-32 py-10">
        <h2 class="text-2xl font-bold mb-4">Hasil pencarian untuk: "{{ $keyword }}"</h2>
        <p class="text-gray-600 mb-10">{{ $total }} data ditemukan</p>

        {{-- Berita --}}
        @if ($berita->count())
            <div class="mb-16">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Berita</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                    @foreach ($berita as $item)
                        <div>
                            @if (!empty($item->thumbnail))
                                <a href="{{ url('/kategori/' . strtolower($item->kategori) . '/read?a=' . $item->id) }}">
                                    <img src="{{ $item->thumbnail }}" alt="Thumbnail"
                                        class="w-full h-44 object-cover rounded-lg">
                                </a>
                            @endif
                            <div class="mt-3">
                                <a href="{{ url('/kategori/' . strtolower($item->kategori) . '/read?a=' . $item->id) }}"
                                    class="block text-black font-semibold text-base hover:text-gray-800 no-underline">
                                    {{ $item->judul }}
                                </a>
                                <p class="text-sm text-gray-600 mt-2">
                                    {{ \Illuminate\Support\Str::limit(strip_tags(str_replace('&nbsp;', ' ', $item->konten_berita)), 100) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $berita->appends(['query' => $keyword])->links() }}
                </div>
            </div>
        @endif

        {{-- Karya --}}
        @if ($karya->count())
            <div class="mb-16">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Karya</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-8">
                    @foreach ($karya as $item)
                        <div>
                            @if (!empty($item->thumbnail))
                                <a href="{{ url('/karya/' . strtolower($item->kategori) . '/read?k=' . $item->id) }}">
                                    <img src="{{ $item->thumbnail }}" alt="Thumbnail"
                                        class="w-full h-36 object-cover rounded-lg">
                                </a>
                            @endif
                            <div class="mt-3">
                                <a href="{{ url('/karya/' . strtolower($item->kategori) . '/read?k=' . $item->id) }}"
                                    class="block text-black font-semibold text-sm hover:text-gray-800 no-underline">
                                    {{ $item->judul }}
                                </a>
                                @if (!empty($item->deskripsi))
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $karya->appends(['query' => $keyword])->links() }}
                </div>
            </div>
        @endif

        {{-- Produk --}}
        @if ($produk->count())
            <div class="mb-16">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Produk</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-8">
                    @foreach ($produk as $item)
                        <div>
                            <a href="{{ url('/produk/' . strtolower($item->kategori) . '/browse?f=' . $item->id) }}">
                                <img src="{{ $item->thumbnail }}" alt="Thumbnail"
                                    class="w-full h-36 object-cover rounded-lg">
                            </a>
                            <div class="mt-3">
                                <a href="{{ url('/produk/' . strtolower($item->kategori) . '/browse?f=' . $item->id) }}"
                                    class="block text-black font-semibold text-sm hover:text-gray-800 no-underline">
                                    {{ $item->judul }}
                                </a>
                                @if (!empty($item->deskripsi))
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $produk->appends(['query' => $keyword])->links() }}
                </div>
            </div>
        @endif

        @if ($total === 0)
            <p class="text-gray-500">Tidak ada hasil ditemukan.</p>
        @endif
    </div>
@endsection
