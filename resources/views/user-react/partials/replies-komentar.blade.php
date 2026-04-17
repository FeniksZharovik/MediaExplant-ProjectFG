@foreach ($komentar->replies as $reply)
    <div class="komentar-item relative" data-id="{{ $reply->id }}"> {{-- ← Hapus ml-4 --}}
        <div class="flex justify-between items-start group nama-pengguna-container" style="position: relative;">
            <div class="flex items-center gap-2">
                @if ($reply->user && $reply->user->profile_pic)
                    <img src="data:image/jpeg;base64,{{ base64_encode($reply->user->profile_pic) }}" alt="Profil"
                        class="w-6 h-6 rounded-full border-2 border-red-500">
                @else
                    <i class="fa-solid fa-user-circle text-xl text-gray-700 hover:text-red-700"></i>
                @endif
                <span class="font-semibold nama-pengguna">{{ $reply->user->nama_pengguna }}</span>
                <span class="text-xs text-gray-500 ml-2">
                    {{ \Carbon\Carbon::parse($reply->tanggal_komentar)->locale('id')->diffForHumans() }}
                </span>
            </div>
            @if ($reply->user->uid === Cookie::get('user_uid'))
                <button
                    class="more-options absolute right-0 top-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    data-id="{{ $reply->id }}"
                    style="font-size: 14px; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            @endif
        </div>

        <div class="isi-komentar mt-1 text-gray-800 text-sm">
            @if ($komentar->user && $reply->user && $reply->user->uid !== $komentar->user->uid)
                <span class="inline-block text-[#9A0605] font-semibold mr-1">
                    {{ '@' . $komentar->user->nama_pengguna }}
                </span>
            @endif
            {{ $reply->isi_komentar }}
        </div>

        <button class="text-xs text-blue-600 hover:underline reply-btn mt-1">Reply</button>

        @if ($reply->replies->count())
            <div class="replies-container hidden mt-2"> {{-- ← Hapus ml-4 --}}
                @include('user-react.partials.replies-komentar', ['komentar' => $reply])
            </div>
            <button class="toggle-replies text-xs text-blue-600 hover:underline mt-1">
                Lihat {{ $reply->replies->count() }} balasan
            </button>
        @endif
    </div>
@endforeach
