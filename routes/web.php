<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAuth\LoginController;
use App\Http\Controllers\UserAuth\RegisterController;
use App\Http\Controllers\UserAuth\LogoutController;
use App\Http\Controllers\Author\BeritaController;
use App\Http\Controllers\Author\DraftController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\UserAuth\ForgotPasswordController;
use App\Http\Controllers\UserAuth\CreatePasswordController;
use App\Http\Controllers\UserAuth\VerifikasiAkunController;
use App\Http\Controllers\News\HomeNewsController;
use App\Http\Controllers\News\KampusNewsController;
use App\Http\Controllers\News\NasionalInternasionalNewsController;
use App\Http\Controllers\News\LiputanKhususNewsController;
use App\Http\Controllers\News\OlahragaNewsController;
use App\Http\Controllers\News\OpiniEsaiNewsController;
use App\Http\Controllers\News\KesenianHiburanNewsController;
use App\Http\Controllers\News\KesehatanNewsController;
use App\Http\Controllers\News\TeknologiNewsController;
use App\Http\Controllers\Author\ProdukController;
use App\Http\Controllers\Author\KaryaController;
use App\Http\Controllers\Produk\BuletinController;
use App\Http\Controllers\Produk\MajalahController;
use App\Http\Controllers\Karya\PuisiController;
use App\Http\Controllers\Karya\PantunController;
use App\Http\Controllers\Karya\SyairController;
use App\Http\Controllers\Karya\FotografiController;
use App\Http\Controllers\Karya\DesainGrafisController;
use App\Http\Controllers\UserReact\ReaksiController;
use App\Http\Controllers\Author\PublishedController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnalitikController;
use App\Http\Controllers\Admin\KotakMasukController;
use App\Http\Controllers\Admin\TentangKamiController;
use App\Http\Controllers\Admin\StrukturOrganisasiController;
use App\Http\Controllers\UserReact\BookmarkController;
use App\Http\Controllers\UserReact\KomentarController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Profile\LikedController;
use App\Http\Controllers\Profile\BookmarkedController;
use App\Http\Controllers\Setting\NotifikasiController;
use App\Http\Controllers\Setting\BantuanController;
use App\Http\Controllers\UserReact\ReportController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Setting\HubungiKamiController;
use App\Http\Controllers\Archive\ArchiveController;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman utama (Beranda)
Route::middleware(['guestOrRole'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/produk/buletin', fn() => view('produk.buletin'))->name('buletin');
    Route::get('/produk/majalah', fn() => view('produk.majalah'))->name('majalah');

    Route::get('/karya/puisi', fn() => view('karya.puisi'))->name('puisi');
    Route::get('/karya/pantun', fn() => view('karya.pantun'))->name('pantun');
    Route::get('/karya/syair', fn() => view('karya.syair'))->name('syair');
    Route::get('/karya/fotografi', fn() => view('karya.fotografi'))->name('fotografi');
    Route::get('/karya/desain-grafis', fn() => view('karya.desain-grafis'))->name('desain-grafis');
});

// Route untuk login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Route untuk register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/verifikasi-akun', [VerifikasiAkunController::class, 'showVerifikasiForm'])->name('verifikasi-akun');
Route::post('/verifikasi-akun', [VerifikasiAkunController::class, 'verifyOtp'])->name('verify-otp');

Route::get('/buat-password', [CreatePasswordController::class, 'showCreatePasswordForm'])->name('buat-password');
Route::post('/buat-password', [CreatePasswordController::class, 'storePassword']);
Route::post('/store-password', [CreatePasswordController::class, 'storePassword'])->name('store-password');

Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::get('verify-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verifyOtpForm');
Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::get('ganti-password', [ForgotPasswordController::class, 'showChangePasswordForm'])->name('password.changePasswordForm');
Route::post('ganti-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.updatePassword');
Route::post('/password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resendOtp');
Route::post('/verifikasi-akun/resend-otp', [RegisterController::class, 'resendOtp'])->name('verifikasi-akun.resendOtp');

