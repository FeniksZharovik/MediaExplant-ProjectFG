@extends('layouts.app')

@section('content')
    <style>
        .disabled-btn {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    <div class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <form onsubmit="return syncQuill()" action="{{ route('produk.update', $produk->id) }}" method="POST"
                enctype="multipart/form-data" class="contents">
                @csrf
                @method('PUT')

                <!-- KIRI -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                        📖 <span class="ml-2">Perbarui Produk</span>
                    </h2>

                    @if (session('success'))
                        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <!-- Judul -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Judul</label>
                        <input type="text" name="judul" value="{{ old('judul', $produk->judul) }}"
                            class="w-full p-2 border rounded-lg" required>
                    </div>

                    <!-- Cover -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Unggah Cover</label>
                        <div id="cover-drop-area"
                            class="border-dashed border-2 border-gray-300 p-4 text-center cursor-pointer relative rounded-lg hover:border-blue-500 transition">
                            <p class="text-gray-500 mb-2">Klik di sini untuk mengunggah</p>
                            <input type="file" name="cover" id="coverInput" accept=".jpg,.jpeg,.png" class="hidden">
                            <p id="cover-error" class="text-red-500 text-sm mt-1 hidden"></p>

                            <div id="cover-preview" class="relative mx-auto max-w-xs rounded overflow-hidden shadow-md">
                                <img id="cover-preview-img" src="{{ $produk->cover }}" alt="Preview Cover"
                                    class="w-full h-auto object-cover rounded" />
                                <button type="button" id="cover-clear-btn"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700 transition">
                                    &times;
                                </button>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Hanya gambar JPG/PNG, maks 10 MB</p>
                    </div>

                    <!-- Media PDF -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Unggah File</label>
                        <div id="drop-area"
                            class="border-dashed border-2 border-gray-300 p-6 text-center cursor-pointer relative">
                            <p class="text-gray-500">letakkan file di sini atau klik untuk unggah</p>
                            <input type="file" name="media" class="hidden" id="fileInput" accept=".pdf">
                            <p id="media-error" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Hanya file PDF, maks 10 MB</p>
                        <p id="file-name" class="text-gray-500 mt-2"></p>

                        <div id="file-preview" class="mt-4">
                            <p class="text-gray-700 font-semibold">Pratinjau File:</p>
                            <iframe id="preview-frame" class="w-full h-64 border rounded-lg"
                                src="{{ route('produk.media-preview', $produk->id) }}"></iframe>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Deskripsi</label>
                        <div id="quill-editor" style="min-height: 200px;" class="border rounded p-2"></div>
                        <textarea name="deskripsi" id="deskripsiField" class="hidden">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>
                </div>

                <!-- KANAN -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
                        ⚙️ <span class="ml-2">Pengaturan Perubahan</span>
                    </h2>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Kategori</label>
                        <p class="text-gray-500 text-sm mb-1">Pilih kategori produk yang sesuai.</p>
                        <select name="kategori" required class="w-full p-2 border rounded-lg">
                            <option value="Buletin" {{ $produk->kategori === 'Buletin' ? 'selected' : '' }}>Buletin</option>
                            <option value="Majalah" {{ $produk->kategori === 'Majalah' ? 'selected' : '' }}>Majalah</option>
                        </select>
                    </div>

                    <!-- Visibilitas -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-1">Visibilitas</label>
                        <p class="text-gray-500 text-sm mb-1">Atur visibilitas agar dapat dilihat oleh kelompok yang
                            diinginkan.
                        </p>
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="visibilitas" value="public"
                                    {{ $produk->visibilitas === 'public' ? 'checked' : '' }}>
                                <span>Public</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="visibilitas" value="private"
                                    {{ $produk->visibilitas === 'private' ? 'checked' : '' }}>
                                <span>Private</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div>
                        <button type="submit" id="submit-btn"
                            class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition disabled-btn"
                            disabled>
                            Perbarui Produk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const submitBtn = document.getElementById('submit-btn');

            // Ambil nilai awal (state asli)
            const initialState = {
                judul: document.querySelector('input[name="judul"]').value.trim(),
                cover: null, // file, awalnya null
                media: null, // file, awalnya null
                deskripsi: @json($produk->deskripsi),
                kategori: document.querySelector('select[name="kategori"]').value,
                visibilitas: document.querySelector('input[name="visibilitas"]:checked').value
            };

            let isDirty = false;

            function checkFormChanges() {
                const currentState = {
                    judul: document.querySelector('input[name="judul"]').value.trim(),
                    cover: document.getElementById('coverInput').files[0] || null,
                    media: document.getElementById('fileInput').files[0] || null,
                    deskripsi: document.querySelector('#quill-editor .ql-editor').innerHTML.trim(),
                    kategori: document.querySelector('select[name="kategori"]').value,
                    visibilitas: document.querySelector('input[name="visibilitas"]:checked').value
                };

                // Cek apakah ada perbedaan
                isDirty = (
                    currentState.judul !== initialState.judul ||
                    currentState.cover !== initialState.cover ||
                    currentState.media !== initialState.media ||
                    currentState.deskripsi !== initialState.deskripsi ||
                    currentState.kategori !== initialState.kategori ||
                    currentState.visibilitas !== initialState.visibilitas
                );

                // Update tombol
                if (isDirty) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled-btn');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('disabled-btn');
                }
            }

            // Tambahkan event listener
            document.querySelector('input[name="judul"]').addEventListener('input', checkFormChanges);
            document.getElementById('coverInput').addEventListener('change', checkFormChanges);
            document.getElementById('fileInput').addEventListener('change', checkFormChanges);
            document.querySelector('select[name="kategori"]').addEventListener('change', checkFormChanges);
            document.querySelectorAll('input[name="visibilitas"]').forEach(radio => {
                radio.addEventListener('change', checkFormChanges);
            });

            // Quill deskripsi
            const quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Tulis deskripsi...',
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

            const deskripsiFromServer = @json($produk->deskripsi);
            if (deskripsiFromServer) {
                quill.clipboard.dangerouslyPasteHTML(deskripsiFromServer);
            }

            // Cek perubahan Quill
            quill.on('text-change', checkFormChanges);

            // Submit animasi
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const textarea = document.querySelector('#deskripsiField');
                textarea.value = quill.root.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Memperbarui...';
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            });

            // sinkronisasi global
            window.syncQuill = function() {
                const textarea = document.querySelector('#deskripsiField');
                if (textarea) {
                    textarea.value = quill.root.innerHTML;
                }
                return true;
            };
        });


        const coverInput = document.getElementById("coverInput");
        const coverPreviewImg = document.getElementById("cover-preview-img");
        const coverClearBtn = document.getElementById("cover-clear-btn");
        const coverDropArea = document.getElementById("cover-drop-area");
        const originalCover = "{{ $produk->cover }}";

        coverClearBtn.style.display = "none";

        coverDropArea.addEventListener("click", function() {
            coverInput.click();
        });

        coverInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    coverPreviewImg.src = evt.target.result;
                    coverClearBtn.style.display = "flex";
                };
                reader.readAsDataURL(file);
            }
        });

        coverClearBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            coverInput.value = "";
            coverPreviewImg.src = originalCover;
            coverClearBtn.style.display = "none";
            coverInput.click();
        });

        document.getElementById("coverInput").addEventListener("change", function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    document.getElementById("cover-preview-img").src = evt.target.result;
                    document.getElementById("cover-preview").classList.remove("hidden");
                };
                reader.readAsDataURL(file);
            }
        });

        const fileInput = document.getElementById("fileInput");
        const fileNameDisplay = document.getElementById("file-name");
        const filePreview = document.getElementById("file-preview");
        const previewFrame = document.getElementById("preview-frame");
        const dropArea = document.getElementById("drop-area");

        // Simpan preview asli (file lama dari server)
        const originalPDFPreview = "{{ route('produk.media-preview', $produk->id) }}";

        // Klik area = buka file selector
        dropArea.addEventListener("click", function() {
            fileInput.click();
        });

        // Saat pilih file
        fileInput.addEventListener("change", function(e) {
            const file = e.target.files[0];

            if (file) {
                if (file.type !== "application/pdf") {
                    alert("File harus berupa PDF");
                    fileInput.value = "";
                    previewFrame.src = originalPDFPreview;
                    fileNameDisplay.textContent = "";
                    filePreview.classList.remove("hidden");
                    return;
                }

                fileNameDisplay.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewFrame.src = evt.target.result;
                    filePreview.classList.remove("hidden");
                };
                reader.readAsDataURL(file);
            } else {
                previewFrame.src = originalPDFPreview;
                fileNameDisplay.textContent = "";
                filePreview.classList.remove("hidden");
            }
        });
    </script>
@endsection
