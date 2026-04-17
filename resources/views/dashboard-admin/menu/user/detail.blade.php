@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center text-sm text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-house mr-1"></i>
                <span>Home</span>
            </a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="/dashboard-admin/pengguna" class="text-gray-600 hover:text-blue-600">Pengguna</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Detail</span>
        </nav>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">Detail Akun</h1>
    </div>

    <!-- Tabs Navigation -->
    <div class="p-6 bg-white shadow rounded-lg">
        <h1 class="mt-3 text-2xl font-bold text-gray-800 mb-5">Meta Data</h1>

        <div class="flex items-center space-x-6 mb-5">
            <!-- Profile Image -->
            <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                @php
                $base64Image = $user && $user->profile_pic
                ? 'data:image/jpeg;base64,' . base64_encode($user->profile_pic)
                : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                @endphp
                <img src="{{ $base64Image }}" alt="Profile Picture" class="w-full h-full object-cover">
            </div>

            <!-- User Info -->
            <div class="space-y-2">
                <p class="text-sm text-gray-600"><strong>UID:</strong> {{ $user->uid }}</p>
                <p class="text-sm text-gray-600"><strong>Nama Lengkap:</strong> {{ $user->nama_pengguna }}</p>
                <p class="text-sm text-gray-600"><strong>Nama Lengkap:</strong> {{ $user->nama_lengkap }}</p>
                <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="text-sm text-gray-600"><strong>Role:</strong> {{ $user->role }}</p>
            </div>
        </div>

        <!-- Tab Buttons -->
        <div class="flex space-x-4 border-b mb-4 pb-1" id="tabNav">
            {{-- <button data-tab="bookmark"
                class="tab-btn group py-2 px-4 focus:outline-none flex items-center relative text-gray-600">
                <i class="fas fa-bookmark mr-1 transition-colors duration-200"></i>
                <span class="transition-colors duration-200">Bookmark</span>
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 origin-left group-hover:scale-x-100 transition-transform duration-300"></span>
            </button> --}}

            <button data-tab="reaksi"
                class="tab-btn group py-2 px-4 focus:outline-none flex items-center relative text-gray-600">
                <i class="fas fa-smile mr-1 transition-colors duration-200"></i>
                <span class="transition-colors duration-200">Reaksi</span>
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 origin-left group-hover:scale-x-100 transition-transform duration-300"></span>
            </button>

            <button data-tab="comment"
                class="tab-btn group py-2 px-4 focus:outline-none flex items-center relative text-gray-600">
                <i class="fas fa-comment mr-1 transition-colors duration-200"></i>
                <span class="transition-colors duration-200">Komentar</span>
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 origin-left group-hover:scale-x-100 transition-transform duration-300"></span>
            </button>

            {{-- @if ($user->role == "Penulis")
            <button data-tab="media"
                class="tab-btn group py-2 px-4 focus:outline-none flex items-center relative text-gray-600">
                <i class="fas fa-photo-video mr-1 transition-colors duration-200"></i>
                <span class="transition-colors duration-200">Media</span>
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-600 transform scale-x-0 origin-left group-hover:scale-x-100 transition-transform duration-300"></span>
            </button>
            @endif --}}
        </div>

        <!-- Tab Content -->
        <div id="tabContent" class="mt-4">
            <div id="reaksi" class="tab-pane hidden">
                @include('dashboard-admin.menu.user.partials.reaksi')
            </div>
            <div id="comment" class="tab-pane hidden">
                @include('dashboard-admin.menu.user.partials.comment')
            </div>
            {{-- <div id="bookmark" class="tab-pane hidden">
                @include('dashboard-admin.menu.user.partials.bookmark')
            </div> --}}
            {{-- <div id="media" class="tab-pane hidden">
                @include('dashboard-admin.menu.user.partials.media')
            </div> --}}
        </div>
    </div>
</div>

<!-- JavaScript for Tab Handling -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.tab-btn');
    const panes = document.querySelectorAll('.tab-pane');

    // Get the saved tab from sessionStorage or fallback to 'reaksi'
    let activeTab = sessionStorage.getItem('activeTab') || 'reaksi';

    // Show the saved tab
    showTab(activeTab);

    // Function to show a specific tab
    function showTab(tabId) {
        // Hide all panes
        panes.forEach(pane => pane.classList.add('hidden'));

        // Reset all buttons
        buttons.forEach(btn => {
            btn.classList.remove('text-blue-600');
            const icon = btn.querySelector('i');
            const textSpan = btn.querySelector('span');
            if (icon) icon.classList.remove('text-blue-600');
            if (textSpan) textSpan.classList.remove('text-blue-600');
            const underline = btn.querySelector('.absolute');
            if (underline) underline.classList.remove('scale-x-100');
        });

        // Show selected tab
        const targetPane = document.getElementById(tabId);
        if (targetPane) targetPane.classList.remove('hidden');

        const targetButton = document.querySelector(`[data-tab="${tabId}"]`);
        if (targetButton) {
            targetButton.classList.add('text-blue-600');
            const icon = targetButton.querySelector('i');
            const textSpan = targetButton.querySelector('span');
            if (icon) icon.classList.add('text-blue-600');
            if (textSpan) textSpan.classList.add('text-blue-600');
            const underline = targetButton.querySelector('.absolute');
            if (underline) underline.classList.add('scale-x-100');
        }

        // Save the selected tab in sessionStorage
        sessionStorage.setItem('activeTab', tabId);
    }

    // Button click handler
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.getAttribute('data-tab');
            showTab(target);
        });
    });

    // Initial tab show
    showTab(activeTab);
});
</script>
@endsection