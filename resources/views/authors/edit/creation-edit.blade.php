@extends('layouts.app')

@section('content')
    <main class="py-8">
        <div class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Bagian Kiri: Form Input -->
                <div class="w-full lg:flex-1 bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">🖋️ Karya Publikasi</h2>

                    <!-- Notifikasi Sukses -->
                    @if (session('success'))
                        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Notifikasi Error -->
                    @if (session('error'))
                        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="editKaryaForm" action="{{ route('karya.update', $karya->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-gray-700 font-bold">Judul</label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul', $karya->judul) }}"
                                class="mt-1 p-2 w-full border rounded-md" required>
                        </div>

                        <!-- Creator -->
                        <div class="mb-4">
                            <label for="creator" class="block text-gray-700 font-bold">Penulis / Creator</label>
                            <input type="text" id="creator" name="penulis"
                                value="{{ old('penulis', $karya->creator) }}" class="mt-1 p-2 w-full border rounded-md"
                                required>
                        </div>

                        <!-- Media -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-1">Media / Gambar</label>
                            <div id="dropArea"
                                class="relative flex items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <span id="dropText" class="text-gray-500">Klik atau seret gambar ke sini</span>
                                <input type="hidden" id="oldMedia"
                                    value="{{ $karya->media ? 'data:image/jpeg;base64,' . $karya->media : '' }}">
                                <input type="file" id="mediaInput" name="media" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <img id="previewImage"
                                    src="{{ $karya->media ? 'data:image/jpeg;base64,' . $karya->media : '' }}"
                                    class="absolute max-h-full max-w-full object-contain hidden rounded" alt="Preview">
                                <button type="button" id="removeImageBtn"
                                    class="absolute top-2 right-2 bg-white border border-gray-300 text-gray-600 rounded-full w-7 h-7 flex items-center justify-center shadow hover:bg-red-100 hover:text-red-600 hidden z-10"
                                    title="Batalkan unggahan">
                                    &times;
                                </button>
                            </div>

                            <small class="text-gray-500">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                        </div>

                        <!-- Konten -->
                        <div class="mb-4" id="kontenContainer">
                            <label for="konten" id="kontenLabel" class="block text-gray-700 font-bold">Konten</label>
                            <textarea id="konten" name="konten" class="mt-1 p-2 w-full border rounded-md h-48 overflow-auto resize-none">{{ old('konten', $karya->konten) }}</textarea>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi-editor" id="deskripsiLabel"
                                class="block text-gray-700 font-bold">Deskripsi</label>
                            <div id="deskripsi-editor" class="mt-1 p-2 w-full border rounded-md" style="min-height: 150px;">
                                {!! old('deskripsi', $karya->deskripsi) !!}
                            </div>
                            <textarea name="deskripsi" id="deskripsi" class="hidden">{{ old('deskripsi', $karya->deskripsi) }}</textarea>
                        </div>
                </div>

                <!-- Bagian Kanan: Pengaturan -->
                <div class="w-full lg:flex-1 bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">⚙️ Pengaturan Perubahan</h2>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="block text-gray-700 font-bold">Kategori</label>
                        <p class="text-gray-500 text-sm mb-2">Pilih kategori produk yang sesuai.</p>
                        <select id="kategori" name="kategori"
                            class="mt-1 p-2 w-full border rounded-md focus:ring focus:ring-blue-300" required>
                            @php
                                $kategoriList = ['puisi', 'pantun', 'syair', 'fotografi', 'desain_grafis'];
                            @endphp
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori }}" {{ $karya->kategori === $kategori ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $kategori)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Visibilitas -->
                    <div class="mb-4">
                        <span class="block text-sm font-bold text-gray-700">Visibilitas</span>
                        <p class="text-gray-500 text-sm mb-2">Atur visibilitas agar dapat dilihat oleh kelompok yang
                            diinginkan.
                        </p>
                        <div class="mt-3 flex items-center space-x-4">
                            <label class="flex items-center text-gray-700">
                                <input type="radio" name="visibilitas" value="public"
                                    {{ $karya->visibilitas === 'public' ? 'checked' : '' }} required>
                                <span class="ml-2">Public</span>
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="radio" name="visibilitas" value="private"
                                    {{ $karya->visibilitas === 'private' ? 'checked' : '' }} required>
                                <span class="ml-2">Private</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="mt-6">
                        <button type="button" id="submitBtn"
                            class="flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span id="submitBtnText">Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </main>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mediaInput = document.getElementById('mediaInput');
            const previewImage = document.getElementById('previewImage');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const oldMedia = document.getElementById('oldMedia').value;
            const dropArea = document.getElementById('dropArea');
            const kategoriSelect = document.getElementById("kategori");
            const kontenContainer = document.getElementById("kontenContainer");
            const kontenLabel = document.getElementById("kontenLabel");
            const deskripsiLabel = document.getElementById("deskripsiLabel");

            const submitBtn = document.getElementById("submitBtn");
            const submitBtnText = document.getElementById("submitBtnText");
            const form = document.getElementById('editKaryaForm');

            // SIMPAN NILAI AWAL
            const originalData = {
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('creator').value,
                kategori: kategoriSelect.value,
                konten: document.getElementById('konten') ? document.getElementById('konten').value : '',
                deskripsi: document.getElementById('deskripsi').value,
                visibilitas: document.querySelector('input[name="visibilitas"]:checked').value,
                media: oldMedia
            };

            // Saat pertama kali load gambar
            if (oldMedia) {
                previewImage.src = oldMedia;
                previewImage.classList.remove('hidden');
                removeImageBtn.classList.remove('hidden');
            }

            mediaInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.classList.remove('hidden');
                        removeImageBtn.classList.remove('hidden');
                        checkChanges(); // cek setelah upload
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeImageBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                mediaInput.value = "";
                previewImage.src = oldMedia;
                if (oldMedia) {
                    previewImage.classList.remove('hidden');
                    removeImageBtn.classList.remove('hidden');
                } else {
                    previewImage.classList.add('hidden');
                    removeImageBtn.classList.add('hidden');
                }
                checkChanges(); // cek setelah reset gambar
            });

            var quill = new Quill('#deskripsi-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['link'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }]
                    ]
                }
            });

            var deskripsiTextarea = document.getElementById('deskripsi');
            quill.root.innerHTML = deskripsiTextarea.value;

            form.addEventListener('submit', function() {
                deskripsiTextarea.value = quill.root.innerHTML;
            });

            function updateForm() {
                const kategori = kategoriSelect.value;
                deskripsiLabel.textContent = "Caption " + kategori.replace('_', ' ').replace(/\b\w/g, l => l
                    .toUpperCase());
                if (["puisi", "pantun", "syair"].includes(kategori)) {
                    kontenContainer.style.display = "block";
                    kontenLabel.textContent = "Konten " + kategori.charAt(0).toUpperCase() + kategori.slice(1);
                } else {
                    kontenContainer.style.display = "none";
                }
            }

            updateForm();
            kategoriSelect.addEventListener("change", function() {
                updateForm();
                checkChanges();
            });

            ['judul', 'creator', 'konten'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', checkChanges);
                }
            });

            document.querySelectorAll('input[name="visibilitas"]').forEach(radio => {
                radio.addEventListener('change', checkChanges);
            });

            quill.on('text-change', checkChanges);
            mediaInput.addEventListener('change', checkChanges);

            function checkChanges() {
                const currentData = {
                    judul: document.getElementById('judul').value,
                    penulis: document.getElementById('creator').value,
                    kategori: kategoriSelect.value,
                    konten: document.getElementById('konten') ? document.getElementById('konten').value : '',
                    deskripsi: quill.root.innerHTML.trim(),
                    visibilitas: document.querySelector('input[name="visibilitas"]:checked').value,
                    media: mediaInput.files.length > 0 ? 'changed' : oldMedia
                };

                let isChanged = false;
                for (let key in originalData) {
                    if (currentData[key] !== originalData[key]) {
                        isChanged = true;
                        break;
                    }
                }

                submitBtn.disabled = !isChanged;
            }

            // EVENT HANDLER UNTUK ANIMASI SAAT SUBMIT
            submitBtn.addEventListener('click', function() {
                // Pastikan textarea deskripsi sinkron dengan editor Quill
                deskripsiTextarea.value = quill.root.innerHTML;

                // Animasi tombol
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-70", "cursor-wait");
                submitBtnText.textContent = "Memperbarui...";

                // Submit form
                form.submit();
            });
        });
    </script>
@endsection
