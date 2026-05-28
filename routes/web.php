<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KegiatanController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return 'LARAVEL HIDUP';
});
/*
|--------------------------------------------------------------------------
| Kegiatan
|--------------------------------------------------------------------------
*/

Route::get('/tambah', [KegiatanController::class, 'create'])->name('kegiatan.create');
Route::post('/store', [KegiatanController::class, 'store'])->name('kegiatan.store');

Route::get('/edit/{id}', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
Route::post('/update/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');

Route::get('/delete/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.delete');


/*
|--------------------------------------------------------------------------
| Filter
|--------------------------------------------------------------------------
*/

Route::get('/status/{status}', [KegiatanController::class, 'filter'])->name('kegiatan.filter');

Route::get('/akan-datang', [KegiatanController::class, 'akanDatang'])->name('kegiatan.akan');

Route::get('/selesai', [KegiatanController::class, 'selesai'])->name('kegiatan.selesai');

/*
|--------------------------------------------------------------------------
| Halaman Tambahan
|--------------------------------------------------------------------------
*/

Route::get('/kalender', [KegiatanController::class, 'kalender'])->name('kegiatan.kalender');

Route::get('/semua-kegiatan', [KegiatanController::class, 'semua'])->name('kegiatan.semua');

Route::get('/profile', function () {
    return view('profile');
})->name('profile.view');

Route::get('/akan-datang', [KegiatanController::class, 'akanDatang'])->name('kegiatan.akan');
Route::get('/selesai', [KegiatanController::class, 'selesai'])->name('kegiatan.selesai');