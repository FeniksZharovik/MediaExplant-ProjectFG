<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HubungiKamiController extends Controller
{
    public function index()
    {
        return view('settings.hubungiKami');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:90',
            'email' => 'required|email|max:90',
            'pesan' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $mediaPath = null;
        if ($request->hasFile('gambar')) {
            $mediaPath = base64_encode(file_get_contents($request->file('gambar')->getRealPath()));
        }

        $id = Str::random(12);

        DB::table('pesan')->insert([
            'id' => $id,
            'nama' => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
            'media' => $mediaPath,
            'created_at' => Carbon::now(),
            'status_read' => 'belum',
            'status' => 'masukan'
        ]);

        return response()->json(['status' => 'success', 'message' => 'Pesan berhasil dikirim!']);
    }
}
