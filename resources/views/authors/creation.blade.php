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

                    <!-- Form -->
                    <form id="karyaForm" action="{{ route('karya.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-gray-700 font-medium">Judul</label>
                            <input type="text" id="judul" name="judul"
                                class="mt-1 p-2 w-full border rounded-md focus:ring focus:ring-blue-300"
                                placeholder="Masukkan judul karya..." required>
                        </div>

                        <!-- Nama Penulis -->
                        <div class="mb-4">
                            <label for="penulis" class="block text-gray-700 font-medium">Nama Penulis</label>
                            <input type="text" id="penulis" name="penulis"
                                class="mt-1 p-2 w-full border rounded-md focus:ring focus:ring-blue-300"
                                placeholder="Masukkan nama penulis..." required>
                        </div>

                        <!-- Upload File -->
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium">Upload File</label>
                            <div id="drop-area"
                                class="border-dashed border-2 border-gray-400 p-6 rounded-md flex flex-col items-center justify-center text-center cursor-pointer relative">
                                <input type="file" id="media" name="media" accept="image/jpeg,image/png"
                                    class="hidden" required>

                                <!-- Ikon Unggah -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-500 mb-2"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 12l8-8 8 8"></path>
                                    <path d="M12 20V4"></path>
                                </svg>

                                <p class="text-gray-600 text-sm">Seret dan jatuhkan file di sini atau klik untuk
                                    unggah<br><span class="text-xs">JPG, PNG (maks. 10MB)</span></p>

                                <!-- Preview Gambar -->
                                <div id="preview-container" class="hidden mt-3 relative">
                                    <img id="imagePreview" class="w-48 h-auto rounded-md shadow-md" />
                                    <button id="removePreview" type="button"
                                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-2 py-1 text-sm">X</button>
                                </div>
                            </div>
                        </div>

                        <!-- Konten -->
                        <div class="mb-4">
                            <label for="konten" id="konten-label" class="block text-gray-700 font-medium">Konten</label>
                            <textarea id="konten" name="konten"
                                class="mt-1 p-2 w-full border rounded-md focus:ring focus:ring-blue-300 resize-none"
                                placeholder="Masukkan konten utama karya..." style="height: 200px; overflow-y: auto;"></textarea>
                        </div>

                        <!-- Caption -->
                        <div class="mb-4" id="deskripsi-container">
                            <label for="deskripsi" id="deskripsi-label"
                                class="block text-gray-700 font-medium">Caption</label>

                            <div id="quill-editor" class="mt-1 border rounded-md" style="height: 200px; overflow-y: auto;">
                            </div>
                            <input type="hidden" name="deskripsi" id="deskripsi">
                        </div>
                        <style>
                            #quill-editor .ql-editor {
                                word-break: break-word;
                                overflow-wrap: break-word;
                                word-wrap: break-word;
                            }

                            #quill-editor {
                                width: 100%;
                                max-width: 100%;
                                box-sizing: border-box;
                            }
                        </style>
                </div>

                <!-- Bagian Kanan: Pengaturan -->
                <div class="w-full lg:flex-1 bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">⚙️ Pengaturan Publikasi</h2>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="block text-gray-700 font-bold">Kategori</label>
                        <p class="text-gray-500 text-sm mb-2">Pilih kategori produk yang sesuai.</p>
                        <select id="kategori" name="kategori"
                            class="mt-1 p-2 w-full border rounded-md focus:ring focus:ring-blue-300" required>
                            <option value="puisi">Puisi</option>
                            <option value="pantun">Pantun</option>
                            <option value="syair">Syair</option>
                            <option value="fotografi">Fotografi</option>
                            <option value="desain_grafis">Desain Grafis</option>
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
                                <input type="radio" name="visibilitas" value="public" required checked>
                                <span class="ml-2">Public</span>
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="radio" name="visibilitas" value="private" required>
                                <span class="ml-2">Private</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-between">
                        <button type="button"
                            class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition flex items-center">
                            <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z">
                                </path>
                            </svg>
                            Pratinjau
                        </button>
                        <button type="submit" id="publishBtn"
                            class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition">
                            + Publikasikan
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Modal Peringatan -->
    <div id="warningModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-md shadow-md w-1/3 relative">
            <h2 class="text-xl font-semibold text-red-600">⚠️ Peringatan</h2>
            <p class="mt-3 text-gray-700">Nama penulis hanya boleh berisi huruf, tanpa angka!</p>
            <div class="mt-4 flex justify-end">
                <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-md">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Preview Mode -->
    <div id="previewMode" class="hidden fixed inset-0 bg-white z-50 overflow-auto p-6">
        <h2 id="previewKategori" class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] inline-block mb-4"
            style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)"></h2>

        <h1 id="previewJudul" class="text-2xl font-bold text-gray-800"></h1>

        <!-- Bagian thumbnail dan konten (sejajar) -->
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-shrink-0">
                <img id="previewThumbnail" class="w-80 rounded-md shadow" />
                <!-- Deskripsi dibatasi sesuai lebar gambar -->
                <div id="previewDeskripsi" class="text-gray-600 mt-2 w-80"></div>
            </div>
            <div id="previewKonten" class="flex-1 text-gray-700"></div>
        </div>

        <!-- Tombol Kembali -->
        <button id="backToEditorBtn"
            class="fixed bottom-4 left-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Kembali ke Editor
        </button>
    </div>

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <!-- Script -->
    <script>
        document.querySelector('button.bg-gray-600').addEventListener('click', function() {
            // Ambil nilai-nilai dari form
            const kategori = document.getElementById('kategori').value;
            const judul = document.getElementById('judul').value;
            const konten = document.getElementById('konten').value;
            const deskripsi = document.getElementById('quill-editor').innerHTML;

            const fileInput = document.getElementById('media');
            const thumbnail = fileInput.files[0];

            // Tampilkan Kategori
            document.getElementById('previewKategori').textContent = kategori;

            // Tampilkan Judul
            document.getElementById('previewJudul').textContent = judul;

            // Tampilkan Gambar Thumbnail
            if (thumbnail) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewThumbnail').src = e.target.result;
                }
                reader.readAsDataURL(thumbnail);
            } else {
                document.getElementById('previewThumbnail').src = '';
            }

            // Sesuaikan tampilan preview berdasarkan kategori
            if (kategori === 'puisi' || kategori === 'pantun' || kategori === 'syair') {
                document.getElementById('previewKonten').innerText = konten;
                document.getElementById('previewDeskripsi').innerHTML = deskripsi;
            } else if (kategori === 'fotografi' || kategori === 'desain_grafis') {
                document.getElementById('previewKonten').classList.add('hidden');
                document.getElementById('previewKonten').innerText = '';
                document.getElementById('previewDeskripsi').innerHTML = deskripsi;
            }

            // Sembunyikan form dan tampilkan preview
            document.getElementById('karyaForm').classList.add('hidden');
            document.getElementById('previewMode').classList.remove('hidden');
        });

        // Tombol Kembali ke Editor
        document.getElementById('backToEditorBtn').addEventListener('click', function() {
            // Sembunyikan preview, tampilkan form lagi
            document.getElementById('previewMode').classList.add('hidden');
            document.getElementById('karyaForm').classList.remove('hidden');
        });

        document.addEventListener("DOMContentLoaded", function() {
            const dropArea = document.getElementById("drop-area");
            const fileInput = document.getElementById("media");
            const imagePreview = document.getElementById("imagePreview");
            const previewContainer = document.getElementById("preview-container");
            const removePreview = document.getElementById("removePreview");

            // Klik area untuk upload
            dropArea.addEventListener("click", () => fileInput.click());

            // Preview gambar saat file dipilih
            fileInput.addEventListener("change", previewImage);

            // Hapus preview tanpa membuka dialog file
            removePreview.addEventListener("click", function(event) {
                event.stopPropagation();
                fileInput.value = "";
                previewContainer.classList.add("hidden");
            });

            // Drag & Drop
            dropArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                dropArea.classList.add("border-blue-500");
            });

            dropArea.addEventListener("dragleave", () => {
                dropArea.classList.remove("border-blue-500");
            });

            dropArea.addEventListener("drop", (e) => {
                e.preventDefault();
                dropArea.classList.remove("border-blue-500");

                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    previewImage();
                }
            });

            function previewImage() {
                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove("hidden");
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Modal Peringatan
            const modal = document.getElementById("warningModal");
            const closeModalBtn = document.getElementById("closeModal");

            function closeModal() {
                modal.classList.add("hidden");
            }

            document.getElementById("karyaForm").addEventListener("submit", function(event) {
                let penulis = document.getElementById("penulis").value;
                let regex = /^[a-zA-Z\s]+$/;

                if (!regex.test(penulis)) {
                    event.preventDefault();
                    modal.classList.remove("hidden");
                }

                const deskripsiInput = document.getElementById("deskripsi");
                deskripsiInput.value = quill.root.innerHTML;
            });

            closeModalBtn.addEventListener("click", closeModal);

            modal.addEventListener("click", function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener("keydown", function(event) {
                if (event.key === "Escape") {
                    closeModal();
                }
            });

            // Quill Editor
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Tambahkan caption di sini...',
            });

            // Update bidang input berdasarkan kategori
            const kategoriSelect = document.getElementById("kategori");
            const deskripsiContainer = document.getElementById("deskripsi-container");
            const deskripsiLabel = document.getElementById("deskripsi-label");

            const kontenLabel = document.getElementById("konten-label");
            const kontenInput = document.getElementById("konten");

            function updateFieldsByKategori() {
                const selected = kategoriSelect.value;

                // Caption selalu muncul untuk semua kategori ini
                if (["puisi", "pantun", "syair", "fotografi", "desain_grafis"].includes(selected)) {
                    deskripsiContainer.classList.remove("hidden");

                    switch (selected) {
                        case "puisi":
                            deskripsiLabel.textContent = "Caption Puisi";
                            quill.root.dataset.placeholder = "Tambahkan caption untuk puisi Anda...";
                            break;
                        case "pantun":
                            deskripsiLabel.textContent = "Caption Pantun";
                            quill.root.dataset.placeholder = "Tambahkan caption untuk pantun Anda...";
                            break;
                        case "syair":
                            deskripsiLabel.textContent = "Caption Syair";
                            quill.root.dataset.placeholder = "Tambahkan caption untuk syair Anda...";
                            break;
                        case "fotografi":
                            deskripsiLabel.textContent = "Caption Foto";
                            quill.root.dataset.placeholder = "Tambahkan caption untuk foto Anda...";
                            break;
                        case "desain_grafis":
                            deskripsiLabel.textContent = "Caption Desain";
                            quill.root.dataset.placeholder = "Tambahkan caption untuk desain Anda...";
                            break;
                    }
                }

                // Tampilkan konten hanya untuk kategori teks
                if (["puisi", "pantun", "syair"].includes(selected)) {
                    kontenInput.parentElement.classList.remove("hidden");

                    switch (selected) {
                        case "puisi":
                            kontenLabel.textContent = "Konten Puisi";
                            kontenInput.placeholder = "Masukkan puisi Anda di sini...";
                            break;
                        case "pantun":
                            kontenLabel.textContent = "Konten Pantun";
                            kontenInput.placeholder = "Masukkan pantun Anda di sini...";
                            break;
                        case "syair":
                            kontenLabel.textContent = "Konten Syair";
                            kontenInput.placeholder = "Masukkan syair Anda di sini...";
                            break;
                    }
                } else {
                    kontenInput.parentElement.classList.add("hidden");
                    kontenInput.value = "";
                }
            }

            updateFieldsByKategori();
            kategoriSelect.addEventListener("change", updateFieldsByKategori);

            // Ambil elemen-elemen input
            const judulInput = document.getElementById("judul");
            const penulisInput = document.getElementById("penulis");
            const deskripsiInput = document.getElementById("deskripsi");
            const form = document.getElementById("karyaForm");
            const publishBtn = document.getElementById("publishBtn");

            // Load dari localStorage saat load halaman
            if (localStorage.getItem("judul")) judulInput.value = localStorage.getItem("judul");
            if (localStorage.getItem("penulis")) penulisInput.value = localStorage.getItem("penulis");
            if (localStorage.getItem("deskripsi")) quill.root.innerHTML = localStorage.getItem("deskripsi");

            // Simpan ke localStorage saat mengetik
            judulInput.addEventListener("input", () => localStorage.setItem("judul", judulInput.value));
            penulisInput.addEventListener("input", () => localStorage.setItem("penulis", penulisInput.value));
            quill.on("text-change", () => localStorage.setItem("deskripsi", quill.root.innerHTML));

            // Validasi dan ubah tombol saat submit
            form.addEventListener("submit", function(e) {
                const penulis = penulisInput.value;
                const regex = /^[a-zA-Z\s]+$/;

                if (!regex.test(penulis)) {
                    e.preventDefault();
                    document.getElementById("warningModal").classList.remove("hidden");
                    return;
                }

                // Set hidden input dari Quill
                deskripsiInput.value = quill.root.innerHTML;

                // Hapus localStorage saat berhasil submit
                localStorage.removeItem("judul");
                localStorage.removeItem("penulis");
                localStorage.removeItem("deskripsi");

                // Tampilan tombol seperti create-product.blade.php
                publishBtn.disabled = true;
                publishBtn.innerHTML = `<svg class="inline w-4 h-4 mr-2 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg> Mengunggah...`;
                publishBtn.classList.add("opacity-50", "cursor-not-allowed");
            });
        });
    </script>
@endsection
