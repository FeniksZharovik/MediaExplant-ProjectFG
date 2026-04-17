<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author\Berita;
use App\Models\Author\Tag;
use App\Models\API\Karya;
use App\Models\API\Produk;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Exports\BeritaExport;
use App\Models\API\Reaksi;
use Maatwebsite\Excel\Facades\Excel;

class AnalitikController extends Controller
{
    // Konten
    public function analitikKonten() {
        return view('dashboard-admin.menu.analitik.konten');
    }

    // Pengunjung
    public function analitikPengunjung() {
        return view('dashboard-admin.menu.analitik.pengunjung');
    }
}