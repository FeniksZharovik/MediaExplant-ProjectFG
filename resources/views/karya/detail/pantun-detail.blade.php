@extends('layouts.app')

@section('content')
    <div class="max-w-[1320px] mx-auto px-4 sm:px-6 py-10">

        {{-- Label PANTUN --}}
        <div class="flex flex-col mb-6">
            <div class="flex items-center">
                <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605]"
                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                    PANTUN
                </h2>
            </div>
            <div class="w-full h-[2px] bg-gray-300 mb-3"></div>
        </div>

        {{-- Judul Besar --}}
        <h1 class="text-3xl font-bold mb-1">{{ $karya->judul }}</h1>

        {{-- Info Penulis & Waktu --}}
        <div class="flex items-center text-sm text-[#A8A8A8] mb-3">
            <span class="mr-2">Oleh : {{ $karya->user->nama_lengkap ?? '-' }}</span> |
            <span class="ml-2">{{ \Carbon\Carbon::parse($karya->release_date)->format('d M Y') }} -
                {{ \Carbon\Carbon::parse($karya->release_date)->format('H.i') }} WIB</span>
            @php
                use Illuminate\Support\Facades\Cookie;
                use App\Models\User;
                use App\Models\UserReact\Bookmark;

                $userUid = Cookie::get('user_uid');
                $user = $userUid ? User::where('uid', $userUid)->first() : null;

                $isBookmarked = Bookmark::where('user_id', $user->uid ?? null)
                    ->where('item_id', $karya->id)
                    ->where('bookmark_type', 'Karya')
                    ->exists();
            @endphp
            <div class="ml-auto -mt-3">
                <button id="bookmark-btn" class="bookmark-check flex items-center gap-2 text-gray-400 hover:text-gray-800"
                    data-item-id="{{ $karya->id }}" data-bookmarked="{{ $isBookmarked ? 'true' : 'false' }}"
                    data-bookmark-type="Karya">
                    <span class="text-sm">
                        {{ $isBookmarked ? 'Batalkan Bookmark' : 'Simpan dan baca nanti' }}
                    </span>
                    <span id="bookmark-icon">
                        @if ($isBookmarked)
                            <i class="fa-solid fa-bookmark text-xl text-black"></i>
                        @else
                            <i class="fa-regular fa-bookmark text-xl text-gray-400"></i>
                        @endif
                    </span>
                </button>
            </div>
        </div>

        {{-- Konten --}}
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Kiri: Gambar + Deskripsi --}}
            <div class="w-full lg:w-1/2 pr-20">
                <div class="w-[600px]">
                    {{-- Gambar --}}
                    <div class="relative h-[840px]">
                        <img src="data:image/jpeg;base64,{{ $karya->media }}" alt="{{ $karya->judul }}"
                            class="w-full h-full object-cover rounded-lg shadow-md" />
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mt-6 text-[15px] leading-relaxed text-justify">
                        {!! strip_tags($karya->deskripsi, '<p><br>') !!}
                    </div>
                </div>
            </div>

            {{-- Kanan: Judul kecil + Konten --}}
            <div class="w-full lg:w-1/2 pl-20">
                <div class="text-center">
                    <div class="text-lg font-bold mb-1">{{ $karya->judul }}</div>
                    <div class="text-sm mb-2 italic">(Karya oleh {{ $karya->creator ?? '-' }})</div>
                </div>

                <div class="text-[14px] leading-[1.30] whitespace-pre-line text-justify">
                    {!! nl2br(e($karya->konten)) !!}
                </div>
            </div>
        </div>

        {{-- Tanggapan --}}
        <div class="mt-5" data-karya-id="{{ $karya->id }}">
            <div class="text-sm font-semibold text-black mb-2">Beri Tanggapanmu :</div>
            <div class="flex items-center gap-6 text-[#ABABAB]">
                <button id="likeButton"
                    class="reaction-check flex items-center gap-2 hover:text-gray-700 {{ $userReaksi && $userReaksi->jenis_reaksi === 'Suka' ? 'text-blue-600' : '' }}">
                    <i class="fas fa-thumbs-up"></i>
                    <span id="likeCount">{{ $likeCount ?? 0 }}</span>
                </button>
                <button id="dislikeButton"
                    class="reaction-check flex items-center gap-2 hover:text-gray-700 {{ $userReaksi && $userReaksi->jenis_reaksi === 'Tidak Suka' ? 'text-blue-600' : '' }}">
                    <i class="fas fa-thumbs-down"></i>
                    <span id="dislikeCount">{{ $dislikeCount ?? 0 }}</span>
                </button>
                <div class="relative">
                    <button id="openShareModal" class="flex items-center gap-2 hover:text-gray-700">
                        <i class="fas fa-share-nodes"></i> Share
                    </button>
                </div>
                <button id="reportButton"
                    class="report-check flex items-center gap-2 text-[#ABABAB] hover:text-[#9A0605] focus:text-[#9A0605]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16M4 4h6l2 2h6v6h-6l-2-2H4z" />
                    </svg>
                    <span>Laporkan</span>
                </button>
            </div>
        </div>

        <!-- Komentar -->
        <div class="mt-10">
            <form id="komentarForm" method="POST" class="{{ $user ? '' : 'trigger-login' }}">
                @csrf
                <div class="relative w-full">
                    <input type="text" name="komentar" id="komentarInput" placeholder="Tulis komentarmu disini"
                        class="w-full border border-[#9A0605] rounded-full pr-12 pl-4 py-2 text-sm focus:outline-none" />
                    <button type="submit"
                        class="absolute right-0 top-0 bottom-0 w-10 flex items-center justify-center bg-[#9A0605] rounded-full rounded-l-none text-white hover:bg-red-800">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </form>

            <div class="mt-5 border border-gray-200 rounded-lg bg-gray-50 p-4">
                <div id="komentarContainer"
                    class="space-y-4 text-sm text-gray-700 max-h-[300px] overflow-y-auto transition-all duration-300">
                    @forelse ($komentarList->where('parent_id', null) as $komentar)
                        <div class="komentar-item relative" data-id="{{ $komentar->id }}">
                            <div class="flex justify-between items-start group nama-pengguna-container"
                                style="position: relative;">
                                <div class="flex items-center gap-2">
                                    @if ($komentar->user && $komentar->user->profile_pic)
                                        <img src="data:image/jpeg;base64,{{ base64_encode($komentar->user->profile_pic) }}"
                                            alt="Profil" class="w-6 h-6 rounded-full border-2 border-red-500">
                                    @else
                                        <i class="fa-solid fa-user-circle text-xl text-gray-700 hover:text-red-700"></i>
                                    @endif
                                    <span class="font-semibold nama-pengguna">{{ $komentar->user->nama_pengguna }}</span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        {{ \Carbon\Carbon::parse($komentar->tanggal_komentar)->diffForHumans(null, true, false, 1) === 'now' ? 'baru saja' : \Carbon\Carbon::parse($komentar->tanggal_komentar)->locale('id')->diffForHumans() }}
                                    </span>
                                </div>
                                @if ($komentar->user->uid === Cookie::get('user_uid'))
                                    <button
                                        class="more-options absolute right-0 top-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                        data-id="{{ $komentar->id }}"
                                        style="font-size: 14px; background: none; border: none; cursor: pointer;">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="isi-komentar flex-1">
                                {{ \Illuminate\Support\Str::limit($komentar->isi_komentar, 150) }}
                                @if (strlen($komentar->isi_komentar) > 150)
                                    <button class="text-xs text-blue-600 hover:underline show-full"
                                        data-full="{{ $komentar->isi_komentar }}"
                                        data-short="{{ \Illuminate\Support\Str::limit($komentar->isi_komentar, 150) }}">Lihat
                                        selengkapnya</button>
                                @endif
                            </div>
                            <button class="text-xs text-blue-600 hover:underline reply-btn mt-1">Reply</button>

                            <div class="replies ml-4 text-sm text-gray-500 mt-2 space-y-2 hidden">
                                @include('user-react.partials.replies-komentar', [
                                    'komentar' => $komentar,
                                ])
                            </div>

                            @if ($komentar->replies->count())
                                <button class="toggle-replies text-xs text-blue-600 hover:underline mt-1">
                                    {{ $komentar->replies->count() === 1 ? 'Lihat 1 balasan' : 'Lihat semua ' . $komentar->replies->count() . ' balasan' }}
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500">Belum Ada Komentar</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Rekomendasi Hari Ini --}}
        <div class="mt-10 flex flex-col mb-6">
            <div class="flex items-center">
                <div class="w-[8px] h-[36px] bg-[#9A0605] mr-[4px]"></div>
                <h2 class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605]"
                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                    Rekomendasi Hari ini
                </h2>
            </div>
            <div class="w-full h-[2px] bg-gray-300 mb-3"></div>
        </div>

        {{-- Grid Rekomendasi (6 kolom) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-6">
            @foreach ($rekomendasi as $item)
                <div class="flex flex-col">
                    <a href="{{ route('karya.pantun.read', ['k' => $item->id]) }}">
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
                    <a href="{{ route('karya.pantun.read', ['k' => $item->id]) }}">
                        <h3 class="text-base font-bold mt-1">"{{ $item->judul }}"</h3>
                    </a>
                    <p class="text-sm text-gray-700 mb-2">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 80) }}
                    </p>
                    <div class="text-xs italic font-medium text-gray-800">
                        <span>Oleh : {{ $item->creator ?? '-' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Share -->
    <div id="shareModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
        <div class="bg-[#212121] rounded-2xl p-6 w-full max-w-md relative">

            <!-- Tombol Close -->
            <button id="closeShareModal" class="absolute top-4 right-4 text-gray-400 hover:text-white text-2xl">
                &times;
            </button>

            <!-- Title -->
            <h2 class="text-white text-center text-xl font-semibold mb-6">Bagikan</h2>

            <!-- Wrapper Icon + Slide Button -->
            <div class="relative flex items-center">

                <!-- Tombol Slide Kiri -->
                <button id="slideLeft"
                    class="absolute left-0 z-10 bg-[#333] hover:bg-[#444] text-white p-2 rounded-full shadow-md">
                    &#10094;
                </button>

                <!-- Slide Icon Bagikan -->
                <div id="iconContainer"
                    class="flex overflow-x-auto space-x-6 px-10 py-2 scrollbar-none snap-x snap-mandatory">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/whatsapp.png" alt="WhatsApp" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">WhatsApp</span>
                    </a>

                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/facebook-new.png" alt="Facebook" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Facebook</span>
                    </a>

                    <!-- Twitter (X) -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/ios-filled/50/000000/twitterx.png" alt="X"
                                class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">X</span>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($karya->judul) }}"
                        target="_blank" class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/linkedin.png" alt="LinkedIn" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">LinkedIn</span>
                    </a>

                    <!-- Email -->
                    <a href="mailto:?subject={{ urlencode($karya->judul) }}&body={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/fluency/48/new-post.png" alt="Email" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Email</span>
                    </a>

                    <!-- Reddit -->
                    <a href="https://reddit.com/submit?url={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/reddit--v1.png" alt="Reddit" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Reddit</span>
                    </a>

                    <!-- VK -->
                    <a href="https://vk.com/share.php?url={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/vk-circled.png" alt="VK" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">VK</span>
                    </a>

                    <!-- OK.ru -->
                    <a href="https://connect.ok.ru/offer?url={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/odnoklassniki.png" alt="OK" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">OK</span>
                    </a>

                    <!-- Pinterest -->
                    <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/pinterest--v1.png" alt="Pinterest" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Pinterest</span>
                    </a>

                    <!-- Blogger -->
                    <a href="https://www.blogger.com/blog-this.g?u={{ urlencode(request()->fullUrl()) }}" target="_blank"
                        class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/blogger.png" alt="Blogger" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Blogger</span>
                    </a>

                    <!-- Tumblr -->
                    <a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl={{ urlencode(request()->fullUrl()) }}"
                        target="_blank" class="flex flex-col items-center min-w-max">
                        <div class="bg-white p-3 rounded-full shadow-md hover:opacity-80 transition">
                            <img src="https://img.icons8.com/color/48/tumblr.png" alt="Tumblr" class="w-8 h-8">
                        </div>
                        <span class="text-white text-xs mt-2">Tumblr</span>
                    </a>

                </div>

                <!-- Tombol Slide Kanan -->
                <button id="slideRight"
                    class="absolute right-0 z-10 bg-[#333] hover:bg-[#444] text-white p-2 rounded-full shadow-md">
                    &#10095;
                </button>

            </div>

            <!-- Link & Copy -->
            <div class="flex items-center bg-[#121212] rounded-lg overflow-hidden border border-gray-600 mt-6">
                <input id="shareLink" type="text" value="{{ request()->fullUrl() }}"
                    class="flex-1 bg-transparent text-white px-3 py-2 text-sm focus:outline-none" readonly>
                <button id="copyLink" class="bg-[#9A0605] hover:bg-[#7a0504] text-white px-4 py-2 text-sm">Salin</button>
            </div>
        </div>
    </div>

    <!-- Modal Pelaporan -->
    <div id="reportModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
            <h2 class="text-lg font-bold mb-4">Laporkan Pantun</h2>
            <form id="reportForm">
                <div class="mb-4">
                    <label class="block mb-2">Pilih alasan pelaporan:</label>
                    <div id="reportReasons" class="space-y-4">
                        @php
                            $reasons = [
                                'Konten seksual',
                                'Konten kekerasan atau menjijikkan',
                                'Konten kebencian atau pelecehan',
                                'Tindakan berbahaya',
                                'Spam atau misinformasi',
                                'Masalah hukum',
                                'Teks bermasalah',
                            ];
                        @endphp
                        @foreach ($reasons as $reason)
                            <label class="block">
                                <input type="radio" name="reportReason" value="{{ $reason }}"
                                    class="form-radio">
                                <span class="ml-2">{{ $reason }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <button type="button" id="nextButton" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mt-4"
                    disabled>Berikutnya</button>
            </form>
        </div>
    </div>

    <!-- Modal Laporan Tambahan -->
    <div id="additionalReportModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
            <h2 class="text-lg font-bold mb-4">Laporan Tambahan Opsional</h2>
            <textarea id="detailTextarea" name="detail_pesan" class="w-full border border-gray-300 rounded p-2"
                placeholder="Berikan detail tambahan" maxlength="500"></textarea>
            <div class="text-right text-sm text-gray-500">
                <span id="charCount">0</span>/500
            </div>
            <div class="mt-4 flex justify-end space-x-4">
                <button type="button" id="backButton"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Kembali</button>
                <button type="button" id="submitReportButton"
                    class="bg-blue-500 text-white px-4 py-2 rounded">Laporkan</button>
            </div>
        </div>
    </div>

    <!-- Modal Terima Kasih -->
    <div id="thankYouModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto mb-4 h-24 w-24 text-green-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-lg font-semibold mb-4">Terima kasih telah melaporkan pantun ini!</p>
            <p class="text-gray-600">Laporan Anda akan kami tinjau sesegera mungkin.</p>
            <button type="button" id="closeThankYouModal"
                class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Tutup</button>
        </div>
    </div>

    <!-- Modal Login -->
    <div id="loginModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm relative animate-fade-in border border-gray-200">

            <!-- Close Button -->
            <button id="closeLoginModal"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors text-2xl">
                &times;
            </button>

            <!-- Modal Content -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Diperlukan</h2>
                <p class="text-sm text-gray-500 mb-6">Anda harus login terlebih dahulu untuk menggunakan fitur ini.</p>
                <a href="{{ route('login') }}"
                    class="inline-block bg-[#9A0605] hover:bg-[#7e0504] text-white font-semibold px-6 py-2 rounded-full transition-all duration-200 shadow">
                    Login Sekarang
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <style>
        .nama-pengguna-container {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .more-options {
            position: absolute;
            right: -20px;
            /* sedikit di luar nama_pengguna, sesuaikan */
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            font-size: 14px;
            color: #555;
        }

        .nama-pengguna-container:hover .more-options,
        .more-options:hover {
            opacity: 1 !important;
        }

        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.3s ease-out;
        }
    </style>

    <!-- Modal Konfirmasi Hapus -->
    <div id="hapusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div id="hapusModalContent"
            class="bg-white p-8 rounded-xl shadow-lg w-96 max-w-full text-center animate-fade-in-down relative">
            <p class="mb-6 text-gray-700 text-lg font-semibold">Apakah kamu yakin ingin menghapus komentar ini?</p>
            <div class="flex justify-center gap-5">
                <button id="batalHapus"
                    class="px-6 py-2 rounded bg-gray-300 hover:bg-gray-400 text-sm font-medium transition">Batal</button>
                <button id="konfirmasiHapus"
                    class="px-6 py-2 rounded bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition">Hapus</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const komentarForm = document.getElementById('komentarForm');
            const komentarInput = document.getElementById('komentarInput');
            const komentarContainer = document.getElementById('komentarContainer');
            let currentReplyTarget = null;

            komentarForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const komentar = komentarInput.value.trim();
                if (!komentar) return;

                if (!isLoggedIn) {
                    document.getElementById('loginModal').classList.remove('hidden');
                    return;
                }

                const parentId = komentarInput.dataset.replyTo || null;

                try {
                    const response = await fetch("{{ route('komentar.kirim') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            komentar: komentar,
                            item_id: "{{ $karya->id }}",
                            komentar_type: "Karya",
                            parent_id: parentId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        const noCommentText = komentarContainer.querySelector(
                            '.text-center.text-gray-500');
                        if (noCommentText) noCommentText.remove();

                        const replyHTML = `
                            <div class="reply-item text-sm text-gray-700">
                                ↳ <span class="font-semibold">${data.nama_pengguna}</span> — ${data.isi_komentar}
                            </div>
                        `;

                        if (data.parent_id) {
                            const parent = document.querySelector(
                                `.komentar-item[data-id="${data.parent_id}"] .replies`);
                            parent.insertAdjacentHTML('beforeend', replyHTML);

                            // Tampilkan container balasan jika tersembunyi
                            const repliesContainer = document.querySelector(
                                `.komentar-item[data-id="${data.parent_id}"] .replies`);

                            const toggleButton = repliesContainer.closest('.komentar-item')
                                .querySelector('.toggle-replies');
                            if (toggleButton) {
                                const jumlah = repliesContainer.children.length;
                                toggleButton.textContent = 'Sembunyikan balasan';
                                toggleButton.classList.remove('hidden');
                            }

                        } else {
                            const div = document.createElement('div');
                            div.className = "komentar-item animate-fade-in relative";
                            div.setAttribute('data-id', data.id);
                            div.innerHTML = `
                            <div class="flex justify-between items-start group nama-pengguna-container" style="position: relative;">
                                <div class="flex items-center gap-2">
                                ${
                                    data.profile_pic
                                    ? `
                                                    <img src="${data.profile_pic}" alt="Profil" class="w-6 h-6 rounded-full border-2 border-red-500">
                                                `
                                    : `
                                                    <i class="fa-solid fa-user-circle text-xl text-gray-700 hover:text-red-700"></i>
                                                `
                                }
                                <span class="font-semibold nama-pengguna">${data.nama_pengguna}</span>
                                <span class="text-xs text-gray-500 ml-2">baru saja</span>
                                </div>
                                ${
                                data.owned_by_user
                                    ? `
                                                <button
                                                    class="more-options absolute right-0 top-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                                    data-id="${data.id}"
                                                    style="font-size: 14px; background: none; border: none; cursor: pointer;"
                                                >
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                `
                                    : ''
                                }
                            </div>

                            <div class="isi-komentar flex-1 mt-1 text-gray-800 text-sm">
                                ${data.isi_komentar}
                            </div>

                            <button class="text-xs text-blue-600 hover:underline reply-btn mt-1">Reply</button>
                            <div class="replies ml-4 text-sm text-gray-500 mt-2 space-y-2 hidden"></div>
                            <button class="toggle-replies text-xs text-blue-600 hover:underline mt-1 hidden"></button>
                            `;
                            komentarContainer.prepend(div);

                        }

                        komentarInput.value = '';
                        komentarInput.removeAttribute('data-reply-to');
                        komentarInput.placeholder = 'Tulis komentarmu disini';
                        if (currentReplyTarget) {
                            currentReplyTarget.remove();
                            currentReplyTarget = null;
                        }
                    } else {
                        alert("Gagal mengirim komentar.");
                    }
                } catch (err) {
                    alert("Gagal mengirim komentar.");
                    console.error(err);
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('reply-btn')) {
                    if (currentReplyTarget) currentReplyTarget.remove();

                    const parentKomentar = e.target.closest('.komentar-item');
                    const parentId = parentKomentar.dataset.id;

                    const formReply = document.createElement('div');
                    formReply.className = 'mt-2';
                    formReply.innerHTML = `
                <div class="flex items-center gap-2">
                    <input type="text" class="reply-input border border-gray-300 rounded-full px-3 py-1 text-sm flex-1" placeholder="Balas komentar ini..." />
                    <button class="send-reply px-3 py-1 bg-[#9A0605] text-white rounded-full text-sm hover:bg-red-800">Kirim</button>
                </div>
            `;
                    parentKomentar.appendChild(formReply);
                    currentReplyTarget = formReply;

                    const input = formReply.querySelector('.reply-input');
                    input.focus();

                    const submitReply = async () => {
                        const isi = input.value.trim();
                        if (!isi) return;

                        if (!isLoggedIn) {
                            document.getElementById('loginModal').classList.remove('hidden');
                            return;
                        }

                        try {
                            const response = await fetch("{{ route('komentar.kirim') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                credentials: 'same-origin',
                                body: JSON.stringify({
                                    komentar: isi,
                                    item_id: "{{ $karya->id }}",
                                    komentar_type: "Karya",
                                    parent_id: parentId
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                const parentKomentar = document.querySelector(
                                    `.komentar-item[data-id="${data.parent_id}"]`);
                                if (!parentKomentar) {
                                    console.error("Parent komentar tidak ditemukan.");
                                    return;
                                }

                                let repliesContainer = parentKomentar.querySelector('.replies');
                                if (!repliesContainer) {
                                    repliesContainer = document.createElement('div');
                                    repliesContainer.className =
                                        'replies text-sm text-gray-500 mt-2 space-y-2';
                                    parentKomentar.appendChild(repliesContainer);
                                }
                                const res = await fetch(`/komentar/${data.parent_id}/replies`);
                                const html = await res.text();

                                repliesContainer.innerHTML = html;
                                repliesContainer.classList.remove('hidden');

                                // Tampilkan dan ubah tombol toggle
                                if (repliesContainer.classList.contains('hidden')) {
                                    repliesContainer.classList.remove('hidden');
                                }

                                const toggleButton = repliesContainer.closest('.komentar-item')
                                    .querySelector('.toggle-replies');
                                if (toggleButton) {
                                    const jumlah = repliesContainer.children.length;
                                    toggleButton.textContent = 'Sembunyikan balasan';
                                    toggleButton.classList.remove('hidden');
                                }

                                // Bersihkan reply form
                                currentReplyTarget.remove();
                                currentReplyTarget = null;
                            } else {
                                alert("Gagal mengirim komentar.");
                            }

                        } catch (err) {
                            console.error(err);
                            alert("Gagal mengirim komentar.");
                        }
                    };

                    formReply.querySelector('.send-reply').addEventListener('click', submitReply);
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            submitReply();
                        }
                    });
                }

                if (e.target.classList.contains('show-full')) {
                    const fullText = e.target.dataset.full;
                    const shortText = e.target.dataset.short;
                    const span = e.target.parentElement;

                    span.innerHTML = `
                    ${fullText}
                    <button class="text-xs text-blue-600 hover:underline show-less"
                        data-full="${fullText}" data-short="${shortText}">
                        Lihat lebih sedikit
                    </button>
                `;
                }

                if (e.target.classList.contains('show-less')) {
                    const shortText = e.target.dataset.short;
                    const fullText = e.target.dataset.full;
                    const span = e.target.parentElement;

                    span.innerHTML = `
                    ${shortText}
                    <button class="text-xs text-blue-600 hover:underline show-full"
                        data-full="${fullText}" data-short="${shortText}">
                        Lihat selengkapnya
                    </button>
                `;
                }

                if (e.target.classList.contains('toggle-replies')) {
                    const container = e.target.previousElementSibling;
                    if (container.classList.contains('hidden')) {
                        container.classList.remove('hidden');
                        e.target.textContent = `Sembunyikan balasan`;
                    } else {
                        container.classList.add('hidden');
                        const jumlah = container.children.length;
                        e.target.textContent = jumlah === 1 ? `Lihat 1 balasan` :
                            `Lihat semua ${jumlah} balasan`;
                    }
                }
            });
        });

        let komentarIdToDelete = null;

        document.addEventListener('click', function(e) {
            // TOMBOL TITIK TIGA (opsi hapus)
            if (e.target.closest('.more-options')) {
                komentarIdToDelete = e.target.closest('.more-options').dataset.id;
                document.getElementById('hapusModal').classList.remove('hidden');
            }

            // BATAL HAPUS
            if (e.target.id === 'batalHapus') {
                komentarIdToDelete = null;
                document.getElementById('hapusModal').classList.add('hidden');
            }

            // KONFIRMASI HAPUS
            if (e.target.id === 'konfirmasiHapus') {
                if (!komentarIdToDelete) return;

                fetch(`/komentar/${komentarIdToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const komentarItem = document.querySelector(
                                `.komentar-item[data-id="${komentarIdToDelete}"]`);
                            if (komentarItem) komentarItem.remove();
                        } else {
                            alert("Gagal menghapus komentar.");
                        }
                        document.getElementById('hapusModal').classList.add('hidden');
                        komentarIdToDelete = null;
                    }).catch(err => {
                        console.error(err);
                        alert("Gagal menghapus komentar.");
                        document.getElementById('hapusModal').classList.add('hidden');
                        komentarIdToDelete = null;
                    });
            }
        });

        // Tutup modal saat klik di luar konten modal
        document.getElementById('hapusModal').addEventListener('click', function(e) {
            if (e.target.id === 'hapusModal') { // klik tepat di background overlay
                komentarIdToDelete = null;
                this.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const bookmarkBtn = document.getElementById('bookmark-btn');
            const text = bookmarkBtn.querySelector('span');
            const iconContainer = document.getElementById('bookmark-icon');

            bookmarkBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const itemId = this.dataset.itemId;
                const bookmarkType = this.dataset.bookmarkType; // Ambil jenis bookmark (Karya)

                fetch('/bookmark/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            item_id: itemId,
                            bookmark_type: bookmarkType
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'bookmarked') {
                            iconContainer.innerHTML =
                                '<i class="fa-solid fa-bookmark text-xl text-black"></i>';
                            text.textContent = 'Batalkan Bookmark';
                            bookmarkBtn.setAttribute('data-bookmarked', 'true');
                        } else {
                            iconContainer.innerHTML =
                                '<i class="fa-regular fa-bookmark text-xl text-gray-400"></i>';
                            text.textContent = 'Simpan dan baca nanti';
                            bookmarkBtn.setAttribute('data-bookmarked', 'false');
                        }
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const reportButton = document.getElementById('reportButton');
            const reportModal = document.getElementById('reportModal');
            const nextButton = document.getElementById('nextButton');
            const additionalReportModal = document.getElementById('additionalReportModal');
            const thankYouModal = document.getElementById('thankYouModal');
            const closeThankYouModalButton = document.getElementById('closeThankYouModal');
            const backButton = document.getElementById('backButton');
            const textarea = document.getElementById('detailTextarea');
            const charCount = document.getElementById('charCount');

            const reasonsWithOptions = {
                "Konten seksual": ["Pornografi", "Eksploitasi anak", "Pelecehan seksual"],
                "Konten kekerasan atau menjijikkan": ["Kekerasan fisik", "Kekerasan verbal",
                    "Kekerasan psikologis"
                ],
                "Konten kebencian atau pelecehan": ["Pelecehan rasial", "Pelecehan agama", "Pelecehan seksual"],
                "Tindakan berbahaya": ["Penggunaan narkoba", "Penyalahgunaan senjata",
                    "Tindakan berbahaya lainnya"
                ],
                "Spam atau misinformasi": ["Informasi palsu", "Iklan tidak sah", "Penipuan"],
                "Masalah hukum": ["Pelanggaran hak cipta", "Pelanggaran privasi", "Masalah hukum lainnya"],
                "Teks bermasalah": ["Kata-kata kasar", "Teks diskriminatif", "Teks mengandung kekerasan"]
            };

            // Update character count dynamically as the user types
            if (textarea && charCount) {
                textarea.addEventListener('input', function() {
                    const currentLength = this.value.length;
                    charCount.textContent = currentLength; // Update the displayed character count
                });
            }

            // Modal Button Click to Show Report Modal
            reportButton.addEventListener('click', () => {
                if (!isLoggedIn) {
                    openLoginModal();
                    return;
                }
                reportModal.classList.remove('hidden');
            });

            // Handle Reason Change and Dynamic Select Options
            document.querySelectorAll('input[name="reportReason"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const selectedReason = radio.value;
                    const label = radio.closest('label');
                    const prev = document.querySelector('.additional-container');
                    if (prev) prev.remove();

                    if (reasonsWithOptions[selectedReason]) {
                        const container = document.createElement('div');
                        container.classList.add('additional-container', 'mt-2');

                        const select = document.createElement('select');
                        select.classList.add('form-select', 'w-full', 'border', 'border-gray-300',
                            'rounded', 'p-2');

                        const defaultOption = document.createElement('option');
                        defaultOption.textContent = "Pilih masalah";
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        select.appendChild(defaultOption);

                        reasonsWithOptions[selectedReason].forEach(option => {
                            const opt = document.createElement('option');
                            opt.value = option;
                            opt.textContent = option;
                            select.appendChild(opt);
                        });

                        container.appendChild(select);
                        label.appendChild(container);

                        // Enable Next Button when selecting option
                        select.addEventListener('change', () => {
                            if (select.value) {
                                nextButton.classList.remove('bg-gray-300');
                                nextButton.classList.add('bg-blue-500', 'text-white');
                                nextButton.disabled = false;
                            }
                        });
                    }

                    // Disable Next Button if reason is changed
                    nextButton.classList.add('bg-gray-300');
                    nextButton.classList.remove('bg-blue-500', 'text-white');
                    nextButton.disabled = true;
                });
            });

            // Navigate to Next Modal
            nextButton.addEventListener('click', function() {
                reportModal.classList.add('hidden');
                additionalReportModal.classList.remove('hidden');
            });

            // Navigate Back to Previous Modal
            backButton.addEventListener('click', function() {
                additionalReportModal.classList.add('hidden');
                reportModal.classList.remove('hidden');
            });

            // Close Thank You Modal
            closeThankYouModalButton.addEventListener('click', function() {
                thankYouModal.classList.add('hidden');
                resetForm();
            });

            // Reset Form After Submission or Close
            function resetForm() {
                document.querySelectorAll('input[name="reportReason"]').forEach(radio => radio.checked = false);
                const container = document.querySelector('.additional-container');
                if (container) container.remove();
                nextButton.classList.add('bg-gray-300');
                nextButton.classList.remove('bg-blue-500', 'text-white');
                nextButton.disabled = true;
                document.querySelector('textarea[name="detail_pesan"]').value = ''; // Reset textarea content
                charCount.textContent = '0'; // Reset character count to 0
            }

            // Close Modal When Clicking Outside
            function closeModalOnOutsideClick(modal) {
                window.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        if (modal === thankYouModal) resetForm();
                    }
                });
            }

            closeModalOnOutsideClick(reportModal);
            closeModalOnOutsideClick(thankYouModal);

            // Submit the Report
            document.getElementById('submitReportButton').addEventListener('click', function() {
                const selectedReason = document.querySelector('input[name="reportReason"]:checked');
                const additionalDetail = document.querySelector('textarea[name="detail_pesan"]').value
                    .trim();
                const itemId = new URLSearchParams(window.location.search).get('k');

                if (selectedReason && itemId) {
                    fetch('/report-news', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({
                                report_reason: selectedReason.value,
                                detail_pesan: additionalDetail,
                                item_id: itemId,
                                pesan_type: 'Karya' // Change this based on the context
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                additionalReportModal.classList.add('hidden');
                                thankYouModal.classList.remove('hidden');
                                resetForm(); // Reset form after successful submission
                            } else {
                                alert(data.message || 'Gagal mengirim laporan.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Terjadi kesalahan jaringan.');
                        });
                } else {
                    alert('Silakan pilih alasan dan pastikan item ID tersedia.');
                }
            });
        });

        // Reset Form After Submission or Close
        function resetForm() {
            document.querySelectorAll('input[name="reportReason"]').forEach(radio => radio.checked = false);
            const container = document.querySelector('.additional-container');
            if (container) container.remove();
            nextButton.classList.add('bg-gray-300');
            nextButton.classList.remove('bg-blue-500', 'text-white');
            nextButton.disabled = true;
            document.querySelector('textarea[name="detail_pesan"]').value = ''; // Reset textarea content
            charCount.textContent = '0'; // Reset character count to 0
        }

        document.addEventListener('DOMContentLoaded', function() {
            const likeButton = document.getElementById('likeButton');
            const dislikeButton = document.getElementById('dislikeButton');
            const likeCountSpan = document.getElementById('likeCount');
            const dislikeCountSpan = document.getElementById('dislikeCount');
            const karyaId = document.querySelector('[data-karya-id]').getAttribute('data-karya-id');

            likeButton.addEventListener('click', function() {
                sendReaction('Suka');
            });

            dislikeButton.addEventListener('click', function() {
                sendReaction('Tidak Suka');
            });

            function sendReaction(jenisReaksi) {
                if (!isLoggedIn) {
                    openLoginModal();
                    return;
                }

                fetch('/reaksi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            item_id: karyaId,
                            jenis_reaksi: jenisReaksi,
                            reaksi_type: 'Karya'
                        })
                    })
                    .then(async response => {
                        const contentType = response.headers.get('Content-Type');
                        if (!response.ok) {
                            const errorData = contentType && contentType.includes('application/json') ?
                                await response.json() : null;
                            throw new Error(errorData?.message || 'Gagal memproses reaksi.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        likeCountSpan.textContent = data.likeCount;
                        dislikeCountSpan.textContent = data.dislikeCount;

                        if (jenisReaksi === 'Suka') {
                            likeButton.classList.add('text-blue-600');
                            dislikeButton.classList.remove('text-blue-600');
                        } else {
                            dislikeButton.classList.add('text-blue-600');
                            likeButton.classList.remove('text-blue-600');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error.message);
                        alert(error.message);
                    });
            }
        });

        const shareModal = document.getElementById('shareModal');
        const openShareModal = document.getElementById('openShareModal');
        const closeShareModal = document.getElementById('closeShareModal');
        const copyLinkBtn = document.getElementById('copyLink');
        const shareLinkInput = document.getElementById('shareLink');

        const iconContainer = document.getElementById('iconContainer');
        const slideLeftBtn = document.getElementById('slideLeft');
        const slideRightBtn = document.getElementById('slideRight');

        // Buka modal
        openShareModal.addEventListener('click', () => {
            shareModal.classList.remove('hidden');
        });

        // Tutup modal
        closeShareModal.addEventListener('click', () => {
            shareModal.classList.add('hidden');
        });

        // Klik di luar modal untuk tutup
        shareModal.addEventListener('click', (e) => {
            if (e.target === shareModal) {
                shareModal.classList.add('hidden');
            }
        });

        // Salin link ke clipboard
        copyLinkBtn.addEventListener('click', () => {
            shareLinkInput.select();
            shareLinkInput.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(shareLinkInput.value)
                .then(() => {
                    copyLinkBtn.textContent = 'Disalin!';
                    setTimeout(() => {
                        copyLinkBtn.textContent = 'Salin';
                    }, 2000);
                })
                .catch(() => {
                    alert('Gagal menyalin link. Silakan salin manual.');
                });
        });

        // Tombol Slide
        slideLeftBtn.addEventListener('click', () => {
            iconContainer.scrollBy({
                left: -150,
                behavior: 'smooth'
            });
        });

        slideRightBtn.addEventListener('click', () => {
            iconContainer.scrollBy({
                left: 150,
                behavior: 'smooth'
            });
        });

        const loginModal = document.getElementById('loginModal');
        const closeLoginModal = document.getElementById('closeLoginModal');

        // Utility untuk buka modal
        function openLoginModal() {
            loginModal.classList.remove('hidden');
        }

        // Tutup modal ketika klik tombol atau luar modal
        closeLoginModal.addEventListener('click', () => loginModal.classList.add('hidden'));
        window.addEventListener('click', (e) => {
            if (e.target === loginModal) loginModal.classList.add('hidden');
        });

        // Cek login status dari PHP (Laravel)
        const isLoggedIn = {{ $user ? 'true' : 'false' }};

        // Bookmark
        document.querySelectorAll('.bookmark-check').forEach(el => {
            el.addEventListener('click', function(e) {
                if (!isLoggedIn) {
                    e.preventDefault();
                    openLoginModal();
                }
            });
        });

        // Reaksi (like/dislike)
        document.querySelectorAll('.reaction-check').forEach(el => {
            el.addEventListener('click', function(e) {
                if (!isLoggedIn) {
                    e.preventDefault();
                    openLoginModal();
                }
            });
        });

        // Report
        document.querySelectorAll('.report-check').forEach(el => {
            el.addEventListener('click', function(e) {
                if (!isLoggedIn) {
                    e.preventDefault();
                    openLoginModal();
                }
            });
        });

        // Komentar
        const komentarForm = document.getElementById('komentarForm');
        if (komentarForm && komentarForm.classList.contains('trigger-login')) {
            komentarForm.addEventListener('submit', function(e) {
                e.preventDefault();
                openLoginModal();
            });
        }
    </script>
@endsection
