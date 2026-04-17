@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 md:px-12 xl:px-32 py-10">
        <h1 class="text-3xl font-bold mb-8 text-gray-800 border-b pb-4">📦 Arsip Tahunan</h1>

        @foreach ($years as $year)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6 p-6">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-blue-800">{{ $year }}</span>
                    <button onclick="toggleCategory('{{ $year }}')"
                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded">
                        Pilih Kategori
                    </button>
                </div>

                <div id="category-{{ $year }}" class="hidden mt-4 space-x-4">
                    <button onclick="loadData('{{ $year }}', 'berita')"
                        class="text-red-600 hover:underline font-medium">📰 Berita</button>
                    <button onclick="loadData('{{ $year }}', 'produk')"
                        class="text-green-600 hover:underline font-medium">📦 Produk</button>
                    <button onclick="loadData('{{ $year }}', 'karya')"
                        class="text-purple-600 hover:underline font-medium">🎨 Karya</button>
                </div>

                <div id="result-{{ $year }}" class="mt-4 hidden max-h-[400px] overflow-y-auto pr-2">
                    <h2 id="result-title-{{ $year }}" class="text-lg font-bold text-gray-700 mb-2"></h2>
                    <div id="result-list-{{ $year }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        function toggleCategory(year) {
            document.getElementById('category-' + year).classList.toggle('hidden');
            document.getElementById('result-' + year).classList.add('hidden');
        }

        function bulanIndo(bln) {
            const namaBulan = {
                '01': 'Januari',
                '02': 'Februari',
                '03': 'Maret',
                '04': 'April',
                '05': 'Mei',
                '06': 'Juni',
                '07': 'Juli',
                '08': 'Agustus',
                '09': 'September',
                '10': 'Oktober',
                '11': 'November',
                '12': 'Desember'
            };
            return namaBulan[bln] || bln;
        }

        function getDetailLink(type, item) {
            const kategori = item.kategori ? item.kategori.toLowerCase() : 'umum';
            const id = item.id;

            if (type === 'berita') {
                return `/kategori/${kategori}/read?a=${id}`;
            } else if (type === 'produk') {
                return `/produk/${kategori}/browse?f=${id}`;
            } else if (type === 'karya') {
                return `/karya/${kategori}/read?k=${id}`;
            }

            return '#';
        }

        function loadData(year, type) {
            fetch(`/arsip/${year}`)
                .then(res => res.json())
                .then(data => {
                    const resultList = document.getElementById(`result-list-${year}`);
                    const resultTitle = document.getElementById(`result-title-${year}`);
                    const resultBox = document.getElementById(`result-${year}`);

                    resultList.innerHTML = '';
                    resultTitle.textContent = type.charAt(0).toUpperCase() + type.slice(1);

                    const group = data[type];

                    if (Object.keys(group).length === 0) {
                        resultList.innerHTML = `<p><em>Tidak ada data.</em></p>`;
                    } else {
                        Object.keys(group).forEach(month => {
                            const section = document.createElement('div');
                            section.classList.add('mb-6');

                            const monthTitle = document.createElement('h3');
                            monthTitle.classList.add('text-md', 'font-semibold', 'text-gray-600', 'mb-2');
                            monthTitle.textContent = bulanIndo(month);

                            const listContainer = document.createElement('div');
                            listContainer.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2',
                                'lg:grid-cols-3', 'gap-4');

                            group[month].forEach(item => {
                                const link = document.createElement('a');
                                link.classList.add('block', 'bg-white', 'rounded-lg', 'shadow',
                                    'border', 'overflow-hidden', 'hover:shadow-lg', 'transition');
                                link.href = getDetailLink(type, item);

                                const img = document.createElement('img');
                                img.src = item.thumbnail ||
                                    'https://via.placeholder.com/300x180?text=No+Image';
                                img.alt = item.judul;
                                img.classList.add('w-full', 'h-40', 'object-cover');

                                const title = document.createElement('div');
                                title.classList.add('p-3', 'text-sm', 'font-medium', 'text-gray-800');
                                title.textContent = item.judul;

                                link.appendChild(img);
                                link.appendChild(title);
                                listContainer.appendChild(link);
                            });

                            section.appendChild(monthTitle);
                            section.appendChild(listContainer);
                            resultList.appendChild(section);
                        });
                    }

                    resultBox.classList.remove('hidden');
                });
        }
    </script>
@endsection
