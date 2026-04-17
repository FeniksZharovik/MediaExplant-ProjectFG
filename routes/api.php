<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KaryaController;
use App\Http\Controllers\API\PesanController;
use App\Http\Controllers\API\BeritaController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\ReaksiController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\SignInController;
use App\Http\Controllers\API\SignUpController;
use App\Http\Controllers\API\LaporanController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\KomentarController;
use App\Http\Controllers\API\SecurityController;
use App\Http\Controllers\API\GetProfileController;
use App\Http\Controllers\API\UpdateProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan route API untuk aplikasi. Semua route
| akan dimuat melalui RouteServiceProvider dan diberikan grup middleware "api".
|
*/

// Route default untuk mengembalikan data user yang sudah terautentikasi.
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ Autentikasi pengguna (Sanctum)
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json($request->user());
});

// ✅ Auth routes
Route::post('/login', [SignInController::class, 'login']);
Route::post('/logout', [SignInController::class, 'logout'])->middleware('auth:sanctum');

// ✅ Multi-Step Registration
Route::post('/register-step1', [SignUpController::class, 'registerStep1']);
Route::post('/verify-otp', [SignUpController::class, 'verifyOtp']);
Route::post('/register-step3', [SignUpController::class, 'registerStep3']);

// ✅ Data pengguna publik
Route::get('/user', [SignUpController::class, 'getUser']);
Route::get('/user/{uid}', [SignUpController::class, 'getUserByUid']);

// Lupa Password
Route::post('/password/send-reset-otp',      [SecurityController::class, 'sendResetPasswordOtp']);
Route::post('/password/verify-reset-otp',    [SecurityController::class, 'verifyResetPasswordOtp']);
Route::post('/password/reset',               [SecurityController::class, 'resetPassword']);

// ✅ Kirim Pesan (tanpa login)
Route::post('/pesan', [PesanController::class, 'store']);

// ✅ Protected routes (membutuhkan token autentikasi)
Route::middleware('auth:sanctum')->group(function () {

     // Profil pengguna
     Route::get('/profile', [GetProfileController::class, 'getProfile']);
     Route::post('/profile/update', [UpdateProfileController::class, 'updateProfile']);
     Route::post('/profile/delete-image', [UpdateProfileController::class, 'deleteProfileImage']);
     Route::get('profile/check-username',    [UpdateProfileController::class, 'checkUsername']);

    // Ganti Password
    Route::post('/password/change',             [SecurityController::class, 'changePassword']);

    // Ganti Email - Step 1 (email lama)
    Route::post('/email/send-change-otp',        [SecurityController::class, 'sendChangeEmailOtp']);
    Route::post('/email/verify-old-email-otp',  [SecurityController::class, 'verifyOldEmailOtp']);

    // Ganti Email - Step 2 (email baru)
    Route::post('/email/send-new-email-otp',     [SecurityController::class, 'sendNewEmailOtp']);
    Route::post('/email/verify-new-email-otp',  [SecurityController::class, 'verifyNewEmailOtp']);
});



// toggle bookmark
Route::post('/bookmark/toggle', [BookmarkController::class, 'toggle']);

// toggle reaksi berita
Route::post('/reaksi/toggle', [ReaksiController::class, 'toggle']);

Route::get('/berita/terbaru', [BeritaController::class, 'getBeritaTerbaru']);
Route::get('/berita/teratas', [BeritaController::class, 'getBeritaTeratas']);
Route::get('/berita/populer', [BeritaController::class, 'getBeritaPopuler']);
Route::get('/berita/rekomendasi', [BeritaController::class, 'getBeritaRekomendasi']);
Route::get('/berita/rekomendasi-lainnya', [BeritaController::class, 'getRekomendasiLainnya']);
Route::get('/berita/terkait', [BeritaController::class, 'getBeritaTerkait']);
Route::get('/berita/topik-lainnya', [BeritaController::class, 'getBeritaTopikLainnya']);
Route::get('/berita/detail', [BeritaController::class, 'getDetailBerita']);

//produk
Route::get('/produk-majalah', [ProdukController::class, 'getProdukMajalah']);
Route::get('/produk-buletin', [ProdukController::class, 'getProdukBuletin']);
Route::get('/produk/detail', [ProdukController::class, 'getDetailProduk']);


// download produk
Route::get('/produk-majalah/{id}/media', [ProdukController::class, 'getProdukMedia']);

//karya
Route::get('/puisi/terbaru  ', [KaryaController::class, 'getPuisiTerbaru']);
Route::get('/syair/terbaru', [KaryaController::class, 'getSyairTerbaru']);
Route::get('/desain-grafis/terbaru', [KaryaController::class, 'getDesainGrafisTerbaru']);
Route::get('/fotografi/terbaru', [KaryaController::class, 'getFotografiTerbaru']);
Route::get('/karya/detail', [KaryaController::class, 'getDetailKarya']);


// komentar
Route::post('/komentar', [KomentarController::class, 'store']);
Route::get('/get-komentar', [KomentarController::class, 'index']);
Route::delete('/delete-komentar', [KomentarController::class, 'destroy']);

// search
Route::get('/berita/search', [BeritaController::class, 'searchBerita']);
Route::get('/berita/search/kategori', [BeritaController::class, 'searchByKategori']);
Route::get('/search', [SearchController::class, 'searchAll']);


//report
Route::post('/laporan', [LaporanController::class, 'store']);