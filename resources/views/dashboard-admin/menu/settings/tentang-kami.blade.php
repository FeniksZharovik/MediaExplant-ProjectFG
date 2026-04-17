@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-1 py-1">
    <div class="mb-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 space-x-2" aria-label="Breadcrumb">
            <a href="/dashboard-admin" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                <i class="fa-solid fa-house mr-1"></i>
                <span>Home</span>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Setting</span>
        </nav>
        <!-- Title -->
        <h1 class="mt-3 text-2xl font-bold text-gray-800">Setting</h1>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="item-center">
            <!-- Sidebar -->
            {{-- <div class="col-span-3">
                <div class="bg-white rounded-lg shadow p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('admin.settings') }}"
                                class="flex items-center space-x-3 px-4 py-2 bg-blue-200 hover:bg-blue-100 border-l-4 border-transparent rounded-lg">
                                <i class="fa-solid fa-address-book text-blue-800"></i>
                                <span class="text-blue-800">Informasi Website</span>
                            </a>
                        </li>
                        <li>
                            <a href="/dashboard-admin/user_profile"
                                class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-50 border-l-4 border-transparent rounded-lg">
                                <i class="fa-solid fa-user text-gray-400"></i>
                                <span>Profile Admin</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> --}}
            <!-- Main Content -->
            <div class="col-span-9 bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6 text-gray-700">Informasi Website</h1>
                @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mt-4">
                    {{ session('success') }}
                </div>
                @endif
                <form id="productForm" action="{{ route('admin.tentangKami.update') }}" method="POST"
                    class="mt-6 space-y-8">
                    @csrf
                    <!-- Contact Info Card -->
                    <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">Kontak Website</h2>
                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email', $data->email ?? '') }}"
                                class="pl-10 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                placeholder="email@example.com">
                        </div>
                    </div>
                    <!-- Phone -->
                    <div class="mb-6">
                        <label for="nomorHp" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                            Telephone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="nomorHp" id="nomorHp"
                                value="{{ old('nomorHp', $data->nomorHp ?? '') }}"
                                class="pl-10 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                placeholder="+62 812-3456-7890">
                        </div>
                    </div>
                    <!-- Social Media Card -->
                    <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">Social Media</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                        $socials = ['facebook', 'youtube', 'linkedin', 'instagram'];
                        @endphp
                        @foreach($socials as $social)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 capitalize mb-2">{{ $social }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fab fa-{{ $social }} text-gray-400"></i>
                                </div>
                                <input type="text" name="{{ $social }}" value="{{ old($social, $data->$social ?? '') }}"
                                    class="pl-10 w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                    placeholder="https://{{  $social }}.com/username">
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- Website Description Card -->
                    <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-6">Deskripsi Website</h2>

                    <input type="hidden" name="tentangKami" id="tentangKamiInput"
                    value="{{ htmlspecialchars_decode(old('tentangKami', $data->tentangKami ?? ''), ENT_QUOTES) }}">
                    <input type="hidden" name="kodeEtik" id="kodeEtikInput"
                        value="{{ htmlspecialchars_decode(old('kodeEtik', $data->kodeEtik ?? ''), ENT_QUOTES) }}">
                    <input type="hidden" name="explantContributor" id="explantContributorInput"
                        value="{{ htmlspecialchars_decode(old('explantContributor', $data->explantContributor ?? ''), ENT_QUOTES) }}">
                    <input type="hidden" name="fokus_utama" id="fokusUtamaInput"
                        value="{{ htmlspecialchars_decode(old('fokus_utama', $data->fokus_utama ?? ''), ENT_QUOTES) }}">
                
                    <!-- Tentang Kami -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tentang Kami</label>
                        <div id="tentangKamiEditor" class="bg-white h-64 sm:h-96 overflow-y-auto border rounded-lg p-2">
                        </div>
                    </div>
                     <!-- Hidden Inputs with Proper Encoding -->
                     
                    <!-- Kode Etik -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Etika</label>
                        <div id="kodeEtikEditor" class="bg-white h-64 sm:h-96 overflow-y-auto border rounded-lg p-2">
                        </div>
                    </div>
                    <!-- Explant Contributor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Explant Contributor</label>
                        <div id="explantContributorEditor"
                            class="bg-white h-64 sm:h-96 overflow-y-auto border rounded-lg p-2"></div>
                    </div>
                    <!-- Fokus Utama -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fokus Utama</label>
                        <div id="fokusUtamaEditor" class="bg-white h-64 sm:h-96 overflow-y-auto border rounded-lg p-2">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="p-6 flex justify-end">
                        <button type="submit" class="bg-green-500 
                                hover:bg-indigo-500
                                text-white 
                                font-medium 
                                py-2 px-4 
                                rounded-lg 
                                transition-all 
                                duration-200 
                                ease-in-out 
                                transform 
                                hover:scale-105 
                                focus:scale-95 
                                active:scale-95">
                            Ubah Informasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quill CSS & JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Register Divider Blot
        class Divider extends Quill.import('blots/block') {}
        Divider.blotName = 'divider';
        Divider.tagName = 'hr';
        Quill.register(Divider);

        // Initialize all Quill editors
        const quillTentang = new Quill('#tentangKamiEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{
                        'header': [1, 2, false]
                    }],
                    ['link', 'image'],
                    ['divider']
                ]
            }
        });

        const quillKodeEtik = new Quill('#kodeEtikEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{
                        'header': [1, 2, false]
                    }],
                    ['link', 'image'],
                    ['divider']
                ]
            }
        });

        const quillExplant = new Quill('#explantContributorEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{
                        'header': [1, 2, false]
                    }],
                    ['link', 'image'],
                    ['divider']
                ]
            }
        });

        const quillFokusUtama = new Quill('#fokusUtamaEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{
                        'header': [1, 2, false]
                    }],
                    ['link', 'image'],
                    ['divider']
                ]
            }
        });

        // Load saved content into editors
        try {
            const tentangKami = document.getElementById('tentangKamiInput').value;
            if (tentangKami) quillTentang.clipboard.dangerouslyPasteHTML(tentangKami);

            const kodeEtik = document.getElementById('kodeEtikInput').value;
            if (kodeEtik) quillKodeEtik.clipboard.dangerouslyPasteHTML(kodeEtik);

            const explant = document.getElementById('explantContributorInput').value;
            if (explant) quillExplant.clipboard.dangerouslyPasteHTML(explant);

            const fokusUtama = document.getElementById('fokusUtamaInput').value;
            if (fokusUtama) quillFokusUtama.clipboard.dangerouslyPasteHTML(fokusUtama);
        } catch (e) {
            console.error('Error loading editor content:', e);
        }

        // Handle form submission
        document.getElementById("productForm").addEventListener("submit", function () {
            document.getElementById("tentangKamiInput").value = quillTentang.root.innerHTML;
            document.getElementById("kodeEtikInput").value = quillKodeEtik.root.innerHTML;
            document.getElementById("explantContributorInput").value = quillExplant.root.innerHTML;
            document.getElementById("fokusUtamaInput").value = quillFokusUtama.root.innerHTML;
        });
    });

</script>
@endsection
