<div id="komentar-list" class="mt-4 max-h-96 overflow-y-auto pr-2 space-y-4">
    @if ($user->komentar?->isNotEmpty())
        @foreach ($user->komentar as $komentar)
            <div class="flex items-start space-x-4 border-b border-gray-700 pb-4 last:border-b-0">
                <div class="flex flex-col flex-grow">
                    <!-- Header: Comment title + delete button -->
                    <div class="flex items-center justify-between space-x-2">
                        <span class="font-medium">
                            {{ $komentar->parent_id ? '@' . optional(optional($komentar->parent)->user)->nama_pengguna : '' }}
                            {{ Str::limit($komentar->isi_komentar, 100) }}
                        </span>

                        <!-- Delete form -->
                        <form action="{{ route('admin.komentar.delete', [
                            'id' => optional(optional(optional($komentar->parent)->user))->uid ?? $user->uid,
                            'komentarId' => $komentar->id
                        ]) }}" 
                        method="POST" id="delete-form-{{ $komentar->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $komentar->id }}">
                                <span class="text-red">Hapus Komentar</span>
                            </button>
                        </form>
                    </div>

                    <!-- Comment Source -->
                    <a href="{{ 
                        match ($komentar->komentar_type) {
                            'Berita' => '/kategori/' . strtolower($komentar->komentarable?->kategori ?? '') . '/read?a=' . $komentar->komentarable?->id,
                            'Produk' => '/produk/' . strtolower($komentar->komentarable?->kategori ?? '') . '/browse?f=' . $komentar->komentarable?->id,
                            'Karya' => '/karya/' . strtolower($komentar->komentarable?->kategori ?? '') . '/read?a=' . $komentar->komentarable?->id,
                            default => '#'
                        }
                    }}">
                        <div class="my-3 pl-1 text-sm text-gray-700 border-l-2 border-gray-300">
                            {{ $komentar->komentarable?->judul ?? 'Judul tidak tersedia' }}
                        </div>
                    </a>

                    <!-- Date -->
                    <div class="text-sm text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($komentar->tanggal_komentar)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-gray-500">Belum ada komentar untuk pengguna ini.</p>
    @endif
</div>

<!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> 

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        // Use closest() so clicks on <i> inside the button also trigger this
        const btn = e.target.closest('.delete-btn');
        if (!btn) return; // not a delete button click

        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const formId = 'delete-form-' + id;
        const form = document.getElementById(formId);

        if (!form) {
            console.error('Form dengan ID ' + formId + ' tidak ditemukan!');
            return;
        }

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            customClass: {
                confirmButton: 'bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded',
                cancelButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded ml-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>