@extends('layouts.app')

@section('content')
    <div class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Bagian Kiri: Tambahkan Produk -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    📖 <span class="ml-2">Tambahkan Produk</span>
                </h2>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="productForm" action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Judul -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Judul</label>
                        <input type="text" name="judul" required placeholder="Masukkan judul produk"
                            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Upload Cover -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Unggah Cover</label>

                        <div id="cover-drop-area"
                            class="border-dashed border-2 border-gray-300 p-4 text-center cursor-pointer relative rounded-lg hover:border-blue-500 transition">
                            <p class="text-gray-500 mb-2">Klik di sini untuk mengunggah</p>
                            <input type="file" name="cover" id="coverInput" accept=".jpg,.jpeg,.png" class="hidden"
                                required>
                            <p id="cover-error" class="text-red-500 text-sm mt-1 hidden"></p>

                            <!-- Preview Container -->
                            <div id="cover-preview"
                                class="relative mx-auto max-w-xs rounded overflow-hidden shadow-md hidden">
                                <img id="cover-preview-img" src="" alt="Preview Cover"
                                    class="w-full h-auto object-cover rounded" />
                                <button type="button" id="cover-clear-btn"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700 transition">
                                    &times;
                                </button>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Hanya gambar JPG/PNG, maks 10 MB</p>
                    </div>

                    <!-- Media Upload -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Unggah File</label>
                        <div id="drop-area"
                            class="border-dashed border-2 border-gray-300 p-6 text-center cursor-pointer relative">
                            <p class="text-gray-500">letakkan file di sini atau klik untuk unggah</p>
                            <input type="file" name="media" required class="hidden" id="fileInput" accept=".pdf">
                            <p id="media-error" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Hanya file PDF, maks 10 MB</p>
                        <p id="file-name" class="text-gray-500 mt-2"></p>

                        <!-- Preview File -->
                        <div id="file-preview" class="mt-4 hidden">
                            <p class="text-gray-700 font-semibold">Pratinjau File:</p>
                            <iframe id="preview-frame" class="w-full h-64 border rounded-lg"></iframe>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Deskripsi</label>
                        <div id="editor-container"
                            class="bg-white min-h-[240px] max-h-[240px] overflow-y-auto border rounded-lg p-2"></div>
                        <input type="hidden" name="deskripsi" id="deskripsi">
                    </div>
            </div>

            <!-- Bagian Kanan: Pengaturan Publikasi -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                    ⚙️ <span class="ml-2">Pengaturan Publikasi</span>
                </h2>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-1">Kategori</label>
                    <p class="text-gray-500 text-sm mb-1">Pilih kategori produk yang sesuai.</p>
                    <select name="kategori" required class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="Buletin">Buletin</option>
                        <option value="Majalah">Majalah</option>
                    </select>
                </div>

                <!-- Visibilitas -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-1">Visibilitas</label>
                    <p class="text-gray-500 text-sm mb-1">Atur visibilitas agar dapat dilihat oleh kelompok yang diinginkan.
                    </p>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="visibilitas" value="public" checked class="focus:ring-blue-500">
                            <span>Public</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="visibilitas" value="private" class="focus:ring-blue-500">
                            <span>Private</span>
                        </label>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex justify-between">
                    <button type="button" id="previewBtn"
                        class="bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z">
                            </path>
                        </svg>
                        Pratinjau
                    </button>
                    <button type="submit" id="submit-btn"
                        class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition">
                        + Publikasikan
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- MODE PRATINJAU -->
    <div id="previewMode" class="hidden fixed inset-0 bg-white overflow-y-auto z-50 p-6">
        <div class="max-w-4xl mx-auto">

            <div class="mb-4">
                <h2 id="previewKategori" class="text-white px-8 py-1 bg-[#9A0605] inline-block font-semibold"
                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                </h2>
            </div>

            <h1 id="previewJudul" class="text-3xl font-bold text-gray-800 mb-4"></h1>

            <div class="w-full mb-6" id="previewCoverContainer">
                <img id="previewCover" src="" class="rounded-lg shadow-md w-full max-h-96 object-cover" />
            </div>

            <div id="previewDeskripsi" class="prose max-w-none"></div>
        </div>

        <!-- Tombol Kembali -->
        <button type="button" id="backToEditor"
            class="fixed bottom-4 left-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition shadow-md">
            ← Kembali ke Editor
        </button>
    </div>

    <!-- Quill CSS & JS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        // Inisialisasi Quill Editor
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tulis deskripsi produk di sini...',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['link', ],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }]
                ]
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("productForm");
            const submitBtn = document.getElementById("submit-btn");

            form.addEventListener("submit", function(e) {
                // Masukkan isi Quill ke hidden input sebelum submit
                document.getElementById("deskripsi").value = quill.root.innerHTML;

                // Ganti tampilan tombol & nonaktifkan
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<svg class="inline w-4 h-4 mr-2 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg> Mengunggah...`;

                submitBtn.classList.add("opacity-50", "cursor-not-allowed");
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('previewBtn');
            const backToEditorBtn = document.getElementById('backToEditor');
            const form = document.querySelector('form');
            const previewMode = document.getElementById('previewMode');
            const savedJudul = localStorage.getItem('draftJudul');
            const savedDeskripsi = localStorage.getItem('draftDeskripsi');

            if (savedJudul) {
                document.querySelector('input[name="judul"]').value = savedJudul;
            }

            if (savedDeskripsi) {
                quill.root.innerHTML = savedDeskripsi;
            }

            previewBtn.addEventListener('click', function() {
                const judul = document.querySelector('input[name="judul"]').value;
                const kategori = document.querySelector('select[name="kategori"]').value;

                // Gunakan innerHTML editor (atau Quill jika pakai)
                const deskripsiHTML = document.getElementById('editor-container').innerHTML;

                document.getElementById('previewKategori').innerText = kategori;
                document.getElementById('previewJudul').innerText = judul;
                document.getElementById('previewDeskripsi').innerHTML = deskripsiHTML;

                const coverImg = document.getElementById('cover-preview-img');
                if (coverImg && coverImg.src && !coverImg.src.includes('blank')) {
                    document.getElementById('previewCover').src = coverImg.src;
                    document.getElementById('previewCoverContainer').style.display = 'block';
                } else {
                    document.getElementById('previewCoverContainer').style.display = 'none';
                }

                // Tampilkan preview full, sembunyikan form
                form.classList.add('hidden');
                previewMode.classList.remove('hidden');
            });

            backToEditorBtn.addEventListener('click', function() {
                form.classList.remove('hidden');
                previewMode.classList.add('hidden');
            });
        });

        document.getElementById("productForm").addEventListener("submit", function() {
            document.getElementById("deskripsi").value = quill.root.innerHTML;

            // Hapus draft dari localStorage setelah submit
            localStorage.removeItem('draftJudul');
            localStorage.removeItem('draftDeskripsi');
        });

        // Simpan ke localStorage saat user mengetik judul
        document.querySelector('input[name="judul"]').addEventListener('input', function() {
            localStorage.setItem('draftJudul', this.value);
        });

        // Simpan deskripsi Quill ke localStorage saat berubah
        quill.on('text-change', function() {
            const deskripsiContent = quill.root.innerHTML;
            localStorage.setItem('draftDeskripsi', deskripsiContent);
        });

        // Saat submit form, ambil isi Quill ke input hidden
        document.getElementById("productForm").addEventListener("submit", function() {
            document.getElementById("deskripsi").value = quill.root.innerHTML;
        });

        // Filter paste: buang gambar dan video
        quill.clipboard.addMatcher(Node.ELEMENT_NODE, function(node, delta) {
            const newOps = delta.ops.filter(op => {
                if (op.insert && typeof op.insert === 'object') {
                    return !op.insert.image && !op.insert.video;
                }
                return true;
            });
            delta.ops = newOps;
            return delta;
        });

        const dropArea = document.getElementById("drop-area");
        const fileInput = document.getElementById("fileInput");
        const fileNameDisplay = document.getElementById("file-name");
        const filePreview = document.getElementById("file-preview");
        const previewFrame = document.getElementById("preview-frame");

        dropArea.addEventListener("click", () => fileInput.click());

        // Fungsi untuk menampilkan preview file
        function previewFile(file) {
            fileNameDisplay.textContent = "File: " + file.name;

            if (file.type === "application/pdf") {
                filePreview.classList.remove("hidden");
                const fileURL = URL.createObjectURL(file);
                previewFrame.src = fileURL;
            } else {
                filePreview.classList.add("hidden");
            }
        }

        // Event listener untuk input file (klik)
        fileInput.addEventListener("change", () => {
            if (fileInput.files.length > 0) {
                previewFile(fileInput.files[0]);
            }
        });

        // Event listener untuk drag & drop
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

            const file = e.dataTransfer.files[0];
            const errorEl = document.getElementById("media-error");

            if (!file) return;

            if (file.type !== "application/pdf") {
                errorEl.textContent = "Format tidak didukung. Hanya file PDF yang diizinkan.";
                errorEl.classList.remove("hidden");
                fileInput.value = '';
                filePreview.classList.add("hidden");
                return;
            }

            if (file.size > MAX_FILE_SIZE) {
                errorEl.textContent = "Ukuran file terlalu besar. Maksimal 10 MB.";
                errorEl.classList.remove("hidden");
                fileInput.value = '';
                filePreview.classList.add("hidden");
                return;
            }

            errorEl.classList.add("hidden");

            // Set file secara manual dan tampilkan preview
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));

            previewFile(file);
        });

        const coverDropArea = document.getElementById('cover-drop-area');
        const coverInput = document.getElementById('coverInput');
        const coverPreview = document.getElementById('cover-preview');
        const coverPreviewImg = document.getElementById('cover-preview-img');
        const coverClearBtn = document.getElementById('cover-clear-btn');

        // Klik area untuk buka file dialog
        coverDropArea.addEventListener('click', () => {
            coverInput.click();
        });

        // Saat file dipilih
        coverInput.addEventListener('change', () => {
            const file = coverInput.files[0];
            if (file) {
                // Validasi tipe file jika perlu
                const reader = new FileReader();
                reader.onload = e => {
                    coverPreviewImg.src = e.target.result;
                    coverPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Tombol clear
        coverClearBtn.addEventListener('click', e => {
            e.stopPropagation();
            coverInput.value = '';
            coverPreviewImg.src = '';
            coverPreview.classList.add('hidden');
        });

        // Optional: support drag & drop file
        coverDropArea.addEventListener('dragover', e => {
            e.preventDefault();
            coverDropArea.classList.add('border-blue-500');
        });

        coverDropArea.addEventListener('dragleave', e => {
            e.preventDefault();
            coverDropArea.classList.remove('border-blue-500');
        });

        coverDropArea.addEventListener('drop', e => {
            e.preventDefault();
            coverDropArea.classList.remove('border-blue-500');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const errorEl = document.getElementById("cover-error");

                if (!allowedTypes.includes(file.type)) {
                    errorEl.textContent = "Format tidak didukung. Hanya JPG dan PNG yang diizinkan.";
                    errorEl.classList.remove("hidden");
                    coverInput.value = '';
                    coverPreview.classList.add("hidden");
                    return;
                }

                if (file.size > MAX_FILE_SIZE) {
                    errorEl.textContent = "Ukuran file terlalu besar. Maksimal 10 MB.";
                    errorEl.classList.remove("hidden");
                    coverInput.value = '';
                    coverPreview.classList.add("hidden");
                    return;
                }

                errorEl.classList.add("hidden");
                // Set file secara manual dan tampilkan preview
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                coverInput.files = dataTransfer.files;
                coverInput.dispatchEvent(new Event('change'));

                const reader = new FileReader();
                reader.onload = e => {
                    coverPreviewImg.src = e.target.result;
                    coverPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB

        // ==== VALIDASI COVER ====
        coverInput.addEventListener('change', () => {
            const file = coverInput.files[0];
            const errorEl = document.getElementById("cover-error");

            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                errorEl.textContent = "Format tidak didukung. Hanya JPG dan PNG yang diizinkan.";
                errorEl.classList.remove("hidden");
                coverInput.value = '';
                coverPreview.classList.add("hidden");
                return;
            }

            if (file.size > MAX_FILE_SIZE) {
                errorEl.textContent = "Ukuran file terlalu besar. Maksimal 10 MB.";
                errorEl.classList.remove("hidden");
                coverInput.value = '';
                coverPreview.classList.add("hidden");
                return;
            }

            errorEl.classList.add("hidden");
            const reader = new FileReader();
            reader.onload = e => {
                coverPreviewImg.src = e.target.result;
                coverPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        // ==== VALIDASI MEDIA (PDF) ====
        fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            const errorEl = document.getElementById("media-error");

            if (!file) return;

            if (file.type !== "application/pdf") {
                errorEl.textContent = "Format tidak didukung. Hanya file PDF yang diizinkan.";
                errorEl.classList.remove("hidden");
                fileInput.value = '';
                filePreview.classList.add("hidden");
                return;
            }

            if (file.size > MAX_FILE_SIZE) {
                errorEl.textContent = "Ukuran file terlalu besar. Maksimal 10 MB.";
                errorEl.classList.remove("hidden");
                fileInput.value = '';
                filePreview.classList.add("hidden");
                return;
            }

            errorEl.classList.add("hidden");
            previewFile(file);
        });
    </script>
@endsection
