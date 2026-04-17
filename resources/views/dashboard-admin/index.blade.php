@extends('layouts.admin-layouts')

@section('content')
{{-- @if(auth()->check() && auth()->user()->role == 'Admin') --}}

<div class="container mx-auto px-1 py-1">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <!-- Icon on the left -->
            <i class="fa-solid fa-user text-3xl text-blue-500 bg-blue-100 p-3 rounded-lg shadow-sm mr-4"></i>
            <!-- Text Content to the right of the icon -->
            <div>
                <p class="text-sm text-gray-500">Total Pembaca</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$pembacaCount ?? 0}}">{{$pembacaCount ?? 0}}</p>
            </div>
        </div>
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <!-- Icon on the left -->
            <i class="fa-solid fa-pencil text-3xl text-pink-500 bg-pink-100 p-3 rounded-lg shadow-sm mr-4"></i>

            <!-- Text Content to the right of the icon -->
            <div>
                <p class="text-sm text-gray-500">Total Penulis</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$penulisCount ?? 0}}">{{$penulisCount ?? 0}}</p>
            </div>
        </div>
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <!-- Icon on the left -->
            <i class="fa-solid fa-user-tie text-3xl text-purple-500 bg-purple-100 p-3 rounded-lg shadow-sm mr-4"></i>

            <!-- Text Content to the right of the icon -->
            <div>
                <p class="text-sm text-gray-500">Total Anggota Organisasi</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$anggotaCount ?? 0}}">{{$anggotaCount ?? 0}}</p>
            </div>
        </div>
        <!-- Total Berita -->
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <i class="fa-solid fa-newspaper text-3xl text-green-500 bg-green-100 p-3 rounded-lg shadow-sm mr-4"></i>
            <div>
                <p class="text-sm text-gray-500">Total Berita</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$beritaCount ?? 0}}">{{$beritaCount ?? 0}}</p>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <i class="fa-solid fa-cube text-3xl text-yellow-500 bg-yellow-100 p-3 rounded-lg shadow-sm mr-4"></i>
            <div>
                <p class="text-sm text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$produkCount ?? 0}}">{{$produkCount ?? 0}}</p>
            </div>
        </div>

        <!-- Total Karya -->
        <div class="relative flex items-center rounded-lg bg-white h-28 shadow-md p-4">
            <i class="fa-solid fa-book text-3xl text-red-500 bg-red-100 p-3 rounded-lg shadow-sm mr-4"></i>
            <div>
                <p class="text-sm text-gray-500">Total Karya</p>
                <p class="text-2xl font-bold counter-number-animation" data-target="{{$KaryaCount ?? 0}}">{{$KaryaCount ?? 0}}</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- Left Side: KOTAK PESAN -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-4 h-96 flex flex-col justify-between">
                <!-- Header -->
                <div class="flex items-center space-x-2">
                    <h2 class="text-xl font-bold text-gray-700">Pesan Terbaru</h2>
                </div>
        
                <!-- Tab Navigation -->
                <ul id="messageTabs" class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 mt-5">
                    <li class="me-2">
                        <a href="#" data-filter="all" class="inline-block p-4 text-blue-600 bg-gray-100 rounded-t-lg active">Selengkapnya</a>
                    </li>
                    {{-- <li class="me-2">
                        <a href="#" data-filter="masukan" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50">Masukan</a>
                    </li>
                    <li class="me-2">
                        <a href="#" data-filter="laporan" class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50">Laporan</a>
                    </li> --}}
                </ul>
        
                <!-- Scrollable Message Container -->
                <div class="mt-4 flex-1 overflow-y-auto pr-2">
                    <!-- Message List -->
                    <div id="messagesContainer" class="space-y-1">
                        @foreach ($pesans as $pesan)
                            <div class="flex items-center justify-between py-3 hover:bg-gray-50 rounded-lg px-2 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="font-medium text-gray-800 truncate">
                                        @if ($pesan->user)
                                            {{ $pesan->user->nama_pengguna }}
                                        @else
                                            {{ $pesan->nama }}
                                        @endif
                                    </span>
                                    <span class="text-sm text-gray-500 truncate max-w-xs">
                                        @if ($pesan->pesan)
                                            {{ Str::limit($pesan->pesan, 100) }}
                                        @else
                                            {{ Str::limit($pesan->detail_pesan, 100) }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if (\Carbon\Carbon::parse($pesan->created_at)->diffInHours(now()) < 24)
                                        <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">Terbaru</span>
                                    @endif
        
                                    @if ($pesan->status === 'laporan')
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Laporan</span>
                                    @elseif ($pesan->status === 'masukan')
                                        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">Masukan</span>
                                    @endif
        
                                    <span class="text-sm text-gray-400">{{ date('M j, Y', strtotime($pesan->created_at)) }}</span>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
        <script>
            $(document).ready(function () {
                // Initial state
                var currentFilter = 'all';
        
                // Handle tab click
                $('#messageTabs a').on('click', function (e) {
                    e.preventDefault();
        
                    // Get the filter value from data attribute
                    var filter = $(this).data('filter');
        
                    // Update current filter
                    currentFilter = filter;
        
                    // Remove active class from all tabs
                    $('#messageTabs a').removeClass('active');
        
                    // Add active class to the clicked tab
                    $(this).addClass('active');
        
                    // Fetch filtered messages using AJAX
                    $.ajax({
                        url: '/dashboard-admin/index?filter=' + filter,
                        type: 'GET',
                        success: function (response) {
                            // Update message container with new data
                            $('#messagesContainer').html(response);
                        },
                        error: function () {
                            alert('Failed to load messages.');
                        }
                    });
                });
            });
        </script>
        <!-- Right Side -->
        <div class="grid grid-rows-2 gap-4">
            <!-- Card 1: Analitik Pengunjung -->
            <div class="bg-gray-500 rounded-lg shadow-md p-4 flex flex-col justify-center items-center">
                <div>
                    <!-- Live Clock -->
                    <div id="liveClock" class="text-center text-white">
                        <div id="clockTime" class="text-5xl font-bold"></div>
                        <div id="clockDate" class="mt-2 text-xl font-medium"></div>
                    </div>
                </div>
            </div>

            <!-- Add this script at the bottom of your Blade file or in a separate JS file -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const clockTime = document.getElementById('clockTime');
                    const clockDate = document.getElementById('clockDate');

                    // Function to update the clock
                    function updateClock() {
                        const now = new Date();

                        // Format time (e.g., 4:00:28 PM)
                        const optionsTime = {
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric',
                            hour12: true
                        };
                        const formattedTime = now.toLocaleTimeString(undefined, optionsTime);

                        // Format date (e.g., June 1, 2025)
                        const optionsDate = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        const formattedDate = now.toLocaleDateString(undefined, optionsDate);

                        // Update the DOM
                        clockTime.textContent = formattedTime;
                        clockDate.textContent = formattedDate;
                    }

                    // Update the clock every second
                    setInterval(updateClock, 1000);

                    // Initial update
                    updateClock();
                });
            </script>

            <!-- Card 2: Analitik Konten -->
            <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-700">Analitik Grafik</h3>
                    <p class="mt-2 text-gray-600 text-md">
                        Telusuri performa konten berdasarkan jumlah tayangan, interaksi pengguna, waktu baca rata-rata,
                        dan konten yang paling populer di situs Anda.
                    </p>
                </div>
                <a href="/dashboard-admin/analitik/konten" class="text-sm text-blue-600 mt-4 hover:underline">Lihat
                    Selengkapnya</a>
            </div>
        </div>
    </div>
    <!-- Total Pengungjung  -->
    {{-- <div class="rounded-lg shadow-md flex flex-col h-[500px] mb-4 rounded-sm bg-white overflow-hidden w-full">
        <!-- Header: Title and Date Select -->
        <div class="flex justify-between items-center px-4 py-2">
            <!-- Left Side: Title -->
            <div class="flex items-center space-x-2">
                <h2 class="text-xl font-bold text-gray-700">Total Akun Login 7 hari ini</h2>
                <!-- Icon (if present) -->
                <i class="fas fa-info-circle text-gray-500 ml-2"></i> <!-- Example icon -->
            </div>

            <!-- Right Side: Select Dropdown -->
            <select class="border rounded-xl pr-10 py-1 text-gray-600">
                <option>7 hari ini</option>
                <option>Bulan ini</option>
                <option>Tahun ini</option>
            </select>
        </div>
        <div class="flex justify-between items-center px-4 py-2">
            <h2 class="text-sm font-bold text-gray-700">Total Pengunjung</h2>
        </div>
        <!-- Chart Container -->
        <div class="flex-grow relative">
            <canvas id="chart1-area" class="w-full max-h-80 max-w-full px-10 mt-10"></canvas>
        </div>
    </div> --}}

    <!-- Most Search  -->
    {{-- <div class="rounded-lg shadow-md flex flex-col h-[500px] mb-4 rounded-sm bg-white overflow-hidden w-full">
        <!-- Header: Title and Date Select -->
        <div class="flex justify-between items-center px-4 py-2">
            <h2 class="text-xl font-bold text-gray-700">Topic Paling Dicari </h2>
            <select class="border rounded px-3 py-1 text-gray-600">
            <option>Dec 31 – Jan 31</option>
            <option>Feb 1 – Feb 28</option>
            <option>Mar 1 – Mar 31</option>
        </select>
        </div>
        <!-- Chart Container -->
        <div class="flex-grow relative">
            <canvas id="chart1-area" class="w-full max-h-80 max-w-full px-10 mt-10"></canvas>
        </div>
    </div> --}}
    <!-- Content Terpopular -->
    {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 ">
        <div class="flex flex-col h-96 rounded-lg shadow-md bg-white p-4">
            <div class="text-xl font-semibold mb-4">Berita Terpopular</div>
            <div class="overflow-auto flex-1">
                <div class="flex items-start space-x-4">
                    <img src="https://www.persma.id/wp-content/uploads/2024/09/1-696x557.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Memahami Payung Hukum dan
                            Perlindungan Pers Mahasiswa.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://www.persma.id/wp-content/uploads/2024/09/1-696x557.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">Majelis Hakim Tidak Progresif dalam
                    Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.</p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://www.persma.id/wp-content/uploads/2024/09/1-696x557.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">Majelis Hakim Tidak Progresif dalam
                    Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.</p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://www.persma.id/wp-content/uploads/2024/09/1-696x557.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">Majelis Hakim Tidak Progresif dalam
                    Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.</p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://www.persma.id/wp-content/uploads/2024/09/1-696x557.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">Majelis Hakim Tidak Progresif dalam
                    Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.</p>
                </div>
                <!-- Tambahkan lebih banyak konten di sini -->
            </div>
        </div>

        <div class="flex flex-col h-96 rounded-lg shadow-md bg-white p-4">
            <div class="text-xl font-semibold mb-4">Etalase Terpopular</div>
            <div class="overflow-auto flex-1">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Memahami Payung Hukum dan
                            Perlindungan Pers Mahasiswa.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://ebooks.gramedia.com/ebook-covers/50298/image_highres/ID_AW2020MTH01AW.jpg" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <!-- Tambahkan lebih banyak konten di sini -->
            </div>
        </div>
        <div class="flex flex-col h-96 rounded-lg shadow-md bg-white p-4">
            <div class="text-xl font-semibold mb-4">Karya Terpopular</div>
            <div class="overflow-auto flex-1">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Memahami Payung Hukum dan
                            Perlindungan Pers Mahasiswa.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div> <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <hr class="my-3">
                <div class="flex items-start space-x-4">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTflVfITKyWM-oRurgCWuo8IKuC__b-D462Ig&s" alt="" class="w-24 h-auto rounded-sm object-cover" />
                    <p class="text-2xl cursor-pointer text-gray-500 hover:text-gray-800">
                        Majelis Hakim Tidak Progresif dalam Memahami Legal Standing Penggugat dalam Gugatan Pembekuan Lembaga Pers Mahasiswa Lintas.
                    </p>
                </div>
                <!-- Tambahkan lebih banyak konten di sini -->
            </div>
        </div>
    </div> --}}

    {{-- 
    <!-- Third grid: 2 columns (responsive: 1 column on small, 2 columns on md and up) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">+</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">+</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">+</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">+</p>
        </div>
    </div>

    <!-- Fourth section: Full-width block remains as is -->
    <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50">
        <p class="text-2xl text-gray-400">+</p>
    </div>

    <!-- Fifth grid: 2 columns (responsive: 1 column on small, 2 columns on md and up) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">1</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">2</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">3</p>
        </div>
        <div class="flex items-center justify-center rounded-sm bg-gray-50 h-28">
            <p class="text-2xl text-gray-400">4</p>
        </div>
    </div> --}}
