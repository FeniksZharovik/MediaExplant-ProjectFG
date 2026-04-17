@extends('layouts.app')

@section('content')
    <main class="py-8">
        <div class="max-w-[1600px] mx-auto px-12 md:px-24 lg:px-32">
            <h1 class="text-xl font-bold mb-6 text-[#9A0605]">Semua Berita Liputan Khusus</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($berita as $item)
                    <div>
                        <a href="{{ route('liputan-khusus.detail', ['a' => $item->id]) }}">
                            <img src="{{ $item->first_image }}" alt="{{ $item->judul }}"
                                class="w-full h-36 sm:h-40 lg:h-48 object-cover rounded mb-2">
                        </a>
                        <a href="{{ route('liputan-khusus.detail', ['a' => $item->id]) }}">
                            <h3 class="text-sm font-semibold leading-tight">
                                {{ Str::limit($item->judul, 60) }}
                            </h3>
                        </a>
                        <div class="text-xs text-[#ABABAB] mt-1">
                            <span>{{ $item->user->nama_lengkap ?? '-' }}</span> |
                            <span>{{ \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p>Tidak ada berita tersedia.</p>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $berita->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
