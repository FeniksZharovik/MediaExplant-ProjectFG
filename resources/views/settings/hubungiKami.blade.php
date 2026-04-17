@extends('layouts.setting-layout')

@section('title', 'Hubungi Kami')

@section('setting-content')

    <style>
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        * {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
    </style>

    <div class="px-4 py-6 max-w-2xl mx-auto">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Hubungi Kami</h2>
        <p class="text-sm text-gray-500 mb-6 leading-relaxed">
            Jika Anda mengalami kendala atau memiliki pertanyaan terkait Media Explant, silakan kirimkan pesan Anda
            melalui formulir di bawah ini.
        </p>

        <!-- Form -->
        <form id="hubungiKamiForm" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <input type="text" name="nama" maxlength="90"
                class="w-full p-4 bg-gray-100 text-sm text-gray-700 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-600"
                placeholder="Nama Anda" required>

            <input type="email" name="email" maxlength="90"
                class="w-full p-4 bg-gray-100 text-sm text-gray-700 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-600"
                placeholder="Email Anda" required>

            <textarea name="pesan" rows="4"
                class="w-full p-4 bg-gray-100 text-sm text-gray-700 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-600 resize-none"
                placeholder="Tulis pesan Anda di sini..." required></textarea>

            <!-- Upload Gambar -->
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Upload Gambar (Opsional)</label>
                <div id="upload-area"
                    class="relative flex flex-col items-center justify-center w-full h-52 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-400 transition overflow-hidden">
                    <div id="upload-placeholder" class="flex flex-col items-center justify-center pointer-events-none">
                        <img src="https://img.icons8.com/ios/50/image.png" alt="Upload Icon"
                            class="w-10 h-10 mb-2 opacity-60">
                        <p class="text-sm text-gray-500">Klik di sini untuk memilih gambar</p>
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, GIF | Maksimal 2MB</p>
                    </div>
                    <input id="gambar-upload" type="file" name="gambar" accept="image/*"
                        class="absolute inset-0 opacity-0 cursor-pointer">
                    <div id="preview-container" class="absolute inset-0 hidden">
                        <img id="image-preview" src="#" alt="Preview"
                            class="object-contain w-full h-full rounded-lg">
                        <button type="button" id="remove-image"
                            class="absolute top-2 right-2 bg-white text-gray-600 rounded-full p-1 shadow hover:bg-gray-200">
                            &times;
                        </button>
                    </div>
                </div>
            </div>

            <button id="submitBtn" type="submit"
                class="w-full bg-red-600 text-white text-sm font-semibold py-2 rounded-lg hover:bg-red-700 transition">
                Kirim Pesan
            </button>
        </form>

        <!-- Modal -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl p-6 w-80 text-center shadow-xl animate-fadeIn relative">
                <div id="modal-icon" class="mb-3 flex justify-center"></div>
                <p id="modal-message" class="text-sm text-gray-700"></p>
                <p id="modal-submessage" class="text-xs text-gray-500 mt-2 leading-relaxed">
                    Terima kasih telah menghubungi kami. Kami akan meninjau pesan Anda dan merespons sesegera mungkin. Mohon
                    periksa email Anda secara berkala.
                </p>
                <button onclick="closeModal()" class="mt-4 bg-red-600 text-white py-1 px-4 rounded hover:bg-red-700">
                    Tutup
                </button>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-6 text-center leading-relaxed">
            Kami akan merespons Anda melalui email atau Anda dapat menghubungi kami langsung melalui
            email resmi kami di <span class="text-gray-500">ukpmexplant@journalist.com</span>.
        </p>
    </div>

    <script>
        const form = document.getElementById('hubungiKamiForm');
        const modal = document.getElementById('modal');
        const modalMessage = document.getElementById('modal-message');
        const modalIcon = document.getElementById('modal-icon');
        const modalSubMessage = document.getElementById('modal-submessage');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerText = 'Mengirim...';

            const formData = new FormData(this);

            try {
                const response = await fetch("{{ route('settings.hubungiKami.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    },
                    body: formData
                });

                const result = await response.json();

                modalMessage.innerText = result.message ?? 'Terjadi kesalahan.';
                modalIcon.innerHTML = result.status === 'success' ?
                    '<img src="https://img.icons8.com/ios-filled/50/12B886/checked--v1.png" class="w-10 h-10">' :
                    '<img src="https://img.icons8.com/ios-filled/50/F24C4C/delete-sign--v1.png" class="w-10 h-10">';

                modalSubMessage.innerText = result.status === 'success' ?
                    'Terima kasih telah menghubungi kami. Kami akan meninjau pesan Anda dan merespons sesegera mungkin. Mohon periksa email Anda secara berkala.' :
                    'Mohon pastikan semua kolom telah diisi dengan benar dan coba lagi. Jika masalah berlanjut, silakan hubungi kami lewat email.';

                modal.classList.remove('hidden');

                if (result.status === 'success') {
                    form.reset();
                    document.getElementById('preview-container').classList.add('hidden');
                    document.getElementById('upload-placeholder').classList.remove('hidden');
                }

            } catch (error) {
                modalMessage.innerText = 'Terjadi kesalahan. Silakan coba lagi.';
                modalIcon.innerHTML =
                    '<img src="https://img.icons8.com/ios-filled/50/F24C4C/delete-sign--v1.png" class="w-10 h-10">';
                modalSubMessage.innerText =
                    'Terjadi masalah saat mengirimkan pesan Anda. Silakan coba beberapa saat lagi atau hubungi kami melalui email.';
                modal.classList.remove('hidden');
            }

            submitBtn.disabled = false;
            submitBtn.innerText = 'Kirim Pesan';
        });

        function closeModal() {
            modal.classList.add('hidden');
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Preview logic
        const input = document.getElementById('gambar-upload');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('image-preview');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const removeButton = document.getElementById('remove-image');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        removeButton.addEventListener('click', function() {
            input.value = '';
            previewImage.src = '#';
            previewContainer.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
@endsection