// Route untuk logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// route setting
Route::prefix('settings')->middleware('remember.prev')->group(function () {
    Route::get('/umum', [SettingController::class, 'umumSettings'])->name('settings.umum');
    // Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('settings.notifikasi');
    Route::get('/bantuan', [BantuanController::class, 'index'])->name('settings.bantuan');
    Route::get('/hubungiKami', [HubungiKamiController::class, 'index'])->name('settings.hubungiKami');

    Route::post('/hubungiKami', [HubungiKamiController::class, 'store'])->name('settings.hubungiKami.store');

    Route::post('/akun/send-otp-current', [AccountController::class, 'sendOtpToCurrentEmail'])->name('settings.sendOtpToCurrentEmail');
    Route::post('/akun/verify-otp', [AccountController::class, 'verifyOtp'])->name('settings.verifyOtp');
    Route::post('/akun/update-email', [AccountController::class, 'updateEmail'])->name('settings.updateEmail');
    Route::post('/akun/update-password', [AccountController::class, 'updatePassword'])->name('settings.updatePassword');
});

// upload & simpan profil
Route::post('/settings/upload-profile-pic', [SettingController::class, 'uploadTempProfilePic'])->name('settings.upload.profile_pic');
Route::post('/settings/save-profile', [SettingController::class, 'saveProfile'])->name('settings.save.profile');

// Route fallback jika halaman tidak ditemukan
Route::fallback(function () {
    return view('404'); // Pastikan Anda membuat file view '404.blade.php'
});

Route::get('/forbidden', function () {
    return response()->view('403', [], 403);
});

