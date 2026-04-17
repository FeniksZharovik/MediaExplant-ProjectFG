@extends('layouts.app')

@section('content')
    <div class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Form Penulisan berita dan Pengaturan Publikasi -->
        <form id="createArticleForm" method="POST" action="{{ route('author.berita.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="lg:flex lg:space-x-8">
                <!-- Form Penulisan berita -->
                <div class="lg:w-2/3 bg-white shadow-lg rounded-lg p-6 lg:p-8 mb-8 md:mb-10">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">📝 Form Penulisan Berita</h2>

                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-bold text-gray-700">Judul</label>
                        <input type="text" id="judul" name="judul" maxlength="200" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                            placeholder="Masukkan judul berita...">
                        <p id="judulWarning" class="text-red-500 text-sm mt-1 hidden">Judul terlalu panjang! Maksimal 200
                            karakter.</p>
                    </div>

                    <!-- Konten berita -->
                    <div class="mb-6">
                        <label for="konten_berita" class="block text-sm font-bold text-gray-700">Konten berita</label>
                        <div id="quillEditor" class="border rounded-md" style="height: 300px;"></div>
                        <textarea id="konten_berita" name="konten_berita" hidden></textarea>
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

                <!-- Pengaturan Publikasi -->
                <div class="lg:w-1/3 bg-gray-50 shadow-md rounded-lg p-6 lg:p-8 mb-8 md:mb-10">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">⚙️ Pengaturan Publikasi</h2>

                    <!-- Kategori -->
                    <div class="mb-6">
                        <label for="kategori" class="block text-sm font-bold text-gray-700">Kategori</label>
                        <p class="text-sm text-gray-500 mb-2">Menambah kategori untuk mempermudah pencarian berita.</p>
                        <select id="kategori" name="kategori" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="Kampus">Kampus</option>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                            <option value="Liputan Khusus">Liputan Khusus</option>
                            <option value="Opini">Opini</option>
                            <option value="Esai">Esai</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Kesenian">Kesenian</option>
                            <option value="Kesehatan">Kesehatan</option>
                            <option value="Olahraga">Olahraga</option>
                            <option value="Hiburan">Hiburan</option>
                        </select>
                    </div>

                    <!-- Tag -->
                    <div class="mb-6">
                        <label for="tags" class="block text-sm font-bold text-gray-700">Tambahkan Tag</label>
                        <p class="text-sm text-gray-500 mb-2">Tambahkan tag untuk membantu pembaca menemukan berita.</p>
                        <div id="tagContainer" class="flex flex-wrap border rounded-md p-2 gap-2 bg-white shadow-inner">
                            <input type="text" id="tagInput"
                                class="flex-grow focus:outline-none px-2 py-1 rounded-md focus:ring-red-500 focus:border-red-500"
                                placeholder="Ketik dan tekan ',' untuk menambahkan tag">
                        </div>
                        <input type="hidden" id="tagsHidden" name="tags">
                    </div>

                    <!-- Visibilitas -->
                    <div class="mb-6">
                        <span class="block text-sm font-bold text-gray-700">Visibilitas</span>
                        <p class="text-sm text-gray-500 mb-2">Atur visibilitas berita agar dapat dilihat oleh kelompok yang
                            diinginkan.</p>
                        <div class="mt-3 flex items-center space-x-4">
                            <label class="flex items-center text-gray-700">
                                <input type="radio" id="public" name="visibilitas" value="public" required checked
                                    class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                <span class="ml-2">Public</span>
                            </label>
                            <label class="flex items-center text-gray-700">
                                <input type="radio" id="private" name="visibilitas" value="private" required
                                    class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                <span class="ml-2">Private</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tanggal Diterbitkan -->
                    <input type="hidden" name="tanggal_diterbitkan" value="{{ now() }}">

                    <!-- Buttons -->
                    <div class="mt-8 flex justify-between">
                        <!-- Pratinjau -->
                        <button type="button" id="previewArticle"
                            class="flex items-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none">
                            <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z">
                                </path>
                            </svg>
                            Pratinjau
                        </button>

                        <!-- Publikasikan -->
                        <button type="button" id="submitArticle"
                            class="flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span id="submitArticleText">Publikasikan</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Mode -->
    <div id="previewMode" class="hidden">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden mb-10">
            <div id="previewThumbnail" class="hidden"></div>
            <div class="p-6">
                <h2 id="previewKategori" class="text-lg font-semibold text-white px-8 py-1 bg-[#9A0605] inline-block mb-4"
                    style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0% 100%)">
                    <!-- Kategori akan di-set lewat JS -->
                </h2>
                <h1 id="previewJudul" class="text-3xl font-bold text-gray-800 mb-4"></h1>
                <div id="previewKonten" class="prose max-w-none mb-6"></div>
                <div id="previewTags" class="flex flex-wrap gap-2 mt-4"></div>

                <button id="backToEditor" class="mt-8 px-6 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    ← Kembali ke Editor
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalAlert" class="fixed inset-0 hidden bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 relative" id="modalContent">
            <div id="modalMessage" class="text-lg font-medium text-gray-800 mb-4"></div>
            <div class="flex justify-end">
                <button id="closeModal"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <style>
        .ql-editor img {
            max-width: 80%;
            max-height: 300px;
            height: auto;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .ql-editor iframe {
            max-width: 80%;
            height: 300px;
            display: block;
            margin: 0 auto;
            background-color: #f0f0f0;
        }
    </style>

    <script>
        // Inisialisasi Quill Editor
        const quill = new Quill('#quillEditor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'font': []
                    }, {
                        'size': []
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'header': '1'
                    }, {
                        'header': '2'
                    }, 'blockquote', 'code-block'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }, {
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }, {
                        'align': []
                    }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Tulis konten berita di sini...',
        });

        document.getElementById('submitArticle').addEventListener('click', function() {
            const quillHtml = quill.root.innerHTML;
            document.getElementById('konten_berita').value = quillHtml;
            document.getElementById('createArticleForm').submit();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const submitBtn = document.getElementById("submitArticle");
            const form = document.getElementById("createArticleForm");
            const judulInput = document.getElementById("judul");
            const submitText = document.getElementById("submitArticleText");

            submitBtn.addEventListener("click", () => {
                const judul = judulInput.value.trim();
                const konten = quill.root.innerHTML.trim();
                document.getElementById('konten_berita').value = konten;

                let errorMessage = '';

                // === VALIDASI ===
                if ((konten !== '' && konten !== '<p><br></p>') && judul === '') {
                    errorMessage += 'Silakan isi judul terlebih dahulu.\n';
                    judulInput.classList.add('border-red-500');
                } else {
                    judulInput.classList.remove('border-red-500');
                }

                if (judul.length > 200) {
                    errorMessage += 'Judul terlalu panjang. Kurangi hingga 200 karakter.\n';
                }

                if (konten === '' || konten === '<p><br></p>') {
                    errorMessage += 'Konten berita tidak boleh kosong.\n';
                }

                if (errorMessage !== '') {
                    showModal(errorMessage);
                    return;
                }

                // === JIKA VALID ===
                // Tampilkan animasi dan nonaktifkan tombol
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-50", "cursor-not-allowed");
                submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
            </svg>
            <span>Mengunggah...</span>
        `;

                // Bersihkan sessionStorage dan submit form
                sessionStorage.clear();
                form.submit();
            });
        });

        // Elemen Modal
        const modal = document.getElementById('modalAlert');
        const modalMessage = document.getElementById('modalMessage');
        const closeModal = document.getElementById('closeModal');
        const modalContent = document.getElementById('modalContent');

        // Fungsi Modal
        function showModal(message) {
            modalMessage.textContent = message;
            modal.classList.remove('hidden');
        }

        function hideModal() {
            modal.classList.add('hidden');
        }

        // Event Listener Modal
        closeModal.addEventListener('click', hideModal);
        document.addEventListener('keydown', (event) => {
            if (event.key === "Escape") hideModal();
        });
        modal.addEventListener('click', (event) => {
            if (!modalContent.contains(event.target)) hideModal();
        });

        // Validasi Judul
        const judulInput = document.getElementById('judul');
        const judulWarning = document.getElementById('judulWarning');

        judulInput.addEventListener('input', () => {
            if (judulInput.value.length > 200) {
                judulInput.value = judulInput.value.slice(0, 200);
                judulWarning.classList.remove('hidden');
                judulWarning.textContent = 'Judul maksimal 200 karakter!';
            } else {
                judulWarning.classList.add('hidden');
            }
        });

        // Penyimpanan Sementara (Judul & Konten)
        function saveToSession() {
            sessionStorage.setItem('judul', judulInput.value);
            sessionStorage.setItem('konten_berita', quill.root.innerHTML);
            sessionStorage.setItem('tags', JSON.stringify(tags));
        }

        function loadFromSession() {
            if (sessionStorage.getItem('judul')) {
                judulInput.value = sessionStorage.getItem('judul');
            }
            if (sessionStorage.getItem('konten_berita')) {
                quill.root.innerHTML = sessionStorage.getItem('konten_berita');
            }
            if (sessionStorage.getItem('tags')) {
                tags = JSON.parse(sessionStorage.getItem('tags'));
                tags.forEach(tag => addTagElement(tag));
            }
        }

        document.addEventListener("DOMContentLoaded", loadFromSession);
        judulInput.addEventListener("input", saveToSession);
        quill.on("text-change", saveToSession);

        // Fitur Tag (Opsional)
        const tagInput = document.getElementById('tagInput');
        const tagContainer = document.getElementById('tagContainer');
        const tagsHidden = document.getElementById('tagsHidden');
        let tags = [];

        tagInput.addEventListener('keydown', (e) => {
            if (e.key === ',') {
                e.preventDefault();
                const tagValue = tagInput.value.trim();
                if (tagValue) {
                    if (tags.length >= 10) {
                        showModal('Tidak dapat menambahkan lebih dari 10 tag.');
                        return;
                    }
                    if (tags.includes(tagValue)) {
                        showModal('Tag sudah ada.');
                        return;
                    }
                    if (tagValue.length > 15) {
                        showModal('Tag tidak boleh lebih dari 15 karakter.');
                        return;
                    }
                    tags.push(tagValue);
                    addTagElement(tagValue);
                    updateHiddenTags();
                    saveToSession();
                }
                tagInput.value = '';
            }
        });

        function addTagElement(tagValue) {
            const tagEl = document.createElement('div');
            tagEl.className = 'flex items-center bg-gray-200 px-3 py-1 rounded-full text-sm';
            tagEl.innerHTML = `
        <span>${tagValue}</span>
        <button type="button" class="ml-2 text-gray-600 focus:outline-none">&times;</button>
    `;
            tagEl.querySelector('button').addEventListener('click', () => {
                removeTag(tagValue);
            });
            tagContainer.insertBefore(tagEl, tagInput);

            if (tags.length >= 10) {
                tagInput.disabled = true;
            }
        }

        function removeTag(tagValue) {
            tags = tags.filter(tag => tag !== tagValue);
            updateHiddenTags();
            saveToSession();

            Array.from(tagContainer.children).forEach((child) => {
                if (child.querySelector('span')?.innerText === tagValue) {
                    child.remove();
                }
            });

            tagInput.disabled = tags.length >= 10;
        }

        function updateHiddenTags() {
            tagsHidden.value = tags.join(',');
            saveToSession();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('previewArticle');
            const backBtn = document.getElementById('backToEditor');

            previewBtn.addEventListener('click', function() {
                const judul = document.getElementById('judul').value;
                const kategori = document.getElementById('kategori').value;

                // Ambil konten Quill
                const quillContent = quill.root.innerHTML;

                // Sinkronisasi isi Quill ke textarea hidden (untuk submit nanti)
                document.getElementById('konten_berita').value = quillContent;

                // Tampilkan Judul dan Kategori
                document.getElementById('previewJudul').textContent = judul;
                document.getElementById('previewKategori').textContent = kategori;
                document.getElementById('previewKonten').innerHTML = quillContent;

                // Ambil tag dari tagContainer (bukan dari tagsHidden)
                const tagElements = document.querySelectorAll('#tagContainer span');
                const tagsContainer = document.getElementById('previewTags');
                tagsContainer.innerHTML = '';

                const tags = [];
                tagElements.forEach(tagEl => {
                    const tagText = tagEl.textContent.trim();
                    if (tagText) {
                        tags.push(tagText);
                        const span = document.createElement('span');
                        span.textContent = tagText;
                        span.className = 'bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm';
                        tagsContainer.appendChild(span);
                    }
                });

                // Opsional: Update tagsHidden jika ingin sinkronisasi
                document.getElementById('tagsHidden').value = tags.join(',');

                // Sembunyikan thumbnail
                const thumbnailDiv = document.getElementById('previewThumbnail');
                if (thumbnailDiv) {
                    thumbnailDiv.style.display = 'none';
                }

                // Tampilkan preview
                document.getElementById('previewMode').classList.remove('hidden');
                document.getElementById('createArticleForm').classList.add('hidden');
            });

            backBtn.addEventListener('click', function() {
                document.getElementById('previewMode').classList.add('hidden');
                document.getElementById('createArticleForm').classList.remove('hidden');
            });
        });
    </script>
@endsection
