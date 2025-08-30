<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\FocusController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/** ===== Artikel (Publik) =====
 * - Index: /artikel  -> ArticleController@publicIndex
 * - Detail: /artikel/{slug} -> HomeController@showArticle (biarkan sesuai implementasi Anda)
 */
Route::get('/artikel', [ArticleController::class, 'publicIndex'])
    ->name('articles.public.index');

Route::get('/artikel/{slug}', [HomeController::class, 'showArticle'])
    ->where('slug', '[A-Za-z0-9\-]+')
    ->name('articles.show');

/** ===== Galeri (Publik) ===== */
Route::get('/galeri', [GalleryController::class, 'public'])
    ->name('galleries.public');

/** ===== Streaming Media (Publik) =====
 * Galeri: gambar/video
 * Artikel: thumbnail & dokumentasi (gambar)
 */
Route::get('/media/gallery/{gallery}', [GalleryController::class, 'media'])
    ->name('galleries.media');

Route::get('/media/articles/{article}/thumb', [ArticleController::class, 'thumb'])
    ->name('articles.thumb');

Route::get('/media/articles/{article}/docs/{i}', [ArticleController::class, 'doc'])
    ->whereNumber('i')
    ->name('articles.doc');

/*
|--------------------------------------------------------------------------
| Mari Beraksi
|--------------------------------------------------------------------------
*/
Route::prefix('mari-beraksi')->name('actions.')->group(function () {
    Route::get('/relawan', [ActionController::class, 'relawan'])->name('relawan');
    Route::get('/magang',  [ActionController::class, 'magang'])->name('magang');
});

/*
|--------------------------------------------------------------------------
| Fokus Kerja (Program)
|--------------------------------------------------------------------------
*/
Route::prefix('fokus-kerja')->name('fokus-kerja.')->group(function () {
    Route::get('/pengelolaan-sampah-dan-daur-ulang', [FocusController::class, 'sampah'])->name('sampah');
    Route::get('/edukasi-dan-kampanye-lingkungan',   [FocusController::class, 'edukasi'])->name('edukasi');
    Route::get('/pemberdayaan-masyarakat-lokal',     [FocusController::class, 'pemberdayaan'])->name('pemberdayaan');
    Route::get('/monitoring-dan-pengawasan-laut',    [FocusController::class, 'monitoring'])->name('monitoring');
    Route::get('/kolaborasi-dan-jejaringan',         [FocusController::class, 'kolaborasi'])->name('kolaborasi');
});

/*
|--------------------------------------------------------------------------
| Donasi (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/donasi',  [DonationController::class, 'create'])->name('donations.create');
Route::post('/donasi', [DonationController::class, 'store'])->name('donations.store');

/*
|--------------------------------------------------------------------------
| Auth Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('login',  [AdminAuthController::class, 'showLoginForm'])
        ->middleware('guest:admin')
        ->name('admin.login');

    Route::post('login', [AdminAuthController::class, 'login'])
        ->middleware('guest:admin')
        ->name('admin.login.submit');

    Route::post('logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('admin.logout');
});

// Alias: /login -> /admin/login
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

/*
|--------------------------------------------------------------------------
| Dashboard (Wajib login admin)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('admin.404')->name('dashboard');

Route::middleware('admin.404')->prefix('dashboard')->group(function () {
    // Resource names tetap "articles.*", "galleries.*", "donations.*"
    Route::resource('articles',  ArticleController::class)->except(['show']);
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::resource('donations', DonationController::class)->only(['index','show','edit','update','destroy']);

    // (Opsional) route file bukti donasi jika Anda punya action-nya
    Route::get('/media/donations/{donation}', [DonationController::class, 'file'])
        ->name('donations.file');
});