// Route untuk Penulis
Route::middleware(['checkRole:Penulis'])->group(function () {
    // Route untuk membuat berita
    Route::get('/authors/create', function () {
        return view('authors.create');
    })->name('create-news');

    // Route untuk draf berita
    Route::get('/authors/draft', [DraftController::class, 'index'])->name('draft-media');
    Route::get('/authors/draft/{id}/edit', [DraftController::class, 'edit'])->name('draft.edit');
    Route::delete('/authors/draft/{id}', [DraftController::class, 'destroy'])->name('draft.destroy');

    // Route untuk publikasi berita
    Route::get('/authors/published', [PublishedController::class, 'index'])->name('published-media');
    Route::get('/authors/published/{id}/edit', [PublishedController::class, 'edit'])->name('published.edit');
    Route::delete('/authors/published/{id}', [PublishedController::class, 'destroy'])->name('published.destroy');

    Route::get('/authors/create-product', function () {
        return view('authors.create-product');
    })->name('create-product');

    Route::get('/authors/creation', function () {
        return view('authors.creation');
    })->name('creation');

    // Route untuk detail berita
    Route::get('/kategori/news-detail/{id}', function ($id) {
        return view('kategori.news-detail', compact('id'));
    })->name('news.detail');

    // Route edit untuk berita, produk & karya
    Route::get('/authors/edit/create-edit/{id}', [PublishedController::class, 'editNews'])->name('edit.news');
    Route::get('/authors/edit/createProduct-edit/{id}', [PublishedController::class, 'editProduct'])->name('edit.product');
    Route::get('/authors/edit/creation-edit/{id}', [PublishedController::class, 'editKarya'])->name('edit.karya');

    Route::put('/authors/update-berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::put('/authors/update-product/{id}', [ProdukController::class, 'update'])->name('produk.update');
    Route::put('/authors/update-karya/{id}', [KaryaController::class, 'update'])->name('karya.update');

    Route::get('/produk/{id}/media-preview', [ProdukController::class, 'mediaPreview'])->name('produk.media-preview');

    // Route untuk menyimpan berita
    Route::post('/author/berita/store', [BeritaController::class, 'store'])->name('author.berita.store');

    // Route untuk menyimpan produk
    Route::get('/create-product', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/create-product', [ProdukController::class, 'store'])->name('produk.store');

    // Route untuk menyimpan karya
    Route::post('/karya/store', [KaryaController::class, 'store'])->name('karya.store');
});

// Halaman Karya yang Disukai
Route::middleware(['checkRole:Penulis,Pembaca'])->group(function () {
    Route::get('/profile/liked', [LikedController::class, 'index'])->name('liked');
    Route::delete('/profile/liked/{id}', [LikedController::class, 'destroy'])->name('liked.destroy');

    // Halaman Karya yang Disimpan
    Route::get('/profile/bookmarked', [BookmarkedController::class, 'index'])->name('bookmarked');
    Route::delete('/profile/bookmarked/{id}', [BookmarkedController::class, 'destroy'])->name('bookmarked.destroy');
});

Route::middleware(['guestOrRole'])->group(function () {

    // Halaman utama
    Route::get('/', [HomeNewsController::class, 'index'])->name('home');

    // Kategori berita
    Route::get('/kategori/kampus', [KampusNewsController::class, 'index'])->name('kampus');
    Route::get('/kategori/kampus/read', [KampusNewsController::class, 'show'])->name('kampus.detail');
    Route::get('/kategori/kampus/semua', [KampusNewsController::class, 'semua'])->name('kampus.semua');

    Route::get('/kategori/nasional-internasional', [NasionalInternasionalNewsController::class, 'index'])->name('nasional-internasional');
    Route::get('/kategori/nasional-internasional/read', [NasionalInternasionalNewsController::class, 'show'])->name('nasional-internasional.detail');
    Route::get('/kategori/nasional-internasional/semua', [NasionalInternasionalNewsController::class, 'semua'])->name('nasional-internasional.semua');

    Route::get('/kategori/opini-esai', [OpiniEsaiNewsController::class, 'index'])->name('opini-esai');
    Route::get('/kategori/opini-esai/read', [OpiniEsaiNewsController::class, 'show'])->name('opini-esai.detail');
    Route::get('/kategori/opini-esai/semua', [OpiniEsaiNewsController::class, 'semua'])->name('opini-esai.semua');

    Route::get('/kategori/kesenian-hiburan', [KesenianHiburanNewsController::class, 'index'])->name('kesenian-hiburan');
    Route::get('/kategori/kesenian-hiburan/read', [KesenianHiburanNewsController::class, 'show'])->name('kesenian-hiburan.detail');
    Route::get('/kategori/kesenian-hiburan/semua', [KesenianHiburanNewsController::class, 'semua'])->name('kesenian-hiburan.semua');

    Route::get('/kategori/kesehatan', [KesehatanNewsController::class, 'index'])->name('kesehatan');
    Route::get('/kategori/kesehatan/read', [KesehatanNewsController::class, 'show'])->name('kesehatan.detail');
    Route::get('/kategori/kesehatan/semua', [KesehatanNewsController::class, 'semua'])->name('kesehatan.semua');

    Route::get('/kategori/teknologi', [TeknologiNewsController::class, 'index'])->name('teknologi');
    Route::get('/kategori/teknologi/read', [TeknologiNewsController::class, 'show'])->name('teknologi.detail');
    Route::get('/kategori/teknologi/semua', [TeknologiNewsController::class, 'semua'])->name('teknologi.semua');

    Route::get('/kategori/liputan-khusus', [LiputanKhususNewsController::class, 'index'])->name('liputan-khusus');
    Route::get('/kategori/liputan-khusus/read', [LiputanKhususNewsController::class, 'show'])->name('liputan-khusus.detail');
    Route::get('/kategori/liputan-khusus/semua', [LiputanKhususNewsController::class, 'semua'])->name('liputan-khusus.semua');

    Route::get('/kategori/olahraga', [OlahragaNewsController::class, 'index'])->name('olahraga');
    Route::get('/kategori/olahraga/read', [OlahragaNewsController::class, 'show'])->name('olahraga.detail');
    Route::get('/kategori/olahraga/semua', [OlahragaNewsController::class, 'semua'])->name('olahraga.semua');

    // Kategori dinamis
    Route::get('/kategori/{category}', [HomeNewsController::class, 'index'])->name('category');
    Route::get('/kategori/{category}/read', [HomeNewsController::class, 'show'])->name('kategori.detail');

    // Footer menu (halaman informasi)
    Route::view('/pusat-bantuan', 'header-footer.footer-menu.pusatBantuan');
    Route::get('/tentang-kami', [StrukturOrganisasiController::class, 'tentangKami'])->name('struktur.tentangKami');
    Route::get('/explant-contributor', [StrukturOrganisasiController::class, 'explantContributor'])->name('struktur.explantContributor');
    Route::get('/kode-etik', [StrukturOrganisasiController::class, 'kodeEtik'])->name('struktur.kodeEtik');
    Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'strukturOrganisasi'])->name('struktur.organisasi');
});

