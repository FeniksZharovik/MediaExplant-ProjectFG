@extends('layouts.app')

@section('content')
<!-- Header Merah Full Width -->
<div class="bg-[#C12122] text-white text-center py-3 w-full">
    <h1 class="text-2xl font-semibold">Struktur Organisasi</h1>
</div>

<!-- Konten dengan padding atas dan bawah -->
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <!-- Penjelasan Singkat -->
    <section class="mb-8 max-w-3xl mx-auto text-justify">

        <h2 class="italic text-gray-800 mb-2">Penjelasan Singkat</h2>
        <p class="border-b border-gray-700 mb-4"></p>
        {!! $DeskripsiOrganisasi !!}
        <p class="border-b border-gray-700 my-4"></p>
    </section>

    <!-- Struktur Organisasi Visual -->
    <div class="min-h-screen  py-5 px-4">
        <div class="max-w-3xl mx-auto">
            @foreach($divisis as $divisi)
            @if($divisi->anggotas->isNotEmpty())
            <!-- {{ $divisi->nama_divisi }} -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ $divisi->nama_divisi }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 place-items-center mb-20">
                @foreach($divisi->anggotas as $anggota)
                <div class="text-center">
                    @php
                    $base64Image = $anggota->user && $anggota->user->profile_pic
                    ? 'data:image/jpeg;base64,' . base64_encode($anggota->user->profile_pic)
                    : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSb2F1sRrmj0rFgZyVmC8yBgXxyccFRJf7LPQ&s';
                    @endphp
                    <img class="w-24 h-24 rounded-full mx-auto"
                        src="{{$base64Image}}"
                        alt="Avatar">

                    @if($anggota->title_perangkat)
                    <h3 class="mt-2 text-md font-semibold text-gray-700">{{ $anggota->title_perangkat }}</h3>
                    @endif

                    <p class="text-gray-600">{{ $anggota->user->nama_pengguna ?? 'Tanpa Nama' }}</p>
                </div>
                @endforeach
            </div>
            @endif
            @endforeach

            <!-- Pimpinan Umum -->
            {{-- <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Pimpinan Umum</h2>
            <div class="flex flex-col items-center space-y-4 mb-20">
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                    <p class="text-gray-600">Satria Ardiatnha Uno</p>
                </div>
            </div>

            <!-- Biro Umum -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Biro Umum</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 justify-items-center mb-20">
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                    <p class="text-gray-600">Satria Ardiatnha Uno</p>
                </div>
                <div class="text-center">
                  <img class="w-16 h-16 rounded-full mx-auto"
                      src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                      alt="Avatar">
                  <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                  <p class="text-gray-600">Satria Ardiatnha Uno</p>
              </div>
              <div class="text-center">
                <img class="w-16 h-16 rounded-full mx-auto"
                    src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                    alt="Avatar">
                <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                <p class="text-gray-600">Satria Ardiatnha Uno</p>
            </div>
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                    <p class="text-gray-600">Satria Ardiatnha Uno</p>
                </div>
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <h3 class="mt-2 text-md font-semibold text-gray-700">Title Pangkat</h3>
                    <p class="text-gray-600">Satria Ardiatnha Uno</p>
                </div>
            </div>

            <!-- Anggota -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Anggota</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 justify-items-center mb-20">
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <p class="mt-2 text-gray-600">Satria Ardiatnha Uno</p>
                </div>
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <p class="mt-2 text-gray-600">Satria Ardiatnha Uno</p>
                </div>
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <p class="mt-2 text-gray-600">Satria Ardiatnha Uno</p>
                </div>
                <div class="text-center">
                    <img class="w-16 h-16 rounded-full mx-auto"
                        src="https://www.techtarget.com/rms/onlineimages/anime_girl-h_half_column_mobile.png "
                        alt="Avatar">
                    <p class="mt-2 text-gray-600">Satria Ardiatnha Uno</p>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
