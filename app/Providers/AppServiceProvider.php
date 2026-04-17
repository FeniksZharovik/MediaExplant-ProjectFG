<?php

namespace App\Providers;

use App\Models\API\Berita;
use App\Models\API\Produk;
use App\Models\API\Karya;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'Berita' => Berita::class,
            'Produk' => Produk::class,
            'Karya' => Karya::class,
        ]);

        Blade::if('role', function ($role) {
            $user = session('user'); // or use Auth::user() if you migrate later
            return $user && $user->role === $role;
        });
    }
}