<!DOCTYPE html>
<html>
<head>
    <title>Laporan Analitik Konten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0 0;
        }

        .counter-cards {
            margin-bottom: 30px;
        }

        .counter-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
            margin-bottom: 15px;
            width: 100%;
        }

        .counter-card i {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .counter-card .label {
            font-size: 12px;
            color: #555;
        }

        .counter-card .value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .table-container {
            margin-bottom: 40px;
        }

        .table-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            font-size: 10px;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .footer {
            text-align: right;
            font-size: 10px;
            color: #999;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Analitik Konten</h1>
            <p>Periode: {{ $period == '7_hari' ? '7 Hari Ini' : ($period == 'bulan' ? 'Bulan Ini' : 'Tahun Ini') }}</p>
        </div>

        <div class="table-container">
            <div class="table-title">Total Reaksi pengguna terhadap konten</div>
            <table>
                <thead>
                    <tr>
                        <th>Like</th>
                        <th>Dislike</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>{{ $totalLike ?? 0}}</td>
                            <td>{{ $totalDislike ?? 0}}</td>
                            <td>{{ $totalKomentar ?? 0}}</td>
                        </tr>                        
                </tbody>
            </table>
        </div>

        <!-- Tabel Berita -->
        <div class="table-container">
            <div class="table-title">Trend Publikasi Berita</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($beritaPerTanggal as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel Karya -->
        <div class="table-container">
            <div class="table-title">Trend Publikasi Karya</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyaPerTanggal as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel Produk -->
        <div class="table-container">
            <div class="table-title">Trend Publikasi Produk</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produkPerTanggal as $item)
                        <tr>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel Engagement -->
        <div class="table-container">
            <div class="table-title">Engagement</div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Like</th>
                        <th>Dislike</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($likePerTanggal as $key => $like)
                        <tr>
                            <td>{{ $like->tanggal }}</td>
                            <td>{{ $like->total }}</td>
                            <td>{{ $dislikePerTanggal[$key]->total ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            Laporan dibuat pada: {{ now()->format('d-m-Y H:i:s') }}
        </div>
    </div>
</body>
</html>