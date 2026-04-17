<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // Hapus cookie user_uid
        Cookie::queue(Cookie::forget('user_uid'));

        // Hapus sesi user
        $request->session()->forget('user'); 

        // Redirect ke halaman login
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
