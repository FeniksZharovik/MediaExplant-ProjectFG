<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />

    <!-- Flowbite CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.4.6/dist/flowbite.min.css" rel="stylesheet" />

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Custom CSS (e.g. scrollbar, etc.) -->
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- sweet alert pop up --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Transition for sidebar width */
        .sidebar {
            transition: width 0.3s;
        }

        /* When collapsed, we also hide text labels, submenus, etc. */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }

        .sidebar.collapsed .submenu-arrow {
            display: none;
        }

        /* Hover to expand:
       We wrap the aside in a .group so that .group:hover can expand the sidebar.
       Then we check if it’s “collapsed” but also hovered.
       If hovered, set width back to 64, show text, show submenus. */
        .group:hover .sidebar.collapsed {
            width: 16rem;
            /* w-64 */
        }

        .group:hover .sidebar.collapsed .sidebar-text {
            display: inline;
        }

        .group:hover .sidebar.collapsed .submenu-arrow {
            display: inline;
        }

    </style>
</head>

<body class="min-h-screen bg-gray-100">
    @php
        $userUid = Cookie::get('user_uid');
        $user = $userUid ? \App\Models\User::where('uid', $userUid)->first() : null;
    @endphp

    {{-- cek user admin --}}
    @if($user)
        <script>
            console.log(@json($user->nama_lengkap));
        </script>
    @endif

    <!-- ========== WRAPPER ========== -->
    <div class="flex h-screen">
        <!-- ========== SIDEBAR (LEFT) ========== -->
        <!--
      The sidebar wrapper now has "hidden md:block" so that on mobile (below md)
      it is hidden by default. The "group" still enables hover expansion on desktop.
    -->
        <aside id="sidebarWrapper" class="group relative hidden md:block">
            <div id="sidebar" class="sidebar bg-white border-r border-gray-200 w-64 h-full flex flex-col">
                <!-- Brand (Top) -->
                <a href="/dashboard-admin">
                    <div class="flex items-center px-4 py-4 border-b border-gray-200">
                        <!-- Brand Image -->
                        <img src="{{ asset('assets/Medex-M-IC.png') }}" alt="Brand Logo" class="w-10 h-10" />
                        <!-- Brand Text -->
                        <span class="sidebar-text ml-3 text-xl font-bold text-gray-700">
                            Media Explant
                        </span>
                    </div>
                </a>

                <!-- Scrollable Menu Area -->
                <nav class="flex-1 overflow-y-auto px-2 py-4">
                    <ul class="space-y-2">
                        <!-- Example Menu Item -->
                        <li>
                            <a href="/dashboard-admin"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fas fa-th-large text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    Dashboard
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard-admin/analitik/konten"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="ableIcon fa-solid fa-chart-bar text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    Analitik Konten
                                </span>
                            </a>
                        </li>
                        {{-- <li>
                            <!-- Toggle for Submenu -->
                            <div
                                class="tableToggle flex items-center justify-between px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="tableIcon fa-solid fa-chart-bar text-xl text-gray-500 w-8 text-center transition-colors duration-300"></i>
                                    <span
                                        class="tableText sidebar-text ml-1 text-base font-medium transition-colors duration-300">
                                        Analitik grafic
                                    </span>
                                </div>
                                <i
                                    class="tableChevron fas fa-chevron-down text-sm text-gray-500 submenu-arrow transition-transform duration-300"></i>
                            </div>

                            <!-- Submenu -->
                            <ul
                                class="tableSubmenu ml-12 mt-1 overflow-hidden transition-[max-height] duration-500 ease-in-out space-y-2 max-h-0">
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Analitik Konten</a>
                                </li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Analitik Pengunjung</a></li>
                                
                            </ul>
                        </li> --}}
                        <li>
                            <!-- Toggle for Submenu -->
                            <div
                                class="tableToggle flex items-center justify-between px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="tableIcon fa-solid fa-newspaper text-xl text-gray-500 w-8 text-center transition-colors duration-300"></i>
                                    <span
                                        class="tableText sidebar-text ml-1 text-base font-medium transition-colors duration-300">
                                        Manajemen Konten 
                                    </span>
                                </div>
                                <i
                                    class="tableChevron fas fa-chevron-down text-sm text-gray-500 submenu-arrow transition-transform duration-300"></i>
                            </div>

                            <!-- Submenu -->
                            <ul class="tableSubmenu ml-12 mt-1 overflow-hidden transition-[max-height] duration-500 ease-in-out space-y-2 max-h-0">
                                <li><a href="{{ route('admin.berita') }}" class="block py-2 text-gray-600 hover:text-gray-800">Berita</a>
                                </li>
                                <li><a href="{{ route('admin.karya') }}" class="block py-2 text-gray-600 hover:text-gray-800">Karya</a></li>
                                <li><a href="{{ route('admin.produk') }}" class="block py-2 text-gray-600 hover:text-gray-800">Produk</a></li>                                
                            </ul>
                        </li>
                        <!-- Tables with Submenu Example -->
                        {{-- <li>
                            <!-- Toggle for Submenu -->
                            <div
                                class="tableToggle flex items-center justify-between px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="tableIcon fa-solid fa-cube text-xl text-gray-500 w-8 text-center transition-colors duration-300"></i>
                                    <span
                                        class="tableText sidebar-text ml-1 text-base font-medium transition-colors duration-300">
                                        Produk
                                    </span>
                                </div>
                                <i
                                    class="tableChevron fas fa-chevron-down text-sm text-gray-500 submenu-arrow transition-transform duration-300"></i>
                            </div>

                            <!-- Submenu -->
                            <ul
                                class="tableSubmenu ml-12 mt-1 overflow-hidden transition-[max-height] duration-500 ease-in-out space-y-2 max-h-0">
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Bulletin</a></li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Majalah</a></li>
                            </ul>
                        </li> --}}

                        {{-- <li>
                            <!-- Toggle for Submenu -->
                            <div
                                class="tableToggle flex items-center justify-between px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="tableIcon fa-solid fa-book text-xl text-gray-500 w-8 text-center transition-colors duration-300"></i>
                                    <span
                                        class="tableText sidebar-text ml-1 text-base font-medium transition-colors duration-300">
                                        Karya
                                    </span>
                                </div>
                                <i
                                    class="tableChevron fas fa-chevron-down text-sm text-gray-500 submenu-arrow transition-transform duration-300"></i>
                            </div>

                            <!-- Submenu -->
                            <ul
                                class="tableSubmenu ml-12 mt-1 overflow-hidden transition-[max-height] duration-500 ease-in-out space-y-2 max-h-0">
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Desain Grafis</a>
                                </li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Fotografi</a></li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Pantun</a></li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Puisi</a></li>
                                <li><a href="#" class="block py-2 text-gray-600 hover:text-gray-800">Syair</a></li>
                            </ul>
                        </li> --}}

                        {{-- <li>
                            <!-- Toggle for Submenu -->
                            <div
                                class="tableToggle flex items-center justify-between px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors duration-300">
                                <div class="flex items-center gap-2">
                                    <i
                                        class="tableIcon fa-solid fa-newspaper text-xl text-gray-500 w-8 text-center transition-colors duration-300"></i>
                                    <span
                                        class="tableText sidebar-text ml-1 text-base font-medium transition-colors duration-300">
                                        Berita
                                    </span>
                                </div>
                                <i
                                    class="tableChevron fas fa-chevron-down text-sm text-gray-500 submenu-arrow transition-transform duration-300"></i>
                            </div>

                            <!-- Submenu -->
                            <ul
                                class="tableSubmenu ml-12 mt-1 overflow-hidden transition-[max-height] duration-500 ease-in-out space-y-2 max-h-0">
                                <li><a href="/dashboard-admin/berita"
                                        class="block py-2 text-gray-600 hover:text-gray-800">Berita</a></li>
                                <li>
                            </ul>
                        </li> --}}
                        <li>
                            <a href="/dashboard-admin/pengguna"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fa-solid fa-user text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    User
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard-admin/struktur-organisasi"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fa-solid fa-sitemap text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    Struktur Organisasi
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard-admin/kotak-masuk"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fa-solid fa-inbox text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    Kotak Masuk
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard-admin/settings"
                                class="flex items-center px-3 py-3 text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fa-solid fa-gears text-xl text-gray-500 w-8 text-center"></i>
                                <span class="sidebar-text ml-3 text-base font-medium">
                                    Settings
                                </span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- ========== MAIN CONTENT AREA ========== -->
        <div class="flex flex-col flex-1">
            <!-- HEADER (TOP BAR) -->
            <header class="bg-white shadow-md flex items-center justify-between px-4 py-2 z-50 relative">
                <!-- Left side: Sidebar toggle button and search bar -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile: show sidebar toggle button -->
                    <button id="toggleSidebarBtn"
                        class="w-10 h-10 md:hidden flex items-center justify-center border border-gray-300 rounded-lg text-gray-600 hover:text-gray-800 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <!-- Desktop: keep sidebar collapse toggle -->
                    <button id="toggleSidebarBtnDesktop"
                        class="w-10 h-10 hidden md:flex items-center justify-center border border-gray-300 rounded-lg text-gray-600 hover:text-gray-800 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="relative w-96">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" placeholder="Search or type command..."
                            class="pl-10 pr-20 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-gray-300" />
                        <span class="absolute inset-y-0 right-3 flex items-center text-gray-500 text-sm">
                            ⌘K
                        </span>
                    </div>
                </div>

                <!-- Right side: Notifications and Profile -->
                <div class="flex items-center space-x-4">
                    {{-- <details class="relative">
                        <summary
                            class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full text-gray-600 hover:text-gray-800 cursor-pointer focus:outline-none">
                            <i class="fas fa-bell"></i>
                            <span
                                class="absolute top-1 right-1 bg-red-500 text-white text-xs w-2 h-2 rounded-full"></span>
                        </summary>
                        <div
                            class="absolute left-1/2 transform -translate-x-1/2 mt-2 w-64 bg-white border border-gray-200 rounded shadow-lg z-10">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Notifications</h3>
                                <ul class="text-gray-600 text-sm">
                                    <li class="mb-2">No new notifications</li>
                                    <!-- Additional notifications can be added here -->
                                </ul>
                            </div>
                        </div>
                    </details> --}}

                    <div class="relative">
                        @php
                        $base64Image = $user && $user->profile_pic 
                            ? 'data:image/jpeg;base64,' . base64_encode($user->profile_pic) 
                            : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                        @endphp
                        
                        <button id="profileButton" class="flex items-center focus:outline-none">
                            <img src="{{ $base64Image }}"
                                alt="Profil" class="w-10 h-10 rounded-full object-cover border" />
                            <div class="ml-2 text-left">
                                <span class="text-gray-700 font-medium">{{ $user->nama_pengguna }}</span>
                                <br />
                                <span class="text-gray-500 text-sm">{{ $user->email }}</span>
                            </div>
                            <svg class="w-4 h-4 ml-2 text-gray-700" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 10l5 5 5-5H7z" />
                            </svg>
                        </button>
                        <!-- Profile Dropdown (ensure you have an element with id profileDropdown somewhere in your layout) -->
                        <div id="profileDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
                            <!-- Dropdown content here -->
                            {{-- <a href="/dashboard-admin/user_profile"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 overflow-y-auto p-4">
                @yield('content')

                <!-- Code Widget Section -->
                {{-- <section class="mt-8">
          <h2 class="text-xl font-bold mb-4">Code Widget</h2>
          <textarea
            class="w-full h-64 p-4 border border-gray-300 rounded resize-y"
            placeholder="Write your code here..."
          ></textarea> --}}
                </section>
            </main>

            <!-- FOOTER -->
            {{-- <footer class="bg-gray-800 text-white py-4 text-center">
        &copy; 2025 UKM Eksplan Dashboard Admin. All Rights Reserved.
      </footer> --}}
        </div>
    </div>

    <!-- ========== SCRIPTS ========== -->
    <script>
        // ========== Profile Dropdown Logic ==========
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // ========== Sidebar Toggle Logic ==========
        const sidebarWrapper = document.getElementById('sidebarWrapper');
        const sidebarEl = document.getElementById('sidebar');
        const toggleSidebarBtnMobile = document.getElementById('toggleSidebarBtn');
        const toggleSidebarBtnDesktop = document.getElementById('toggleSidebarBtnDesktop');

        // For Mobile: toggle the entire sidebar wrapper visibility
        toggleSidebarBtnMobile.addEventListener('click', () => {
            sidebarWrapper.classList.toggle('hidden');
                });

                // For Desktop: toggle collapsed state (width change)
                toggleSidebarBtnDesktop.addEventListener('click', () => {
                    sidebarEl.classList.toggle('collapsed');

                    // If collapsed, hide text, arrows, and submenus
                    if (sidebarEl.classList.contains('collapsed')) {
                        sidebarEl.classList.remove('w-64');
                        sidebarEl.classList.add('w-20');

                        // Hide text labels, arrows, and submenus
                        document.querySelectorAll('.sidebar-text, .submenu-arrow').forEach(el => el.classList.add('hidden'));

                        // Hide all submenus (using class selector) and reset max-height
                        document.querySelectorAll('.tableSubmenu').forEach(submenu => {
                            submenu.classList.add('hidden');
                            submenu.style.maxHeight = '0'; // Force collapse
                        });
                    } else {
                        sidebarEl.classList.remove('w-20');
                        sidebarEl.classList.add('w-64');

                        // Show text labels and arrows
                        document.querySelectorAll('.sidebar-text, .submenu-arrow').forEach(el => el.classList.remove('hidden'));

                        // Show submenus
                        document.querySelectorAll('.tableSubmenu').forEach(submenu => {
                            submenu.classList.remove('hidden');
                            // Optional: Restore max-height if needed for animation
                            // submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        });
                    }
        });

        // ========== Tables Submenu Toggle ==========
        const tableToggles = document.querySelectorAll('.tableToggle');

        tableToggles.forEach(toggle => {
            const submenu = toggle.nextElementSibling;
            const icon = toggle.querySelector('.tableIcon');
            const text = toggle.querySelector('.tableText');
            const chevron = toggle.querySelector('.tableChevron');

            toggle.addEventListener('click', () => {
                const isOpen = submenu.classList.contains('open');

                if (isOpen) {
                    submenu.style.maxHeight = submenu.scrollHeight +
                        'px'; // force to full height before collapse
                    requestAnimationFrame(() => {
                        submenu.style.maxHeight = '0';
                    });
                    submenu.classList.remove('open');

                    toggle.classList.remove('bg-blue-200');
                    toggle.classList.remove('hover:bg-blue-100');
                    toggle.classList.add('hover:bg-gray-100');
                    text.classList.remove('text-blue-800');
                    icon.classList.remove('text-blue-800');
                    chevron.classList.remove('text-blue-800');
                } else {
                    submenu.classList.add('open');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';

                    toggle.classList.add('bg-blue-200');
                    toggle.classList.remove('hover:bg-gray-100');
                    toggle.classList.add('hover:bg-blue-100');
                    text.classList.add('text-blue-800');
                    icon.classList.add('text-blue-800');
                    chevron.classList.add('text-blue-800');
                }

                chevron.classList.toggle('rotate-180');
            });
        });
    </script>
</body>

</html>
