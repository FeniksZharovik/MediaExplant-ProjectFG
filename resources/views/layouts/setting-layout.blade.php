<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/ukpm-explant-ic.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 h-screen overflow-hidden">

    <!-- Header Sticky -->
    <div class="sticky top-0 z-50 bg-white border-b px-6 py-4 flex items-center">
        <a href="{{ session('settings_previous_url', url('/')) }}" class="flex items-center">
            <img src="{{ asset('assets/Medex-M-IC.png') }}" alt="Logo" class="w-6 h-6 mr-2">
            <h1 class="text-lg font-semibold text-red-600">Pengaturan</h1>
        </a>
    </div>

    <!-- Layout Container -->
    <div class="flex h-[calc(100vh-64px)]"> {{-- 64px: tinggi header --}}
        <!-- Sidebar Sticky -->
        <div class="w-60 bg-gray-100 p-4 border-r sticky top-[64px] h-[calc(100vh-64px)] overflow-y-auto">
            <nav class="flex flex-col gap-4">
                <a href="{{ route('settings.umum') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-user"></i> Akun
                </a>
                {{-- <a href="{{ route('settings.notifikasi') }}"
                   class="flex items-center gap-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-bell"></i> Notifikasi
                </a> --}}
                <a href="{{ route('settings.bantuan') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-question-circle"></i> Pusat Bantuan
                </a>
                <a href="{{ route('settings.hubungiKami') }}" class="flex items-center gap-2 text-gray-600 hover:text-blue-600">
                    <i class="fas fa-clipboard-question"></i> Hubungi Kami
                </a>
            </nav>
        </div>

        <!-- Main Content Scrollable -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('setting-content')
        </div>
    </div>

</body>
</html>
