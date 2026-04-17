@extends('layouts.app')

@section('content')
    <!-- Header Merah Full Width -->
    <div class="bg-[#C12122] text-white text-center py-3 w-full">
        <h1 class="text-2xl font-semibold">Explant Contributor</h1>
    </div>

    <!-- Konten dengan padding -->
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <section class="mb-6 max-w-2xl mx-auto text-justify">
            <h2 class="italic text-gray-800 mb-2">Penjelasan Singkat</h2>
            <p class="border-b border-gray-400 mb-4"></p>
        @php
            $cleanHtml = $explantContributorDeskripsi;

            // Hapus class ql-indent-1 supaya bullet dan numbering muncul normal
            $cleanHtml = str_replace('class="ql-indent-1"', '', $cleanHtml);

            // Hapus h2 kosong
            $cleanHtml = preg_replace('/<h2>\s*<br\s*\/?>\s*<\/h2>/', '', $cleanHtml);
        @endphp

        <div class="space-y-6 text-gray-800">
            <div class="tentang-kami-content">
                {!! $cleanHtml ?? '' !!}
            </div>
        </div>

        <style>
            /* Scoped styling: hanya untuk konten di dalam .tentang-kami-content */
            .tentang-kami-content ol {
                list-style-type: decimal;
                margin-left: 1.5rem;
                padding-left: 0;
            }
        
            .tentang-kami-content ul {
                list-style-type: disc;
                margin-left: 1.5rem;
                padding-left: 0;
            }
        
            .tentang-kami-content ol > li,
            .tentang-kami-content ul > li {
                margin-top: 0.25rem;
                margin-bottom: 0.25rem;
            }
        
            .tentang-kami-content h2 {
                font-weight: 700;
                font-size: 1.5rem;
                margin-top: 1.5rem;
                margin-bottom: 1rem;
                color: #1a202c; /* Tailwind gray-900 */
            }
        
            .tentang-kami-content p {
                margin-bottom: 1rem;
                line-height: 1.6;
            }
        </style>
        </section>
    </div>
@endsection
