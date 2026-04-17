<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-yadaYadaHashKey" crossorigin="anonymous" referrerpolicy="no-referrer" />
<header id="site-header"
    class="sticky top-0 z-50 bg-white shadow-md w-full flex items-center justify-between px-6 md:px-12 lg:px-24 transition-transform duration-300">
    <div class="container mx-auto flex justify-between items-center px-4 sm:px-6 lg:px-8 py-3">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/Media-Explant-head-Ic.svg') }}" alt="Media Explant Logo" class="h-8">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="hidden lg:flex space-x-8">
            <ul class="flex space-x-8">
                @php
                    $currentRoute = Route::currentRouteName();
                @endphp

                <!-- Beranda -->
                <li>
                    <a href="{{ route('home') }}"
                        class="relative px-4 py-2 relative top-[8px] {{ $currentRoute === 'home' ? 'text-[#990505] font-semibold' : 'text-gray-700 hover:text-[#990505]' }}">
                        Beranda
                        @if ($currentRoute === 'home')
                            <span class="absolute left-0 bottom-0 w-full h-[2px] bg-[#990505]"></span>
                        @endif
                    </a>
                </li>

                <!-- Berita -->
                <li class="relative group">
                    <button
                        class="relative px-4 py-2 {{ in_array($currentRoute, ['siaran-pers', 'riset', 'wawancara', 'diskusi', 'agenda', 'opini']) ? 'text-[#990505] font-semibold' : 'text-gray-700 hover:text-[#990505]' }}">
                        Berita
                        <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
                        @if (in_array($currentRoute, ['siaran-pers', 'riset', 'wawancara', 'diskusi', 'agenda', 'opini']))
                            <span class="absolute left-0 bottom-0 w-full h-[2px] bg-[#990505]"></span>
                        @endif
                    </button>
                    <ul
                        class="absolute left-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <li><a href="{{ route('kampus') }}" class="block px-4 py-2 hover:bg-gray-100">Kampus</a></li>
                        <li><a href="{{ route('nasional-internasional') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Nasional dan Internasional</a></li>
                        <li><a href="{{ route('liputan-khusus') }}" class="block px-4 py-2 hover:bg-gray-100">Liputan
                                Khusus</a></li>
                        <li><a href="{{ route('teknologi') }}" class="block px-4 py-2 hover:bg-gray-100">Teknologi</a>
                        </li>
                        <li><a href="{{ route('kesenian-hiburan') }}" class="block px-4 py-2 hover:bg-gray-100">Kesenian
                                dan Hiburan</a></li>
                        <li><a href="{{ route('kesehatan') }}" class="block px-4 py-2 hover:bg-gray-100">Kesehatan</a>
                        </li>
                        <li><a href="{{ route('olahraga') }}" class="block px-4 py-2 hover:bg-gray-100">Olahraga</a>
                        </li>
                        <li><a href="{{ route('opini-esai') }}" class="block px-4 py-2 hover:bg-gray-100">Opini dan
                                Esai</a></li>
                    </ul>
                </li>

                <!-- Produk -->
                <li class="relative group">
                    <button
                        class="relative px-4 py-2 {{ in_array($currentRoute, ['buletin', 'majalah']) ? 'text-[#990505] font-semibold' : 'text-gray-700 hover:text-[#990505]' }}">
                        Produk
                        <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
                        @if (in_array($currentRoute, ['buletin', 'majalah']))
                            <span class="absolute left-0 bottom-0 w-full h-[2px] bg-[#990505]"></span>
                        @endif
                    </button>
                    <ul
                        class="absolute left-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <li><a href="{{ route('buletin.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Buletin</a></li>
                        <li><a href="{{ route('majalah.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Majalah</a></li>
                    </ul>
                </li>

                <!-- Karya -->
                <li class="relative group">
                    <button
                        class="relative px-4 py-2 {{ in_array($currentRoute, ['puisi', 'pantun', 'syair', 'fotografi', 'desain-grafis']) ? 'text-[#990505] font-semibold' : 'text-gray-700 hover:text-[#990505]' }}">
                        Karya
                        <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
                        @if (in_array($currentRoute, ['puisi', 'pantun', 'syair', 'fotografi', 'desain-grafis']))
                            <span class="absolute left-0 bottom-0 w-full h-[2px] bg-[#990505]"></span>
                        @endif
                    </button>
                    <ul
                        class="absolute left-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <li><a href="{{ route('karya.puisi.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Puisi</a></li>
                        <li><a href="{{ route('karya.pantun.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Pantun</a></li>
                        <li><a href="{{ route('karya.syair.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Syair</a></li>
                        <li><a href="{{ route('karya.fotografi.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Fotografi</a>
                        </li>
                        <li><a href="{{ route('karya.desain-grafis.index') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Desain
                                Grafis</a></li>
                    </ul>
                </li>

            </ul>
        </nav>

        <!-- Overlay untuk latar belakang gelap -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <div id="searchNotifContainer"
            class="fixed top-0 right-0 w-64 h-screen bg-white shadow-lg transform translate-x-full transition-transform duration-300 p-4">
            <button id="closeSidebar" class="text-gray-500 hover:text-red-700 absolute top-4 right-4">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>

            <div class="flex flex-col space-y-4 mt-10">
                <div class="relative">
                    <input id="sidebarSearchInput" type="text" placeholder="Cari..."
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" />
                    <button id="sidebarSearchButton" class="absolute right-2 top-2 text-gray-500 hover:text-red-700">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-[#990505] font-semibold">Beranda</a>

                @if (session('user') && session('user')->role === 'Penulis')
                    <!-- Media Dropdown -->
                    <div class="group">
                        <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                            Buat <i class="fa-solid fa-chevron-down float-right"></i>
                        </button>
                        <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                            <li><a href="{{ route('create-news') }}" class="text-gray-600 hover:text-[#990505]">Buat
                                    Berita</a></li>
                            <li><a href="{{ route('create-product') }}"
                                    class="text-gray-600 hover:text-[#990505]">Tambahkan Produk</a></li>
                            <li><a href="{{ route('creation') }}" class="text-gray-600 hover:text-[#990505]">Tambahkan
                                    Karya</a></li>
                        </ul>
                    </div>
                @endif

                <!-- Berita Dropdown -->
                <div class="group">
                    <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                        Berita <i class="fa-solid fa-chevron-down float-right"></i>
                    </button>
                    <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                        <li><a href="{{ route('kampus') }}" class="text-gray-600 hover:text-[#990505]">Kampus</a>
                        </li>
                        <li><a href="{{ route('nasional-internasional') }}"
                                class="text-gray-600 hover:text-[#990505]">Nasional dan Internasional</a></li>
                        <li><a href="{{ route('liputan-khusus') }}" class="text-gray-600 hover:text-[#990505]">Liputan
                                Khusus</a>
                        <li><a href="{{ route('teknologi') }}"
                                class="text-gray-600 hover:text-[#990505]">Teknologi</a>
                        </li>
                        <li><a href="{{ route('kesenian-hiburan') }}"
                                class="text-gray-600 hover:text-[#990505]">Kesenian dan Hiburan</a>
                        </li>
                        <li><a href="{{ route('kesehatan') }}"
                                class="text-gray-600 hover:text-[#990505]">Kesehatan</a>
                        </li>
                        <li><a href="{{ route('olahraga') }}" class="text-gray-600 hover:text-[#990505]">Olahraga</a>
                        <li><a href="{{ route('opini-esai') }}" class="text-gray-600 hover:text-[#990505]">Opini dan
                                Esai</a>
                        </li>
                    </ul>
                </div>

                <!-- Produk Dropdown -->
                <div class="group">
                    <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                        Produk <i class="fa-solid fa-chevron-down float-right"></i>
                    </button>
                    <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                        <li><a href="{{ route('buletin.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Buletin</a></li>
                        <li><a href="{{ route('majalah.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Majalah</a></li>
                    </ul>
                </div>

                <!-- Karya Dropdown -->
                <div class="group">
                    <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                        Karya <i class="fa-solid fa-chevron-down float-right"></i>
                    </button>
                    <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                        <li><a href="{{ route('karya.puisi.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Puisi</a></li>
                        <li><a href="{{ route('karya.pantun.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Pantun</a></li>
                        <li><a href="{{ route('karya.syair.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Syair</a></li>
                        <li><a href="{{ route('karya.fotografi.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Fotografi</a></li>
                        <li><a href="{{ route('karya.desain-grafis.index') }}"
                                class="text-gray-600 hover:text-[#990505]">Desain
                                Grafis</a></li>
                    </ul>
                </div>

                <!-- Profil Dropdown -->
                <div class="group">
                    <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                        Profil <i class="fa-solid fa-chevron-down float-right"></i>
                    </button>

                    @if (session('user'))
                        <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                            <li>
                                <a href="{{ route('settings.umum') }}"
                                    class="text-gray-600 hover:text-[#990505]">Pengaturan</a>
                            </li>
                            <li>
                                <a href="{{ route('liked') }}" class="text-gray-600 hover:text-[#990505]">Disukai</a>
                            </li>
                            <li>
                                <a href="{{ route('bookmarked') }}"
                                    class="text-gray-600 hover:text-[#990505]">Bookmark</a>
                            </li>
                            @if (session('user')->role === 'Penulis')
                                <li>
                                    <a href="{{ route('draft-media') }}"
                                        class="text-gray-600 hover:text-[#990505]">Draf Konten</a>
                                </li>
                                <li>
                                    <a href="{{ route('published-media') }}"
                                        class="text-gray-600 hover:text-[#990505]">Publikasi Konten</a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-left text-gray-600 hover:text-[#990505] w-full">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <ul class="hidden group-hover:block mt-2 pl-4 space-y-2">
                            <li>
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#990505]">Login</a>
                            </li>
                            <li>
                                <a href="{{ route('settings.umum') }}"
                                    class="text-gray-600 hover:text-[#990505]">Pengaturan</a>
                            </li>
                        </ul>
                    @endif
                </div>

                <a href="{{ route('archive.index') }}" class="text-gray-700 hover:text-[#990505] font-semibold">Arsip</a>

                {{-- <!-- Notifikasi -->
                <div class="relative">
                    <button class="text-gray-700 hover:text-[#990505] font-semibold w-full text-left">
                        Notifikasi
                    </button>
                    <ul class="hidden mt-2 pl-4 space-y-2">
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Belum ada notifikasi</a></li>
                    </ul>
                </div> --}}
            </div>
        </div>

        <!-- Toggle Button for Sidebar -->
        <button id="toggleSearchNotif" class="lg:hidden text-gray-500 hover:text-red-700">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>

        <!-- Search Container -->
        <div id="searchContainer"
            class="fixed top-0 right-0 w-full md:w-96 h-screen bg-white shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col p-4 z-50">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Pencarian</h2>
                <button id="closeSearch" class="text-gray-500 hover:text-red-700">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <input type="text" placeholder="Ketik dan tekan enter"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">

            <!-- Preview Search Suggestions -->
            <ul id="searchSuggestions" class="mt-2 space-y-1"></ul>
        </div>

        <!-- Overlay -->
        <div id="searchOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden transition-opacity duration-300">
        </div>

        <!-- Search & Notifications -->
        <div id="searchNotifContainer"
            class="ffixed top-0 right-0 w-48 h-screen bg-white shadow-lg transform translate-x-full transition-transform duration-300 flex flex-col items-center justify-center space-y-4 lg:flex-row lg:relative lg:w-auto lg:h-auto lg:bg-transparent lg:translate-x-0 lg:shadow-none lg:space-x-4 lg:space-y-0 hidden lg:flex">
            <!-- Tombol Search -->
            <button id="searchButton" class="text-gray-500 hover:text-red-700">
                <i class="fa-solid fa-magnifying-glass text-lg"></i>
            </button>

            <a href="{{ route('archive.index') }}" class="ml-4 text-gray-500 hover:text-red-700">
                <i class="fa-solid fa-box-archive text-lg"></i>
            </a>
            {{-- <button id="notifButton" class="text-gray-500 hover:text-red-700 focus:outline-none">
                <i class="fa-solid fa-bell text-lg"></i>
            </button> --}}

            {{-- <!-- Dropdown Notifikasi -->
            <div id="notifDropdown"
                class="absolute top-10 right-2 w-64 bg-white shadow-lg rounded-lg hidden opacity-0 transition-all duration-300 transform scale-95">
                <div class="p-4 border-b">
                    <h3 class="text-gray-700 font-semibold text-sm">Notifikasi</h3>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    <!-- Contoh notifikasi -->
                    <div class="p-3 border-b hover:bg-gray-100 flex space-x-2">
                        <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-bullhorn"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 text-sm">Berita terbaru telah dipublikasikan!</p>
                            <span class="text-xs text-gray-500">2 jam yang lalu</span>
                        </div>
                    </div>
                    <div class="p-3 border-b hover:bg-gray-100 flex space-x-2">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 text-sm">Komentar baru di artikel Anda.</p>
                            <span class="text-xs text-gray-500">1 hari yang lalu</span>
                        </div>
                    </div>
                    <div class="p-3 hover:bg-gray-100 flex space-x-2">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 text-sm">Permintaan Anda telah disetujui.</p>
                            <span class="text-xs text-gray-500">3 hari yang lalu</span>
                        </div>
                    </div>
                </div>
                <div class="p-3 text-center">
                    <a href="#" class="text-red-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
            </div> --}}

            @if (session('user') && session('user')->role === 'Penulis')
                <!-- Media Dropdown -->
                <div class="relative">
                    <button id="articleButton"
                        class="flex items-center space-x-2 text-gray-700 hover:text-red-700 focus:outline-none">
                        <i
                            class="fa-solid fa-square-plus text-lg text-gray-500 hover:text-red-700 focus:outline-none"></i>
                    </button>
                    <div id="articleDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded-md hidden">
                        <a href="{{ route('create-news') }}" class="block px-4 py-2 hover:bg-gray-100">Buat
                            Berita</a>
                        <a href="{{ route('create-product') }}" class="block px-4 py-2 hover:bg-gray-100">Tambahkan
                            Produk</a>
                        <a href="{{ route('creation') }}" class="block px-4 py-2 hover:bg-gray-100">Tambahkan
                            Karya</a>
                    </div>
                </div>
            @endif

            <!-- Profil Dropdown -->
            <div class="relative z-50">
                @php
                    $userUid = Cookie::get('user_uid');
                    $user = $userUid ? \App\Models\User::where('uid', $userUid)->first() : null;
                @endphp

                <button id="profileButton" class="flex items-center focus:outline-none">
                    @if ($user && $user->profile_pic)
                        <img src="data:image/jpeg;base64,{{ base64_encode($user->profile_pic) }}" alt="Profil"
                            class="w-8 h-8 rounded-full border-2 border-red-500">
                    @else
                        <i class="fa-solid fa-user-circle text-2xl text-gray-700 hover:text-red-700"></i>
                    @endif
                </button>

                <div id="profileDropdown"
                    class="absolute right-0 mt-2 w-64 bg-white text-gray-800 shadow-lg rounded-md hidden z-50">
                    @if ($user)
                        <div class="px-4 py-4 border-b flex flex-col items-center">
                            @if ($user && $user->profile_pic)
                                <img src="data:image/jpeg;base64,{{ base64_encode($user->profile_pic) }}"
                                    alt="Profil" class="w-20 h-20 rounded-full">
                            @else
                                <i class="fa-solid fa-user-circle text-2xl text-gray-700 hover:text-red-700"></i>
                            @endif
                            <div class="text-center">
                                <p class="font-bold text-red-700 uppercase">{{ $user->nama_lengkap }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <a href="{{ route('settings.umum') }}"
                            class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <img src="https://img.icons8.com/ios-filled/24/808080/settings.png" alt="Settings Icon"
                                class="w-5 h-5 mr-3"> Pengaturan
                        </a>
                        <a href="{{ route('bookmarked') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <img src="https://img.icons8.com/ios-filled/24/808080/bookmark-ribbon.png"
                                alt="Bookmark Icon" class="w-5 h-5 mr-3"> Bookmark
                        </a>
                        <a href="{{ route('liked') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <img src="https://img.icons8.com/ios-filled/24/808080/agreement.png"
                                alt="Agreement Like Icon" class="w-5 h-5 mr-3"> Disukai
                        </a>
                        @if ($user->role === 'Penulis')
                            <a href="{{ route('draft-media') }}"
                                class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <img src="https://img.icons8.com/ios-filled/24/808080/edit-property.png"
                                    class="w-5 h-5 mr-3"> Draf Konten
                            </a>
                            <a href="{{ route('published-media') }}"
                                class="flex items-center px-4 py-2 hover:bg-gray-100">
                                <img src="https://img.icons8.com/ios-filled/24/808080/internet.png"
                                    class="w-5 h-5 mr-3"> Publikasi Konten
                            </a>
                        @endif

                        <div class="border-t my-2"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-100">
                                <i class="fa-solid fa-right-from-bracket w-5 text-gray-500 mr-3"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
                        <a href="{{ route('settings.umum') }}"
                            class="block px-4 py-2 hover:bg-gray-100">Pengaturan</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lastScrollY = window.scrollY;
        const header = document.getElementById("site-header");

        window.addEventListener("scroll", function() {
            const currentScrollY = window.scrollY;

            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scroll down, hide header
                header.style.transform = "translateY(-100%)";
            } else {
                // Scroll up, show header
                header.style.transform = "translateY(0)";
            }

            lastScrollY = currentScrollY;
        });
        // Ambil elemen dropdown
        const articleButton = document.getElementById("articleButton");
        const articleDropdown = document.getElementById("articleDropdown");
        const profileButton = document.getElementById("profileButton");
        const profileDropdown = document.getElementById("profileDropdown");
        const notifButton = document.getElementById("notifButton");
        const notifDropdown = document.getElementById("notifDropdown");

        // Fungsi untuk menutup semua dropdown, kecuali yang diklik
        function closeAllDropdowns(except = null) {
            if (articleDropdown && except !== articleDropdown) {
                articleDropdown.classList.add("hidden");
            }
            if (profileDropdown && except !== profileDropdown) {
                profileDropdown.classList.add("hidden");
            }
            if (notifDropdown && except !== notifDropdown) {
                notifDropdown.classList.add("hidden", "opacity-0", "scale-95");
                notifDropdown.classList.remove("opacity-100", "scale-100");
            }
        }

        // Artikel dropdown
        if (articleButton && articleDropdown) {
            articleButton.addEventListener("click", (e) => {
                e.stopPropagation();
                const isHidden = articleDropdown.classList.contains("hidden");
                closeAllDropdowns(articleDropdown);
                articleDropdown.classList.toggle("hidden", !isHidden);
            });
        }

        // Profil dropdown
        if (profileButton && profileDropdown) {
            profileButton.addEventListener("click", (e) => {
                e.stopPropagation();
                const isHidden = profileDropdown.classList.contains("hidden");
                closeAllDropdowns(profileDropdown);
                profileDropdown.classList.toggle("hidden", !isHidden);
            });
        }

        // Notifikasi dropdown
        // if (notifButton && notifDropdown) {
        //     notifButton.addEventListener("click", (e) => {
        //         e.stopPropagation();
        //         const isHidden = notifDropdown.classList.contains("hidden");
        //         closeAllDropdowns(notifDropdown);
        //         if (isHidden) {
        //             notifDropdown.classList.remove("hidden", "opacity-0", "scale-95");
        //             notifDropdown.classList.add("opacity-100", "scale-100");
        //         } else {
        //             notifDropdown.classList.add("hidden", "opacity-0", "scale-95");
        //             notifDropdown.classList.remove("opacity-100", "scale-100");
        //         }
        //     });
        // }

        // Klik di luar dropdown akan menutup semuanya
        document.addEventListener("click", function() {
            closeAllDropdowns();
        });

        // === SIDEBAR TOGGLE ===
        const toggleButton = document.getElementById("toggleSearchNotif");
        const closeButton = document.getElementById("closeSidebar");
        const searchNotifContainer = document.getElementById("searchNotifContainer");
        const overlay = document.getElementById("overlay");

        function openSidebar() {
            searchNotifContainer.classList.remove("translate-x-full", "hidden");
            searchNotifContainer.classList.add("translate-x-0");
            overlay.classList.remove("hidden");
            overlay.classList.add("opacity-100");
        }

        function closeSidebar() {
            searchNotifContainer.classList.add("translate-x-full");
            searchNotifContainer.classList.remove("translate-x-0");
            overlay.classList.add("hidden");
            overlay.classList.remove("opacity-100");
        }

        if (toggleButton) toggleButton.addEventListener("click", openSidebar);
        if (closeButton) closeButton.addEventListener("click", closeSidebar);
        if (overlay) overlay.addEventListener("click", closeSidebar);

        // === AUTO CLOSE SIDEBAR on LARGE SCREEN ===
        let wasSmallScreen = window.innerWidth < 1024;

        window.addEventListener('resize', () => {
            const isNowLargeScreen = window.innerWidth >= 1024;

            if (isNowLargeScreen && wasSmallScreen) {
                closeSidebar();
            }

            wasSmallScreen = window.innerWidth < 1024;
        });

        // === SEARCH TOGGLE ===
        const searchButton = document.getElementById("searchButton");
        const searchContainer = document.getElementById("searchContainer");
        const closeSearch = document.getElementById("closeSearch");
        const searchOverlay = document.getElementById("searchOverlay");

        function openSearch() {
            searchContainer.classList.remove("translate-x-full");
            searchContainer.classList.add("translate-x-0");
            searchOverlay.classList.remove("hidden");
            searchOverlay.classList.add("opacity-100");
        }

        function closeSearchFunc() {
            // Tutup sidebar pencarian
            searchContainer.classList.add("translate-x-full");
            searchContainer.classList.remove("translate-x-0");

            // Sembunyikan overlay
            searchOverlay.classList.add("hidden");
            searchOverlay.classList.remove("opacity-100");

            // Tampilkan kembali sidebar menu hanya di layar kecil (< 1024px)
            if (window.innerWidth < 1024) {
                searchNotifContainer.classList.remove("translate-x-full");
                searchNotifContainer.classList.add("translate-x-0");
            }
        }

        if (searchButton) searchButton.addEventListener("click", openSearch);
        if (closeSearch) closeSearch.addEventListener("click", closeSearchFunc);
        if (searchOverlay) searchOverlay.addEventListener("click", closeSearchFunc);

        const searchInput = document.querySelector('#searchContainer input');
        const suggestionList = document.getElementById('searchSuggestions');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            if (!query) {
                suggestionList.innerHTML = '';
                return;
            }

            fetch(`/search-preview?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    let results = data.map(item => {
                        const safeQuery = encodeURIComponent(item);
                        return `<li>
                    <a href="/search?query=${safeQuery}" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        ${item}
                    </a>
                </li>`;
                    });

                    suggestionList.innerHTML = results.join('');
                });
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query) {
                    window.location.href = `/search?query=${encodeURIComponent(query)}`;
                }
            }
        });

        const sidebarSearchInput = document.getElementById('sidebarSearchInput');
        const sidebarSearchButton = document.getElementById('sidebarSearchButton');

        function showSearchSidebar() {
            // Tutup sidebar menu jika terbuka
            if (!searchNotifContainer.classList.contains('translate-x-full')) {
                searchNotifContainer.classList.add('translate-x-full');
                searchNotifContainer.classList.remove('translate-x-0');
            }

            // Buka sidebar pencarian
            searchContainer.classList.remove("translate-x-full");
            searchContainer.classList.add("translate-x-0");

            // Tampilkan overlay
            searchOverlay.classList.remove("hidden");
            searchOverlay.classList.add("opacity-100");

            // Fokus ke input search utama
            const mainSearchInput = searchContainer.querySelector("input");
            if (mainSearchInput) mainSearchInput.focus();
        }

        // Klik icon search di sidebar
        if (sidebarSearchButton) {
            sidebarSearchButton.addEventListener('click', (e) => {
                e.preventDefault();
                showSearchSidebar();
            });
        }

        // Klik field input langsung
        if (sidebarSearchInput) {
            sidebarSearchInput.addEventListener('focus', showSearchSidebar);
        }

    });
</script>

<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