</div>



<!-- Chart.js Setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chart1-area');
        const ctx = canvas.getContext('2d');

        // Gradients
        const gradientBlue = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradientBlue.addColorStop(0, 'rgba(54, 162, 235, 0.6)');
        gradientBlue.addColorStop(1, 'rgba(54, 162, 235, 0.1)');

        const gradientOrange = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradientOrange.addColorStop(0, 'rgba(255, 159, 64, 0.6)');
        gradientOrange.addColorStop(1, 'rgba(255, 159, 64, 0.1)');
        // Sample Data
        const labels = ['Mei 26', 'Mei 27', 'Mei 28', 'Mei 29', 'Mei 30', 'Mei 31', 'Juni 1'];
        const dataBlue = [10, 5, 7, 7, 13, 18, 22];
        const dataOrange = [10, 6, 9, 5, 15, 20, 20];

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Pengunjung',
                        data: dataBlue,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradientBlue,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                    },
                    {
                        label: 'Pengunjung (periode sebelumnya)',
                        data: dataOrange,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradientOrange,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(255, 159, 64, 1)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    x: {
                        grid: {
                            // display: false,
                            // drawBorder: true
                        }
                    },
                    y: {
                        // display: false,
                        // grid: {
                        //     display: false
                        // }
                    }
                }
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const counters = document.querySelectorAll('.counter-number-animation');

        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let count = 0;
            const speed = 100; // Semakin kecil, semakin cepat

            const updateCount = () => {
                const increment = Math.ceil(target / speed);
                count += increment;

                if (count < target) {
                    counter.textContent = count.toLocaleString();
                    requestAnimationFrame(updateCount);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };

            updateCount();
        });
    });

</script>

{{-- @else
    <p>You do not have permission to access this page.</p>
@endif --}}
@endsection
