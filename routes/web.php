<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;

// Redirect root to backend login
Route::get('/', function () {
    return redirect()->route('backend.login');
});

// Auth Routes
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// Dashboard / Beranda (protected)
Route::get('backend/beranda', [BerandaController::class, 'index'])->name('backend.beranda')->middleware('auth');

// User Routes
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');

// Laporan User
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser')->middleware('auth');
Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser')->middleware('auth');

// Kategori Routes
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');

// Produk Routes
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');

// Foto Produk - Tambah & Hapus (protected)
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');

Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk')->middleware('auth');
Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk')->middleware('auth');
