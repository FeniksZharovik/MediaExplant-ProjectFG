<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\TentangKami;
use Illuminate\Support\Str;

class TentangKamiController extends Controller
{
    public function index()
    {
        $data = TentangKami::first();
        return view('dashboard-admin.menu.settings.tentang-kami', compact('data'));
    }
    
    public function updateOrCreate(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'email' => 'nullable|email',
            'nomorHp' => 'nullable|string',
            'tentangKami' => 'nullable|string',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'fokus_utama' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'youtube' => 'nullable|string',
            'kodeEtik' => 'nullable|string',
            'explantContributor' => 'nullable|string',
        ]);
    
        // Remove empty paragraphs and unescape special characters
        $htmlFields = ['tentangKami', 'kodeEtik', 'explantContributor', 'fokus_utama'];
    
        foreach ($htmlFields as $field) {
            if (isset($validatedData[$field])) {
                // Unescape HTML entities first
                $content = html_entity_decode($validatedData[$field], ENT_QUOTES, 'UTF-8');
                
                // Remove empty paragraphs
                $content = preg_replace('/<p[^>]*>\s*<\/p>/i', '', $content);
                
                $validatedData[$field] = $content;
            }
        }
    
        // Save logic (no changes needed)
        $data = TentangKami::first();
        if ($data) {
            $data->update($validatedData);
        } else {
            $validatedData['id'] = 1; // Use fixed ID for single settings row
            TentangKami::create($validatedData);
        }
    
        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }    
}