<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckGuestOrRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        // Jika user tidak login (guest), izinkan
        if (!$user) {
            return $next($request);
        }

        // Jika user punya role selain Admin, izinkan
        if (in_array($user->role, ['Penulis', 'Pembaca'])) {
            return $next($request);
        }

        // Jika Admin → redirect ke 404
        return redirect('/404');
    }
}
