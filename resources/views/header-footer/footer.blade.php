<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Explant</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header -->
<header class="bg-[#383838] text-white py-4 w-full">
    <div class="max-w-7xl mx-auto px-6 md:px-20 flex justify-between items-center">
        <!-- Menu Navigasi di Kanan -->
        <div id="menu" class="hidden md:flex ml-auto space-x-6 text-sm transition-transform duration-300">
            <a href="{{ url('/tentang-kami') }}" class="hover:underline">Tentang Kami</a>
            <a href="{{ url('/kode-etik') }}" class="hover:underline">Kode Etik</a>
            <a href="{{ url('/explant-contributor') }}" class="hover:underline">Explant Contributor</a>
            <a href="{{ url('/struktur-organisasi') }}" class="hover:underline">Struktur Organisasi</a>
        </div>

        <!-- Tombol Menu (Mobile) di Kanan -->
        <button id="menu-btn" class="md:hidden text-white text-xl ml-auto">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<!-- Menu Sidebar untuk Mobile (Muncul dari Kanan) -->
<div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="w-64 bg-[#383838] h-full fixed right-0 top-0 p-6 transform translate-x-full transition-transform duration-300">
        <!-- Tombol Close (di Kanan Atas) -->
        <button id="close-menu" class="text-white text-xl absolute top-4 right-4">
            <i class="fas fa-times"></i>
        </button>
        <nav class="mt-10 space-y-4 text-white">
            <a href="{{ url('/tentang-kami') }}" class="block hover:underline">Tentang Kami</a>
            <a href="{{ url('/kode-etik') }}" class="block hover:underline">Kode Etik</a>
            <a href="{{ url('/explant-contributor') }}" class="block hover:underline">Explant Contributor</a>
            <a href="{{ url('/struktur-organisasi') }}" class="block hover:underline">Struktur Organisasi</a>
        </nav>
    </div>
</div>

<!-- Footer -->
<footer class="bg-[#2A2A2A] text-white py-12 w-full">
    <div class="max-w-7xl mx-auto px-6 md:px-20 grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        <!-- Logo -->
        <div class="flex justify-center md:justify-start">
            <img src="{{ asset('assets/ukpm-explant-icF.png') }}" alt="Logo Explant" class="w-44 md:w-52">
        </div>

        <!-- Tentang Kami -->
        <div class="text-left">
            <h3 class="text-lg font-bold text-[#C63232]">Tentang Kami</h3>
            <div class="w-16 border-t-2 border-[#C63232] my-3"></div>
            <p class="text-sm leading-relaxed">
                Unit Kegiatan Pers Mahasiswa (UKPM) yang berfokus pada jurnalistik kampus. UKPM Explant bertugas sebagai
                media informasi bagi mahasiswa Polije, meliputi berita kampus, opini, dan berbagai artikel lainnya.
            </p>
            <h4 class="font-semibold mt-6 text-[#C63232]">Ikuti Kami</h4>
            <div class="w-16 border-t-2 border-[#C63232] my-3"></div>
            <div class="flex space-x-4 text-[#C63232] text-xl">
                <a href="https://www.facebook.com/ukmexplant/"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/ukpmexplant/"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.youtube.com/channel/UC-vKIVeYs5vzocMFmrbe7ew/"><i class="fa-brands fa-youtube"></i></a>
                <a href="https://id.linkedin.com/company/ukpmexplantpolije"><i class="fab fa-linkedin"></i></a>
            </div>
            <div class="mt-4 text-sm space-y-2">
                <p><i class="fa-solid fa-phone"></i> +6221 350 0584, +6221 351 1086</p>
                <p><i class="fa-solid fa-envelope"></i> ukpmexplant@journalist.com</p>
            </div>
        </div>

        <!-- Alamat Kami -->
        <div class="text-left">
            <h3 class="text-lg font-bold text-[#C63232]">Alamat Kami</h3>
            <div class="w-16 border-t-2 border-[#C63232] my-3"></div>
            <p class="text-sm">
                <i class="fa-solid fa-map-marker-alt"></i> Jl. Mastrip, Krajan Timur, Sumbersari, Kec. Sumbersari,
                Kabupaten Jember, Jawa Timur 68121
            </p>
            <div class="mt-4">
                <iframe class="w-full h-40 md:h-52 rounded-lg"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.4197290716543!2d113.7236985!3d-8.1603975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695b625d4f5bd%3A0xd1500f0198d82891!2sOmah%20Explant!5e0!3m2!1sid!2sid!4v1742134057064!5m2!1sid!2sid"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Garis pemisah -->
    <div class="border-t border-gray-600 mt-12 mx-auto w-[90%]"></div>

    <!-- Copyright -->
    <div class="text-center text-sm mt-4 px-6">
        &copy; MediaExplant. All rights reserved.
    </div>
</footer>

<!-- Script untuk animasi menu -->
<script>
    const menuBtn = document.getElementById('menu-btn');
    const closeMenu = document.getElementById('close-menu');
    const mobileMenu = document.getElementById('mobile-menu');
    const sidebar = mobileMenu.querySelector('div');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
        setTimeout(() => sidebar.classList.remove('translate-x-full'), 50);
    });

    closeMenu.addEventListener('click', () => {
        sidebar.classList.add('translate-x-full');
        setTimeout(() => mobileMenu.classList.add('hidden'), 300);
    });

    // Tutup menu jika klik di luar sidebar
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMenu.click();
        }
    });
</script>

</body>
</html>
