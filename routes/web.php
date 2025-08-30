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

Route::get('/artikel', [HomeController::class, 'articlesIndex'])
  ->name('articles.public.index');

Route::get('/artikel', [ArticleController::class, 'publicIndex'])
    ->name('articles.public.index');

Route::get('/artikel/{slug}', [HomeController::class, 'showArticle'])
  ->name('articles.show');

Route::get('/galeri', [GalleryController::class, 'public'])
  ->name('galleries.public');

/*
|--------------------------------------------------------------------------
| Fokus Kerja (Program)
|--------------------------------------------------------------------------
*/
Route::prefix('fokus-kerja')->name('fokus-kerja.')->group(function () {
  Route::get('/pengelolaan-sampah-dan-daur-ulang', [FocusController::class, 'sampah'])
    ->name('sampah');
  Route::get('/edukasi-dan-kampanye-lingkungan', [FocusController::class, 'edukasi'])
    ->name('edukasi');
  Route::get('/pemberdayaan-masyarakat-lokal', [FocusController::class, 'pemberdayaan'])
    ->name('pemberdayaan');
  Route::get('/monitoring-dan-pengawasan-laut', [FocusController::class, 'monitoring'])
    ->name('monitoring');
  Route::get('/kolaborasi-dan-jejaringan', [FocusController::class, 'kolaborasi'])
    ->name('kolaborasi');
});

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
| Donasi (Publik)
|--------------------------------------------------------------------------
*/
Route::get('/donasi',  [DonationController::class, 'create'])->name('donations.create');
Route::post('/donasi', [DonationController::class, 'store'])->name('donations.store');

/*
|----------------------------------------------------------------------
| Auth Admin
|----------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
  // Form login admin
  Route::get('login',  [AdminAuthController::class, 'showLoginForm'])
    ->middleware('guest:admin')
    ->name('admin.login');

  // Submit login
  Route::post('login', [AdminAuthController::class, 'login'])
    ->middleware('guest:admin')
    ->name('admin.login.submit');

  // Logout
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
    Route::resource('articles',  ArticleController::class)->except(['show']);
    Route::resource('galleries', GalleryController::class)->except(['show']);
    Route::resource('donations', DonationController::class)->only(['index','show','edit','update','destroy']);
});