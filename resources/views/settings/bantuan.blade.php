@extends('layouts.setting-layout')

@section('title', 'Pusat Bantuan')

@section('setting-content')

    <style>
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
    </style>

    <div class="px-6 py-6 w-full max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-red-700 mb-2">Butuh Bantuan?</h1>
        <p class="text-base text-gray-700 mb-6">
            Cari jawaban dari pertanyaan yang sering diajukan atau hubungi tim dukungan kami untuk mendapatkan bantuan lebih
            lanjut.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <input id="searchInput" type="text" placeholder="Cari bantuan..."
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-sm bg-gray-50" />

            <select id="kategori" name="kategori"
                class="w-full p-3 border border-gray-300 rounded-lg bg-white text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                <option value="semua">Semua Kategori</option>
                <option value="akun">Akun & Login</option>
                <option value="penggunaan">Penggunaan</option>
                <option value="teknis">Masalah Teknis</option>
                <option value="privasi">Privasi & Keamanan</option>
            </select>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-4">Pertanyaan yang Sering Diajukan</h2>

        @php
            $faqs = [
                // Akun & Login
                [
                    'q' => 'Bagaimana cara mendaftar akun?',
                    'a' => 'Klik tombol "Daftar" di halaman login, lalu isi formulir dengan data yang valid.',
                    'kategori' => 'akun',
                ],
                [
                    'q' => 'Bagaimana cara mengatur ulang kata sandi?',
                    'a' => 'Klik "Lupa Kata Sandi" di halaman login dan ikuti petunjuk yang dikirimkan melalui email.',
                    'kategori' => 'akun',
                ],
                [
                    'q' => 'Bagaimana cara mengganti email akun?',
                    'a' => 'Masuk ke halaman pengaturan profil, lalu ubah alamat email Anda.',
                    'kategori' => 'akun',
                ],
                [
                    'q' => 'Kenapa akun saya diblokir?',
                    'a' =>
                        'Akun bisa diblokir jika melanggar kebijakan kami. Hubungi dukungan untuk informasi lebih lanjut.',
                    'kategori' => 'akun',
                ],
                [
                    'q' => 'Bagaimana cara menghapus akun saya?',
                    'a' => 'Silakan hubungi tim dukungan kami dan ajukan permintaan penghapusan akun.',
                    'kategori' => 'akun',
                ],
                [
                    'q' => 'Apakah saya bisa memiliki lebih dari satu akun?',
                    'a' =>
                        'Kami menyarankan hanya memiliki satu akun untuk menghindari kebingungan dalam penggunaan layanan.',
                    'kategori' => 'akun',
                ],

                // Penggunaan
                [
                    'q' => 'Bagaimana cara menggunakan fitur bookmark?',
                    'a' =>
                        'Klik ikon bookmark di konten yang ingin Anda simpan. Bookmark bisa diakses dari menu profil.',
                    'kategori' => 'penggunaan',
                ],
                [
                    'q' => 'Bagaimana cara menonaktifkan notifikasi?',
                    'a' => 'Masuk ke pengaturan aplikasi, lalu matikan notifikasi sesuai preferensi Anda.',
                    'kategori' => 'penggunaan',
                ],
                [
                    'q' => 'Apa fungsi dari halaman dashboard?',
                    'a' => 'Dashboard menampilkan ringkasan aktivitas, notifikasi, dan fitur penting lainnya.',
                    'kategori' => 'penggunaan',
                ],

                // Masalah Teknis
                [
                    'q' => 'Tidak bisa login meskipun email dan password sudah benar.',
                    'a' => 'Pastikan koneksi internet stabil. Jika masih bermasalah, reset kata sandi.',
                    'kategori' => 'teknis',
                ],
                [
                    'q' => 'Kenapa halaman tidak bisa dimuat?',
                    'a' => 'Periksa koneksi internet Anda atau coba di perangkat/browser lain.',
                    'kategori' => 'teknis',
                ],
                [
                    'q' => 'Bagaimana melaporkan bug atau error?',
                    'a' => 'Gunakan formulir Hubungi Kami atau kirim email ke tim dukungan kami.',
                    'kategori' => 'teknis',
                ],

                // Privasi & Keamanan
                [
                    'q' => 'Bagaimana menjaga privasi akun saya?',
                    'a' => 'Gunakan kata sandi yang kuat dan jangan membagikannya kepada orang lain.',
                    'kategori' => 'privasi',
                ],
                [
                    'q' => 'Apa yang terjadi jika saya kehilangan perangkat?',
                    'a' => 'Segera ubah kata sandi dari perangkat lain untuk melindungi akun Anda.',
                    'kategori' => 'privasi',
                ],
                [
                    'q' => 'Bagaimana cara menonaktifkan akun sementara?',
                    'a' => 'Hubungi tim dukungan kami untuk permintaan penonaktifan akun sementara.',
                    'kategori' => 'privasi',
                ],
                [
                    'q' => 'Apakah informasi saya dibagikan ke pihak ketiga?',
                    'a' => 'Kami tidak membagikan data pribadi Anda tanpa persetujuan eksplisit.',
                    'kategori' => 'privasi',
                ],
            ];
        @endphp

        <div id="faqContainer" class="space-y-4">
            @foreach ($faqs as $index => $faq)
                <details
                    class="faq-item border border-gray-200 rounded-lg p-4 bg-gray-50 transition-all duration-200 ease-in-out"
                    data-kategori="{{ $faq['kategori'] }}" data-question="{{ strtolower($faq['q']) }}">
                    <summary class="cursor-pointer font-medium text-sm text-gray-800">{{ $faq['q'] }}</summary>
                    <p class="text-sm text-gray-600 mt-2">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>

        <p id="notFound" class="hidden text-sm text-center text-gray-500 mt-6">Tidak ada hasil yang ditemukan. Coba kata
            kunci atau kategori lain.</p>

        <div class="mt-10 text-center">
            <a href="{{ url('settings/hubungiKami') }}"
                class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-3 px-6 rounded-lg transition">
                Hubungi Tim Dukungan
            </a>
        </div>

        <p class="text-xs text-gray-500 mt-6 text-center leading-relaxed">
            Anda juga dapat mengirimkan pertanyaan melalui email resmi kami di
            <span class="text-gray-700 font-medium">ukpmexplant@journalist.com</span>. Kami akan membalas sesegera mungkin.
        </p>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const kategoriSelect = document.getElementById('kategori');
        const faqItems = document.querySelectorAll('.faq-item');
        const notFound = document.getElementById('notFound');

        function filterFAQs() {
            const searchQuery = searchInput.value.trim().toLowerCase();
            const selectedKategori = kategoriSelect.value;
            let visibleCount = 0;

            faqItems.forEach(item => {
                const question = item.dataset.question;
                const kategori = item.dataset.kategori;

                const matchKategori = selectedKategori === 'semua' || selectedKategori === kategori;
                const matchSearch = question.includes(searchQuery) || (searchQuery.length >= 2 && question.charAt(
                    0) === searchQuery.charAt(0));

                if (matchKategori && matchSearch) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            notFound.classList.toggle('hidden', visibleCount > 0);
        }

        searchInput.addEventListener('input', filterFAQs);
        kategoriSelect.addEventListener('change', filterFAQs);
    </script>

@endsection