Route::middleware(['guestOrRole'])->group(function () {

    // route PRODUK: BULETIN
    Route::get('/produk/buletin', [BuletinController::class, 'index'])->name('buletin.index');
    Route::get('/produk/buletin/browse', [BuletinController::class, 'show'])->name('buletin.browse');
    Route::get('/produk/buletin/pdf-preview/{id}', [BuletinController::class, 'pdfPreview'])->name('buletin.pdfPreview');
    Route::get('/produk/buletin/download/{id}', [BuletinController::class, 'download'])->name('buletin.download');
    Route::get('/produk/buletin/preview', [BuletinController::class, 'preview'])->name('buletin.preview');
    Route::get('/produk/buletin/semua', [BuletinController::class, 'semua'])->name('buletin.semua');

    // route PRODUK: MAJALAH
    Route::get('/produk/majalah', [MajalahController::class, 'index'])->name('majalah.index');
    Route::get('/produk/majalah/browse', [MajalahController::class, 'show'])->name('majalah.browse');
    Route::get('/produk/majalah/pdf-preview/{id}', [MajalahController::class, 'pdfPreview'])->name('majalah.pdfPreview');
    Route::get('/produk/majalah/download/{id}', [MajalahController::class, 'download'])->name('majalah.download');
    Route::get('/produk/majalah/preview', [MajalahController::class, 'preview'])->name('majalah.preview');
    Route::get('/produk/majalah/semua', [MajalahController::class, 'semua'])->name('majalah.semua');

    // route KARYA
    Route::prefix('karya/puisi')->name('karya.puisi.')->group(function () {
        Route::get('/', [PuisiController::class, 'index'])->name('index');
        Route::get('/read', [PuisiController::class, 'show'])->name('read');
        Route::get('/semua', [PuisiController::class, 'semua'])->name('semua');
    });

    Route::prefix('karya/pantun')->name('karya.pantun.')->group(function () {
        Route::get('/', [PantunController::class, 'index'])->name('index');
        Route::get('/read', [PantunController::class, 'show'])->name('read');
        Route::get('/semua', [PantunController::class, 'semua'])->name('semua');
    });

    Route::prefix('karya/syair')->name('karya.syair.')->group(function () {
        Route::get('/', [SyairController::class, 'index'])->name('index');
        Route::get('/read', [SyairController::class, 'show'])->name('read');
        Route::get('/semua', [SyairController::class, 'semua'])->name('semua');
    });

    Route::prefix('karya/fotografi')->name('karya.fotografi.')->group(function () {
        Route::get('/', [FotografiController::class, 'index'])->name('index');
        Route::get('/read', [FotografiController::class, 'show'])->name('read');
        Route::get('/semua', [FotografiController::class, 'semua'])->name('semua');
    });

    Route::prefix('karya/desain-grafis')->name('karya.desain-grafis.')->group(function () {
        Route::get('/', [DesainGrafisController::class, 'index'])->name('index');
        Route::get('/read', [DesainGrafisController::class, 'show'])->name('read');
        Route::get('/semua', [DesainGrafisController::class, 'semua'])->name('semua');
    });

    // route search
    Route::get('/search-preview', [SearchController::class, 'preview']);
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/{section}', [SearchController::class, 'paginateSection']);

    Route::get('/arsip', [ArchiveController::class, 'index'])->name('archive.index');
    Route::get('/arsip/{year}', [ArchiveController::class, 'show'])->name('archive.show');
});

Route::post('/reaksi', [ReaksiController::class, 'store'])->name('reaksi.store');

Route::middleware('web')->group(function () {
    Route::post('/bookmark/toggle', [BookmarkController::class, 'toggle']);
});

Route::get('/komentar/{komentarId}/replies', [KomentarController::class, 'fetchReplies']);

Route::middleware(['web'])->group(function () {
    Route::post('/komentar/store', [KomentarController::class, 'store'])->name('komentar.store');
    Route::get('/komentar/{item_id}', [KomentarController::class, 'fetch'])->name('komentar.fetch');
    Route::post('/komentar/kirim', [KomentarController::class, 'store'])->name('komentar.kirim');
    Route::delete('/komentar/{id}', [KomentarController::class, 'destroy'])->name('komentar.hapus');
});

Route::middleware('web')->group(function () {
    Route::post('/report-news', [ReportController::class, 'store'])->name('report.news');
});

