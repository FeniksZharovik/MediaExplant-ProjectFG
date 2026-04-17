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
            <span class="text-gray-700 font-medium">Struktur Organisasi </span>
        </nav>
        <!-- Title -->
        <h1 class="mt-3 text-2xl font-bold text-gray-800">Daftar Anggota</h1>
    </div>

    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <!-- Per Page Selector -->
            <form method="GET" id="perPageForm" class="flex items-center gap-2">
                <label for="perPage">Show</label>
                <select name="perPage" id="perPage" onchange="this.form.submit()"
                    class="py-1 pl-2 pr-7 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
                <div>entries</div>
                <!-- Preserve Search & Filter -->
                @if ($searchTerm)
                <input type="hidden" name="search" value="{{ $searchTerm }}">
                @endif
                @if ($selectedDivisi)
                <input type="hidden" name="divisi" value="{{ $selectedDivisi }}">
                @endif
            </form>

            <!-- Search & Filter -->
            <div class="flex items-center ml-auto">
                <form method="GET" class="relative max-w-lg mr-4">
                    <input type="text" name="search" value="{{ $searchTerm }}" placeholder="Search..."
                        class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                    <!-- Preserve perPage -->
                    <input type="hidden" name="perPage" value="{{ $perPage }}">
                    <i class="fa fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </form>

                <!-- Tambah Anggota Button -->
                <div class="">
                    <button id="DivisiPopUp" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-700 transition"
                        onclick="showDivisionTable()">
                        Divisi
                    </button>
                </div>
                
                <div class="ml-2">
                    <button id="tambahAnggotaBtn"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                        onclick="showTambahAnggotaModal()">
                        Tambah Anggota
                    </button>
                </div>

                <!-- Filter Dropdown -->
                <div class="relative group mr-2 inline-block ml-2">
                    <form method="GET" class="relative group mr-2 inline-block">
                        <!-- Preserve perPage and search -->
                        <input type="hidden" name="perPage" value="{{ $perPage }}">
                        @if ($searchTerm)
                        <input type="hidden" name="search" value="{{ $searchTerm }}">
                        @endif

                        <button id="filterDropdownBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600">
                            <i class="fa fa-filter"></i>
                            <span>Filter</span>
                            <svg class="w-2.5 h-2.5 ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <!-- Dropdown Content -->
                        <div id="filterDropdown"
                            class="z-10 hidden absolute right-0 mt-2 w-60 bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-4">
                            <!-- Divisi Filter -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Divisi</p>
                                <div class="flex flex-col gap-2 text-sm text-gray-700">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="divisi" value="semua"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ $selectedDivisi === 'semua' || empty($selectedDivisi) ? 'checked' : '' }}>
                                        <span class="ml-2">Semua Divisi</span>
                                    </label>
                                    @foreach($divisis as $id => $namaDivisi)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="divisi" value="{{ $id }}"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ $selectedDivisi == $id ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $namaDivisi }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex pt-2">
                                <div class="ml-auto">
                                    <button type="button"
                                        onclick="window.location.href='{{ route('admin.organisasi.index') }}?perPage={{ $perPage }}'"
                                        class="bg-yellow-400 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm">
                                        Kembalikan
                                    </button>
                                </div>

                                <div class="ml-2">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm flex items-center">
                                        Terapkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            

            <script>
                function showDivisionTable() {
                    const content = `
                        <!-- Judul -->
                        <h2 class="text-xl font-semibold mb-4">Daftar Divisi</h2>

                        <!-- Tombol Tambah Divisi -->
                        <div class="mb-4 flex justify-end">
                            <button 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300"
                                onclick="tambahDivisi()">
                                Tambah Divisi
                            </button>
                        </div>

                        <!-- Tabel dengan Kolom Aksi -->
                        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
                            <!-- Header (thead) -->
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-1">Baris</th>
                                        <th class="p-1">Nama Divisi</th>
                                        <th class="p-1">Jumlah Anggota</th>
                                        <th class="p-1">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($divisiTable as $index => $divisi)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4 border-b text-center">{{ $divisi->row }}</td>
                                            <td class="py-3 px-4 border-b text-left">{{ $divisi->nama_divisi }}</td>
                                            <td class="py-3 px-4 border-b text-center">{{ $divisi->total_anggota ?? 0 }}</td>
                                            <td class="py-3 px-4 border-b text-center space-x-2">

                                                 <!-- Delete form -->
                                                <form id="delete-form2-{{ $divisi->id }}" 
                                                    action="{{ route('admin.divisi.delete', $divisi->id) }}" 
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                <button type="button"
                                                    class="delete-btn2 w-9 h-9 flex items-center justify-center rounded-full border border-red-500 text-red-500 hover:bg-red-50 transition"
                                                    data-id="{{ $divisi->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="bg-blue-50 text-blue-700 text-sm text-center py-4">
                                                    Belum ada data yang tersedia.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>                
                    `;
                    Swal.fire({
                        title: "",
                        html: content,
                        width: '50%',
                        // Disable the default close button
                        showCloseButton: false,
                        focusConfirm: false,
                        confirmButtonText: "Tutup",
                        customClass: {
                            popup: 'rounded-xl shadow-xl',
                            confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg'
                        }
                    });
                }

                document.addEventListener('click', function (e) {
                    const btn = e.target.closest('.delete-btn2');
                    if (!btn) return;

                    e.preventDefault();

                    const id = btn.getAttribute('data-id');
                    const formId = 'delete-form2-' + id;
                    
                    // Cari form di seluruh DOM
                    let form = document.getElementById(formId);

                    if (!form) {
                        console.error('Form tidak ditemukan!');
                        return;
                    }

                    // Tambahkan form ke body jika belum (opsional)
                    if (!document.body.contains(form)) {
                        document.body.appendChild(form);
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

                // Fungsi dummy untuk Tambah Divisi
                function tambahDivisi() {
                    // Get the URL for creating a new divisi (outside the template string)
                    const urlCreate = @json(route('admin.divisi.store'));

                    Swal.fire({
                            title: 'Tambah Divisi Baru',
                            html: `
                            <form id="tambahDivisiForm" class="max-w-md mx-auto space-y-4">
                                @csrf
                                <!-- Nama Divisi -->
                                <div class="grid grid-cols-3 items-center gap-4">
                                    <label for="nama_divisi" class="text-sm font-medium text-gray-900">Nama Divisi</label>
                                    <div class="col-span-2">
                                        <input type="text" id="nama_divisi" name="nama_divisi" required
                                            placeholder="Masukkan Nama Divisi"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                    </div>
                                </div>

                                <!-- Baris -->
                                <div class="grid grid-cols-3 items-center gap-4">
                                    <label for="row" class="text-sm font-medium text-gray-900">Baris</label>
                                    <div class="col-span-2">
                                        <input type="number" id="row" name="row" required min="1"
                                            placeholder="Masukkan Jumlah Baris"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                    </div>
                                </div>

                                <!-- Kolom -->                              
                            </form>
                        `,
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonText: 'Tambahkan!',
                            cancelButtonText: 'Batal',
                            allowOutsideClick: true,
                            allowEscapeKey: false,
                            focusConfirm: false,
                            reverseButtons: true,
                            // Apply Tailwind classes to buttons
                            customClass: {
                                confirmButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded',
                                cancelButton: 'bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded ml-2'
                            },
                            didOpen: () => {
                                // Custom "X" close button
                                const btn = document.createElement('button');
                                btn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                                btn.className = 'absolute top-2 right-2 text-gray-400 hover:text-gray-700';
                                btn.onclick = () => Swal.close();
                                Swal.getContainer().appendChild(btn);
                            },
                            preConfirm: () => {
                                const form = document.getElementById('tambahDivisiForm');
                                const data = Object.fromEntries(new FormData(form).entries());
                                const csrf = document.querySelector('meta[name="csrf-token"]').content;

                                return fetch(urlCreate, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrf,
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify(data),
                                    })
                                    .then(async response => {
                                        if (!response.ok) {
                                            let errMsg = 'Unknown error';
                                            try {
                                                const json = await response.json();
                                                errMsg = (json.errors && Object.values(json.errors)
                                                        .flat().join(' ')) ||
                                                    json.message ||
                                                    JSON.stringify(json);
                                            } catch {
                                                errMsg = await response.text();
                                            }
                                            Swal.showValidationMessage(errMsg);
                                            return false;
                                        }
                                        return response.json();
                                    })
                                    .catch(err => {
                                        Swal.showValidationMessage(err.message);
                                        return false;
                                    });
                            }
                        })
                        .then(result => {
                            if (result.isConfirmed && result.value && result.value.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: result.value.success,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => window.location.reload());
                            }
                        });
                }
            </script>

           
        </div>

        <!-- Member Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200 table-auto">
                <thead class="bg-gray-100 text-xs font-semibold text-gray-600">
                    <tr>
                        <th class="p-1">No.</th>
                        <th class="p-3">Nama Anggota</th>
                        <th class="p-3">Jabatan</th>
                        <th class="p-3">Divisi</th>
                        <th class="p-3 text-center w-32">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($anggotas as $anggota)

                    <!-- 💡 Define the highlight function once per row (or once before the loop for efficiency) -->
                    @php
                    $highlight = function($text) use ($searchTerm) {
                    if (!$searchTerm || empty($text)) return e($text);
                    return preg_replace(
                    '/(' . preg_quote($searchTerm, '/') . ')/i',
                    '<mark class="bg-yellow-200 px-1 rounded">$1</mark>',
                    e($text)
                    );
                    };
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium">
                            {{ $loop->iteration }}
                        </td>
                        <td class="p-3 h-16 ">
                            <div class="flex items-center gap-3 h-full">
                                @php
                                $base64Image = $anggota && $anggota->user?->profile_pic
                                ? 'data:image/jpeg;base64,' . base64_encode($anggota->user?->profile_pic )
                                :
                                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                                @endphp
                                <img src="{{ $base64Image }}" alt="Foto Profil"
                                    class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-300">
                                <span class="text-sm">{!! $highlight($anggota->user?->nama_pengguna,
                                    $searchTerm)!!}</span>
                            </div>
                        </td>
                        <td class="p-3">
                            {!! $highlight($anggota->title_perangkat ?? 'Tidak Ada Jabatan') !!}
                        </td>
                        <td class="p-3">
                            {{ $highlight($anggota->divisi?->nama_divisi ?? 'Tidak Ada Divisi') }}
                        </td>
                        <td class="p-3 flex justify-center space-x-2">
                            <!-- Edit button -->
                            {{-- <div class="relative group">
                                <button
                                    class="view-btn w-9 h-9 flex items-center justify-center rounded-full border border-blue-500 text-blue-500 hover:bg-blue-50 transition"
                                    onclick="window.location.href='{{ route('admin.organisasi.edit', $anggota->id) }}'">
                            <i class="fa fa-edit"></i>
                            </button>
                            <div
                                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition">
                                Edit
                            </div> --}}
        </div>

        <!-- Delete form -->
        <form id="delete-form-{{ $anggota->id }}" action="{{ route('admin.organisasi.delete', $anggota->id) }}"
            method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <!-- Delete button -->
        <div class="relative group">
            <button type="button"
                class="delete-btn w-9 h-9 flex items-center justify-center rounded-full border border-red-500 text-red-500 hover:bg-red-50 transition"
                data-id="{{ $anggota->id }}">
                <i class="fa fa-trash"></i>
            </button>
            <div
                class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition">
                Hapus
            </div>
        </div>
        </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">
                <div class="bg-blue-50 text-blue-700 text-sm text-center py-4">
                    Belum ada data yang tersedia.
                </div>
            </td>
        </tr>
        @endforelse
        </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <div class="flex items-center justify-between mt-4 text-sm text-gray-600">
            <div>
                Showing
                {{ $anggotas->firstItem() }}
                to
                {{ $anggotas->lastItem() }}
                of
                {{ $anggotas->total() }}
                entries
            </div>
            <div class="flex space-x-1">
                <!-- Previous -->
                @if ($anggotas->onFirstPage())
                <button class="px-3 py-1 border rounded text-gray-600 cursor-not-allowed">Previous</button>
                @else
                <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                    onclick="window.location.href='{{ $anggotas->previousPageUrl() }}'">
                    Previous
                </button>
                @endif

                <!-- Page Numbers -->
                @for ($i = 1; $i <= $anggotas->lastPage(); $i++)
                    @if ($i == $anggotas->currentPage())
                    <button class="px-3 py-1 border rounded bg-blue-500 text-white">{{ $i }}</button>
                    @else
                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-100"
                        onclick="window.location.href='{{ $anggotas->url($i) }}'">
                        {{ $i }}
                    </button>
                    @endif
                    @endfor

                    <!-- Next -->
                    @if ($anggotas->hasMorePages())
                    <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                        onclick="window.location.href='{{ $anggotas->nextPageUrl() }}'">
                        Next
                    </button>
                    @else
                    <button class="px-3 py-1 border rounded text-gray-600 cursor-not-allowed">Next</button>
                    @endif
            </div>
        </div>
    </div>
</div>
</div>

<!-- Script for Filter Dropdown Toggle -->
<script>
    document.getElementById('filterDropdownBtn').addEventListener('click', function (e) {
        e.preventDefault();
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('filterDropdown');
        const btn = document.getElementById('filterDropdownBtn');
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

</script>

<!-- Script for Delete Confirmation -->
<script>
    // Hapus semua event listener sebelumnya
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



    function showTambahAnggotaModal() {
        // grab the real URL once (outside the template string)
        const urlCreate = @json(route('admin.organisasi.create'));

        Swal.fire({
                title: 'Tambah Anggota Baru',
                html: `
      <form id="tambahAnggotaForm" class="max-w-md mx-auto space-y-4">
        @csrf
        <!-- Pengguna -->
        <div class="grid grid-cols-3 items-center gap-4">
          <label for="uid" class="text-sm font-medium text-gray-900">Pengguna</label>
          <div class="col-span-2">
            <select id="uid" name="uid" required
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              <option value="" disabled selected>Pilih Pengguna</option>
              @foreach($users as $users)
                <option value="{{ $users->uid }}">
                  {{ $users->nama_pengguna ?? 'N/A' }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Divisi -->
        <div class="grid grid-cols-3 items-center gap-4">
          <label for="id_divisi" class="text-sm font-medium text-gray-900">Divisi</label>
          <div class="col-span-2">
            <select id="id_divisi" name="id_divisi" required
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
              <option value="" disabled selected>Pilih Divisi</option>
              @foreach($divisis as $id => $namaDivisi)
                <option value="{{ $id }}">{{ $namaDivisi }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Jabatan -->
        <div class="grid grid-cols-3 items-center gap-4">
          <label for="title_perangkat" class="text-sm font-medium text-gray-900">Jabatan</label>
          <div class="col-span-2">
            <input type="text" id="title_perangkat" name="title_perangkat" required
              placeholder="Masukkan Jabatan"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
          </div>
        </div>
      </form>
    `,
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Tambahkan!',
                cancelButtonText: 'Batal',
                allowOutsideClick: true,
                allowEscapeKey: false,
                focusConfirm: false,
                reverseButtons: true,
                // Set background color (optional)
                confirmButtonColor: '#d33', // Still use this if needed
                cancelButtonColor: '#3085d6',
                // Apply Tailwind classes to buttons
                customClass: {
                    confirmButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded ',
                    cancelButton: 'bg-red-500 text-white hover:bg-red-600 px-4 py-2 roundedml-2'
                },
                didOpen: () => {
                    // custom “X” close button
                    const btn = document.createElement('button');
                    btn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                    btn.className = 'absolute top-2 right-2 text-gray-400 hover:text-gray-700';
                    btn.onclick = () => Swal.close();
                    Swal.getContainer().appendChild(btn);
                },
                preConfirm: () => {
                    const form = document.getElementById('tambahAnggotaForm');
                    const data = Object.fromEntries(new FormData(form).entries());
                    const csrf = document.querySelector('meta[name="csrf-token"]').content;

                    return fetch(urlCreate, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(async response => {
                            // if Laravel returns 422/500, grab the JSON and show it
                            if (!response.ok) {
                                let errMsg = 'Unknown error';
                                try {
                                    const json = await response.json();
                                    // Laravel default errors live under json.errors or json.message
                                    errMsg = (json.errors && Object.values(json.errors).flat().join(
                                            ' ')) ||
                                        json.message ||
                                        JSON.stringify(json);
                                } catch {
                                    errMsg = await response.text();
                                }
                                // showValidationMessage will stop the modal from closing
                                Swal.showValidationMessage(errMsg);
                                // return false so preConfirm resolves, not rejects
                                return false;
                            }
                            return response.json();
                            window.history.back();
                        })
                        .catch(err => {
                            // network-level failure
                            Swal.showValidationMessage(err.message);
                            return false;
                        });
                }
            })
            .then(result => {
                if (result.isConfirmed && result.value && result.value.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.value.success,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => window.location.reload());
                }
            });
    }

</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek jika ada pesan sukses dari session
        if (typeof {
                {
                    session('success')
                }
            } !== 'undefined') {
            let timerInterval;

            Swal.fire({
                title: 'Berhasil!',
                html: '<b>{{ session('
                success ') }}</b>',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded'
                },
                timerProgressBar: true, // Aktifkan progress bar timer
                didOpen: () => {
                    // Tampilkan loading spinner saat modal dibuka
                    Swal.showLoading();

                    // Timer untuk menampilkan waktu tersisa
                    const timer = Swal.getPopup().querySelector('b');
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()} ms`;
                    }, 100);
                },
                willClose: () => {
                    // Hentikan interval timer ketika modal ditutup
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                // Tangani hasil setelah modal ditutup
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('Modal ditutup oleh timer.');
                }
            });
        }
    });

</script>

@if (session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('
            error ') }}',
            timer: 3000,
            showConfirmButton: true,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#ef4444', // Tailwind red-500
            buttonsStyling: false,
            customClass: {
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded'
            }
        });
    });

</script>
@endif

@endif

@endsection
