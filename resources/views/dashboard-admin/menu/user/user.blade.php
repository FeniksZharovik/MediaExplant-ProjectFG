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
            <span class="text-gray-700 font-medium">Berita</span>
        </nav>

        <!-- Title -->
        <h1 class="mt-3 text-2xl font-bold text-gray-800">Daftar Akun</h1>
    </div>

    <div class="p-6 bg-white shadow rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <!-- Show Entries -->
            <div class="relative flex items-center gap-2">
                <form method="GET" id="perPageForm" class="flex items-center gap-2">
                    <label for="perPage">Show</label>
                    <select name="perPage" id="perPage" onchange="document.getElementById('perPageForm').submit()"
                        class="py-1 pl-2 pr-7 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <div>entries</div>

                    <!-- Hidden inputs to preserve state -->
                    @if ($searchTerm)
                    <input type="hidden" name="search" value="{{ $searchTerm }}">
                    @endif
                    @if ($role = request('role'))
                    <input type="hidden" name="role" value="{{ $role }}">
                    @endif
                    @if ($order = request('order'))
                    <input type="hidden" name="order" value="{{ $order }}">
                    @endif
                    {{-- @if ($tanggalDari = request('tanggal_dari'))
                    <input type="hidden" name="tanggal_dari" value="{{ $tanggalDari }}">
                    @endif
                    @if ($tanggalSampai = request('tanggal_sampai'))
                    <input type="hidden" name="tanggal_sampai" value="{{ $tanggalSampai }}">
                    @endif --}}
                </form>
            </div>

            <!-- Search + Filter -->
            <div class="flex items-center ml-auto">
                <form method="GET" class="relative max-w-lg mr-4">
                    <input type="text" name="search" value="{{ $searchTerm }}" placeholder="Search..."
                        class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                    <input type="hidden" name="perPage" value="{{ $perPage }}">
                    <i class="fa fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </form>

                {{-- Filter Dropdown --}}
                <div class="relative group mr-2 inline-block">
                    <form method="GET" class="relative group mr-2 inline-block">
                        <!-- Hidden inputs -->
                        <input type="hidden" name="perPage" value="{{ $perPage }}">
                        @if ($searchTerm)
                        <input type="hidden" name="search" value="{{ $searchTerm }}">
                        @endif

                        <button id="filterDropdownBtn"
                            class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-600">
                            <i class="fa fa-filter"></i>
                            <span>Filter</span>
                            <svg class="w-2.5 h-2.5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>

                        <div id="filterDropdown"
                            class="z-10 hidden absolute right-0 mt-2 w-90 bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-4">
                            <!-- Role Filter -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Role</p>
                                <div class="flex flex-col gap-2 text-sm text-gray-700">
                                    @foreach(['Admin', 'Penulis', 'Pembaca'] as $roleOption)
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="role" value="{{ $roleOption }}"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ request('role') === $roleOption ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $roleOption }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <p class="text-sm font-semibold mb-2">Urutkan berdasarkan</p>
                                <div class="flex flex-col gap-2 text-sm text-gray-700">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="order" value="terbaru"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ request('order') === 'terbaru' ? 'checked' : '' }}>
                                        <span class="ml-2">Terbaru</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="order" value="terlama"
                                            class="text-blue-600 focus:ring-blue-500"
                                            {{ request('order') === 'terlama' ? 'checked' : '' }}>
                                        <span class="ml-2">Terlama</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Date Range -->
                            {{-- <div>
                                <p class="text-sm font-semibold mb-2">Tanggal Dibuat</p>
                                <div class="flex items-center gap-2">
                                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500
                            focus:border-blue-500">
                            <i class="fa-solid fa-arrow-right text-gray-500 text-sm"></i>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                </div> --}}

                <!-- Buttons -->
                <div class="flex pt-2">
                    <div class="ml-auto">
                        <button type="button"
                            onclick="window.location.href='{{ route('admin.user') }}?perPage={{ $perPage }}'"
                            class="bg-yellow-400 hover:bg-yellow-700 text-white px-4 py-2 rounded text-sm">Kembalikan</button>
                    </div>
                    <div class="ml-2">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">Terapkan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- User Table -->
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-50 text-xs font-semibold text-gray-600">
            <tr>
                <th class="p-3">UID</th>
                <th class="p-3">Nama Pengguna</th>
                <th class="p-3">Nama Lengkap</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3 text-center w-32">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @php
            $highlight = function($text, $searchTerm) {
            if (!$searchTerm) return e($text);
            return preg_replace('/(' . preg_quote($searchTerm, '/') . ')/i', '<mark
                class="bg-yellow-200 px-1 rounded">$1</mark>', e($text));
            };
            @endphp
            @foreach ($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="p-3 font-medium">{!! $highlight($user->uid, $searchTerm) !!}</td>
                <td class="p-3 flex items-center gap-3">
                    @php
                    $base64Image = $user && $user->profile_pic
                    ? 'data:image/jpeg;base64,' . base64_encode($user->profile_pic)
                    : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                    @endphp

                    <img src="{{ $base64Image }}" alt="Foto Profil"
                        class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-300">
                    <span>{!! $highlight($user->nama_pengguna, $searchTerm) !!}</span>
                </td>
                <td class="p-3">{!! $highlight($user->nama_lengkap, $searchTerm) !!}</td>
                <td class="p-3">{!! $highlight($user->email, $searchTerm) !!}</td>
                <td class="p-3 capitalize">{{ $user->role }}</td>
                <td class="p-3 flex justify-center space-x-2">
                    <div class="relative group">
                        <button onclick="window.location='{{ route('admin.user.detail', ['id' => $user->uid]) }}'"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-blue-500 text-blue-500 hover:bg-blue-50 transition"
                            type="button">
                            <i class="fa fa-eye"></i>
                        </button>
                        <div
                            class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                            Lihat Detail
                        </div>
                    </div>
                    <div class="relative group">
                        <button
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-yellow-500 text-yellow-500 hover:bg-yellow-50 transition"
                            onclick="showChangeRoleModal('{{ $user->uid }}')">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <div
                            class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                            Update
                        </div>
                    </div>
                    <div class="relative group">
                        <form id="delete-form-{{ $user->uid }}"
                            action="{{ route('admin.user.delete', ['uid' => $user->uid]) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <button type="button"
                            class="delete-btn w-9 h-9 flex items-center justify-center rounded-full border border-red-500 text-red-500 hover:bg-red-50 transition"
                            data-id="{{ $user->uid }}">
                            <i class="fa fa-trash"></i>
                        </button>
                        <div
                            class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition">
                            Hapus
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    <div class="flex items-center justify-between mt-4 text-sm text-gray-600">
        <div>
            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
        </div>
        <div class="flex space-x-1">
            @if ($users->onFirstPage())
            <button class="px-3 py-1 border rounded text-gray-600 cursor-not-allowed">Previous</button>
            @else
            <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                onclick="window.location.href='{{ $users->previousPageUrl() }}'">
                Previous
            </button>
            @endif

            @for ($i = 1; $i <= $users->lastPage(); $i++)
                @if ($i == $users->currentPage())
                <button class="px-3 py-1 border rounded bg-blue-500 text-white">{{ $i }}</button>
                @else
                <button class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-100"
                    onclick="window.location.href='{{ $users->url($i) }}'">
                    {{ $i }}
                </button>
                @endif
                @endfor

                @if ($users->hasMorePages())
                <button class="px-3 py-1 border rounded text-gray-600 hover:bg-gray-100"
                    onclick="window.location.href='{{ $users->nextPageUrl() }}'">
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

<script>
    document.getElementById('filterDropdownBtn').addEventListener('click', function (e) {
        e.preventDefault();
        const dropdown = document.getElementById('filterDropdown');
        dropdown.classList.toggle('hidden');
    });

    // Close dropdown on outside click
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('filterDropdown');
        const btn = document.getElementById('filterDropdownBtn');
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = btn.getAttribute('data-id');
                const form = document.getElementById('delete-form-' + id);

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    // Set background color (optional)
                    confirmButtonColor: '#d33', // Still use this if needed
                    cancelButtonColor: '#3085d6',
                    // Apply Tailwind classes to buttons
                    customClass: {
                        confirmButton: 'bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded',
                        cancelButton: 'bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded ml-2'
                    },
                    buttonsStyling: false // Disable default SweetAlert2 styling
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });

    function showChangeRoleModal(uid) {
        Swal.fire({
            title: 'Ubah Role',
            html: `
                <div class="mt-4">
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-700">Pilih Role:</label>
                    <select id="role" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Admin">Admin</option>
                        <option value="Penulis">Penulis</option>
                        <option value="Pembaca">Pembaca</option>
                    </select>

                    <div class="mt-6 flex justify-end gap-2">
                        <button id="cancelBtn" class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded">
                            Batal
                        </button>
                        <button id="confirmBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Terapkan
                        </button>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCancelButton: false,
            didOpen: () => {
                const confirmBtn = document.getElementById('confirmBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                const roleSelect = document.getElementById('role');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                confirmBtn.addEventListener('click', () => {
                    const role = roleSelect.value;

                    if (!role) {
                        Swal.showValidationMessage('Silakan pilih role.');
                        return;
                    }

                    fetch("{{ route('admin.user.change-role', ['uid' => '__UID__']) }}".replace(
                            '__UID__', uid), {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                role
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal mengupdate role');
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire('Berhasil!', 'Role pengguna telah diubah.', 'success')
                                .then(() => window.location.reload());
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error!', 'Gagal mengupdate role pengguna.', 'error');
                        });

                    Swal.close();
                });

                cancelBtn.addEventListener('click', () => {
                    Swal.close();
                });
            }
        });
    }

</script>
@endsection
