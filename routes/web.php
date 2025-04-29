<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;

Route::get('/', function () {
    return redirect()->route('backend.login');
});

// Login & Logout
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login.submit');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');
Route::get('login', fn() => redirect()->route('backend.login'))->name('login');

// ✅ Route Beranda (Dashboard)
Route::get('backend/beranda', [BerandaController::class, 'index'])
    ->middleware('auth')
    ->name('backend.beranda');

// Resource dengan middleware auth
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');
