@extends('layouts.admin-layouts')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-4">
        <!-- Sidebar -->
        <div class="col-span-3">
            @include('dashboard-admin.components.sidebar')
        </div>
        <!-- Main Content -->
        <div class="col-span-9 bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-700">Manajemen Kegiatan</h1>

            <!-- Tambah Kegiatan -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold">Daftar Kegiatan</h2>
                <button class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">Tambah Kegiatan</button>
            </div>

            <!-- Daftar Kegiatan -->
            <div class="bg-gray-50 rounded-lg shadow p-4">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="py-2 px-4 border">#</th>
                            <th class="py-2 px-4 border">Nama Kegiatan</th>
                            <th class="py-2 px-4 border">Tanggal</th>
                            <th class="py-2 px-4 border">Lokasi</th>
                            <th class="py-2 px-4 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border">1</td>
                            <td class="py-2 px-4 border">Seminar Nasional</td>
                            <td class="py-2 px-4 border">15 Jan 2025</td>
                            <td class="py-2 px-4 border">Aula Kampus</td>
                            <td class="py-2 px-4 border">
                                <button class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border">2</td>
                            <td class="py-2 px-4 border">Workshop Desain</td>
                            <td class="py-2 px-4 border">20 Jan 2025</td>
                            <td class="py-2 px-4 border">Lab Komputer</td>
                            <td class="py-2 px-4 border">
                                <button class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Statistik Informasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <h3 class="font-bold text-lg text-blue-600">Total Kegiatan</h3>
                    <p class="text-2xl font-bold mt-2">10</p>
                </div>
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <h3 class="font-bold text-lg text-green-600">Kegiatan Mendatang</h3>
                    <p class="text-2xl font-bold mt-2">5</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
