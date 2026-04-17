<div class="mt-4">
    <h2 class="text-lg font-semibold mb-2">Reaksi Pengguna</h2>
    @if ($user->reaksi?->isNotEmpty())
        <div class="space-y-4">
            @foreach ($user->reaksi as $reaksi)
                <div class="flex items-start space-x-4 border-b border-gray-700 pb-4 last-of-type:border-b-0">
                    <!-- Item Details -->
                    <div class="flex flex-col flex-grow">
                        <!-- Item Title -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ 
                                match ($reaksi->reaksi_type) {
                                    'Berita' => '/kategori/' . strtolower($reaksi->reaksiable?->kategori ?? '') . '/read?a=' . $reaksi->reaksiable?->id,
                                    'Produk' => '/produk/' . strtolower($reaksi->reaksiable?->kategori ?? '') . '/browse?f=' . $reaksi->reaksiable?->id,
                                    'Karya' => '/karya/' . strtolower($reaksi->reaksiable?->kategori ?? '') . '/read?a=' . $reaksi->reaksiable?->id,
                                    default => '#'
                                }
                            }}">
                                <span class="font-medium">
                                    {{ $reaksi->reaksiable?->judul ?? 'Judul tidak tersedia' }}
                                </span>
                            </a>
                        </div>

                        <!-- Date of Reaction -->
                        <div class="text-sm text-gray-400">
                            {{ \Carbon\Carbon::parse($reaksi->tanggal_reaksi)->format('M d, Y') }}
                        </div>

                        <!-- Reaction Type -->
                        <div class="flex items-center mt-2 text-sm">
                            @if ($reaksi->jenis_reaksi === 'Suka')
                                <i class="fas fa-thumbs-up mr-1 text-green-500"></i>
                                <span>Suka</span>
                            @else
                                <i class="fas fa-thumbs-down mr-1 text-red-500"></i>
                                <span>Tidak Suka</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Tidak ada reaksi ditemukan.</p>
    @endif
</div>

