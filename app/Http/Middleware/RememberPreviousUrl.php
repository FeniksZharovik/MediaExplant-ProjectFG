<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RememberPreviousUrl
{
    public function handle($request, Closure $next)
    {
        $previousUrl = url()->previous();

        // Cek jika sebelumnya bukan route settings
        if (!str_contains($previousUrl, '/settings')) {
            Session::put('settings_previous_url', $previousUrl);
        }

        return $next($request);
    }
}
