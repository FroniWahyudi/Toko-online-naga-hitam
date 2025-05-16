<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;

// Redirect root ke halaman login backend
Route::get('/', function () {
    return redirect()->route('backend.login');
});

// 🔐 Login & Logout
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.submit');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// 🏠 Beranda
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
    ->name('backend.beranda')
    ->middleware('auth');

// 👤 Manajemen User
Route::resource('backend/user', UserController::class, ['as' => 'backend'])
    ->middleware('auth');

// 🗂️ Manajemen Kategori
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])
    ->middleware('auth');

// 📦 Manajemen Produk
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])
    ->middleware('auth');

// 📸 Tambah Foto Produk
Route::post('backend/foto-produk/store', [ProdukController::class, 'storeFoto'])
    ->name('backend.foto_produk.store')
    ->middleware('auth');

// ❌ Hapus Foto Produk
Route::delete('backend/foto-produk/{id}', [ProdukController::class, 'destroyFoto'])
    ->name('backend.foto_produk.destroy')
    ->middleware('auth');