Route::middleware(['checkRole:Admin'])->group(function () {
    // Dashboard admin utama
    Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/dashboard-admin/analitik/konten', [AdminController::class, 'performaKonten'])->name('admin.analitik.konten');
    Route::get('/dashboard-admin/analitik/konten/download', [AdminController::class, 'downloadLaporan'])->name('admin.laporan.download');

    Route::prefix('dashboard-admin/kotak-masuk')->name('kotak-masuk.')->group(function () {
        Route::resource('/', KotakMasukController::class);
        Route::delete('/destroy', [KotakMasukController::class, 'destroy'])->name('bulk-delete');
        Route::get('/toggle-star/{id}', [KotakMasukController::class, 'toggleStar'])->name('kotak-masuk.toggle-star');
        Route::get('/{id}', [KotakMasukController::class, 'show'])->name('kotak-masuk.show');
    });

    // Halaman daftar user
    Route::get('/dashboard-admin/pengguna', [AdminUserController::class, 'user'])->name('admin.user');

    // Hapus pengguna
    Route::delete('/dashboard-admin/hapus-pengguna/{uid}', [AdminUserController::class, 'deleteUser'])
        ->name('admin.user.delete');
    Route::put('/dashboard-admin/user/change-role/{uid}', [AdminUserController::class, 'updateRole'])
        ->name('admin.user.change-role');
    // Detail pengguna
    Route::get('/dashboard-admin/detail-pengguna/{id}', [AdminUserController::class, 'detail'])->name('admin.user.detail');
    Route::delete('/dashboard-admin/delete-komentar/{id}/{komentarId}', [AdminUserController::class, 'deleteKomen'])
        ->name('admin.komentar.delete');

    // Berita Routes
    Route::get('/dashboard-admin/berita', [AdminContentController::class, 'berita'])->name('admin.berita');
    Route::get('/dashboard-admin/berita/{id}/detail', [AdminContentController::class, 'detailBerita'])->name('admin.berita.detail');;
    Route::delete('/dashboard-admin/berita/delete/{id}', [AdminContentController::class, 'delete'])
        ->name('admin.berita.delete');
    Route::get('/berita/export', [AdminContentController::class, 'exportBerita']);

    // Produk Routes
    Route::get('/dashboard-admin/produk', [AdminContentController::class, 'produk'])->name('admin.produk');
    Route::get('/dashboard-admin/produk/detail/{id}', [AdminContentController::class, 'detailProduk'])->name('admin.produk.detail');
    Route::delete('/dashboard-admin/produk/delete/{id}', [AdminContentController::class, 'deleteProduk'])->name('admin.produk.delete');
    Route::get('/dashboard-admin/produk/pdf-preview/{id}', [AdminContentController::class, 'pdfPreview'])->name('admin.pdfPreview');
    Route::get('/dashboard-admin/produk/pdf-download/{id}', [AdminContentController::class, 'downloadPdf'])->name('admin.downloadPdf');

    // Daftar karya
    Route::get('/dashboard-admin/karya', [AdminContentController::class, 'karya'])->name('admin.karya');
    Route::get('/dashboard-admin/karya/detail/{id}', [AdminContentController::class, 'detailKarya'])->name('admin.karya.detail');
    Route::delete('/dashboard-admin/karya/delete/{id}', [AdminContentController::class, 'deleteKarya'])->name('admin.karya.delete');

    Route::get('/dashboard-admin/settings', [TentangKamiController::class, 'index'])->name('admin.settings');
    Route::post('/dashboard-admin/settings/update', [TentangKamiController::class, 'updateOrCreate'])->name('admin.tentangKami.update');

    Route::get('/dashboard-admin/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('admin.organisasi.index');
    // anggota CRUD
    Route::post('/dashboard-admin/struktur-organisasi/anggota/tambah', [StrukturOrganisasiController::class, 'createAnggota'])
        ->name('admin.organisasi.create');
    Route::put('/dashboard-admin/struktur-organisasi/anggota/update/{id}', [StrukturOrganisasiController::class, 'updateAnggota'])
        ->name('admin.organisasi.update');
    Route::delete('/dashboard-admin/struktur-organisasi/anggota/delete/{id}', [StrukturOrganisasiController::class, 'destroyAnggota'])
        ->name('admin.organisasi.delete');
    // Divisi CRUD
    Route::get('/dashboard-admin/struktur-organisasi/divisi/tambah', [StrukturOrganisasiController::class, 'createDivisi'])->name('admin.divisi.create');
    Route::post('/dashboard-admin/struktur-organisasi/divisi/store', [StrukturOrganisasiController::class, 'storeDivisi'])->name('admin.divisi.store');
    Route::delete('/dashboard-admin/struktur-organisasi/divisi/delete/{id}', [StrukturOrganisasiController::class, 'destroyDivisi'])->name('admin.divisi.delete');

    // Route::get('/dashboard-admin/analitik/pengunjung', [AnalitikController::class, 'analitikPengunjung'])->name('admin.analitik.pengunjung');
});
