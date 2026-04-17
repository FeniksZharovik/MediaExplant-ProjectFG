@extends('layouts.app')

@section('content')
    @php
        $tagList = isset($berita) && $berita->tags ? $berita->tags->pluck('nama_tag')->toArray() : [];
    @endphp

    <div class="max-w-[84rem] mx-auto px-4 sm:px-6 lg:px-8 py-6">

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

                <form action="{{ route('berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Judul -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-bold text-gray-700">Judul</label>
                        <input type="text" name="judul" id="judul" value="{{ $berita->judul }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>

                    <!-- Konten Berita -->
                    <div class="mb-6">
                        <label for="konten_berita" class="block text-sm font-bold text-gray-700 mb-2">Konten Berita</label>
                        <div id="quillEditor" style="height: 300px;">{!! $berita->konten_berita !!}</div>
                        <input type="hidden" name="konten_berita" id="konten_berita">
                    </div>
            </div>

            <!-- Pengaturan Perubahan -->
            <div class="lg:w-1/3 bg-gray-50 shadow-md rounded-lg p-6 lg:p-8 mb-8 md:mb-10">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">⚙️ Pengaturan Perubahan</h2>

                <!-- Kategori -->
                <div class="mb-6">
                    <label for="kategori" class="block text-sm font-bold text-gray-700">Kategori</label>
                    <p class="text-sm text-gray-500 mb-2">Menambah kategori untuk mempermudah pencarian berita.</p>
                    <select id="kategori" name="kategori" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        @foreach (['Kampus', 'Nasional', 'Internasional', 'Liputan Khusus', 'Opini', 'Esai', 'Teknologi', 'Kesenian', 'Kesehatan', 'Olahraga', 'Hiburan'] as $kategori)
                            <option value="{{ $kategori }}" {{ $berita->kategori === $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tag -->
                <div class="mb-6">
                    <label for="tags" class="block text-sm font-bold text-gray-700">Tambahkan Tag</label>
                    <p class="text-sm text-gray-500 mb-2">Tambahkan tag untuk membantu pembaca menemukan berita.</p>
                    <div id="tagContainer" class="flex flex-wrap border rounded-md p-2 gap-2 bg-white shadow-inner">
                        <!-- Dynamic tags will be inserted here by JS -->
                        <input type="text" id="tagInput"
                            class="flex-grow focus:outline-none px-2 py-1 rounded-md focus:ring-red-500 focus:border-red-500"
                            placeholder="Ketik dan tekan ',' atau Enter untuk menambahkan tag">
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
                            <input type="radio" name="visibilitas" value="public" required
                                class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500"
                                {{ $berita->visibilitas === 'public' ? 'checked' : '' }}>
                            <span class="ml-2">Public</span>
                        </label>
                        <label class="flex items-center text-gray-700">
                            <input type="radio" name="visibilitas" value="private" required
                                class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500"
                                {{ $berita->visibilitas === 'private' ? 'checked' : '' }}>
                            <span class="ml-2">Private</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" id="submitButton" disabled
                        class="bg-red-300 cursor-not-allowed text-white font-bold py-2 px-4 rounded flex items-center justify-center gap-2">
                        <span id="submitText">Perbarui</span>
                        <svg id="submitSpinner" class="animate-spin h-5 w-5 text-white hidden" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="modal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center transition-opacity duration-300 opacity-0 z-50">
        <div class="bg-white p-6 rounded-md shadow-md max-w-sm mx-auto text-center" id="modalContent">
            <div class="flex justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-600" viewBox="0 0 20 20"
                    fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-7h-2v2h2v-2zm0-4h-2v3h2V7z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p id="modalMessage" class="text-lg font-semibold text-gray-700"></p>
        </div>
    </div>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <!-- Script tag input jika dinamis -->
    <script>
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
            }
        });

        // Set initial content
        document.getElementById('konten_berita').value = quill.root.innerHTML;

        // Update hidden input on text change
        quill.on('text-change', function() {
            document.getElementById('konten_berita').value = quill.root.innerHTML;
        });

        const tagInput = document.getElementById('tagInput');
        const tagContainer = document.getElementById('tagContainer');
        const tagsHidden = document.getElementById('tagsHidden');

        let tags = @json($tagList);

        // Function to show modal with message
        function showModal(message) {
            const modal = document.getElementById('modal');
            const modalContent = document.getElementById('modalContent');
            modalContent.textContent = message;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 10);
        }

        // Hide modal
        function hideModal() {
            const modal = document.getElementById('modal');
            modal.classList.remove('opacity-100');
            modal.classList.add('hidden');
        }

        // Handle modal close when clicked outside
        window.onclick = function(event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                hideModal();
            }
        }

        function renderTags() {
            tagContainer.querySelectorAll('.tag-item').forEach(el => el.remove());

            tags.forEach((tag, index) => {
                const span = document.createElement('span');
                span.className =
                    'flex items-center bg-gray-200 px-3 py-1 rounded-full text-sm tag-item';

                const tagText = document.createElement('span');
                tagText.textContent = tag;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'ml-2 text-black-500 font-small focus:outline-none';
                removeBtn.innerHTML = '&times;';

                removeBtn.onclick = () => {
                    tags.splice(index, 1);
                    renderTags();
                };

                span.appendChild(tagText);
                span.appendChild(removeBtn);
                tagContainer.insertBefore(span, tagInput);
            });


            tagsHidden.value = tags.join(',');
        }

        tagInput.addEventListener('keydown', function(e) {
            if (e.key === ',' || e.key === 'Enter') {
                e.preventDefault();
                const tagValue = tagInput.value.trim().replace(',', '');
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
                    renderTags();
                }
                tagInput.value = '';
            }
        });

        renderTags();

        const initialState = {
            judul: document.getElementById('judul').value,
            konten: quill.root.innerHTML,
            kategori: document.getElementById('kategori').value,
            tags: [...tags],
            visibilitas: document.querySelector('input[name="visibilitas"]:checked')?.value || ''
        };

        const submitButton = document.getElementById('submitButton');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');

        function hasFormChanged() {
            const currentState = {
                judul: document.getElementById('judul').value,
                konten: quill.root.innerHTML,
                kategori: document.getElementById('kategori').value,
                tags: [...tags],
                visibilitas: document.querySelector('input[name="visibilitas"]:checked')?.value || ''
            };

            return JSON.stringify(currentState) !== JSON.stringify(initialState);
        }

        function updateSubmitButtonState() {
            if (hasFormChanged()) {
                submitButton.disabled = false;
                submitButton.classList.remove('bg-red-300', 'cursor-not-allowed');
                submitButton.classList.add('bg-red-600', 'hover:bg-red-700');
            } else {
                submitButton.disabled = true;
                submitButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                submitButton.classList.add('bg-red-300', 'cursor-not-allowed');
            }
        }

        // Cek perubahan di semua elemen form
        document.getElementById('judul').addEventListener('input', updateSubmitButtonState);
        quill.on('text-change', updateSubmitButtonState);
        document.getElementById('kategori').addEventListener('change', updateSubmitButtonState);
        document.querySelectorAll('input[name="visibilitas"]').forEach(radio => {
            radio.addEventListener('change', updateSubmitButtonState);
        });

        // Update saat tag berubah
        const originalRenderTags = renderTags;
        renderTags = function() {
            originalRenderTags();
            updateSubmitButtonState();
        }

        // Saat form disubmit
        document.querySelector('form').addEventListener('submit', function(e) {
            submitButton.disabled = true;
            submitText.textContent = 'Memperbarui...';
            submitSpinner.classList.remove('hidden');
            submitButton.classList.add('cursor-not-allowed');
        });
    </script>
@endsection
