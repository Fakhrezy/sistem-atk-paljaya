<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/barang', [App\Http\Controllers\BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/barang/create', [App\Http\Controllers\BarangController::class, 'create'])->name('admin.barang.create');
    Route::post('/admin/barang', [App\Http\Controllers\BarangController::class, 'store'])->name('admin.barang.store');
    Route::get('/admin/barang/{barang}/edit', [App\Http\Controllers\BarangController::class, 'edit'])->name('admin.barang.edit');
    Route::put('/admin/barang/{barang}', [App\Http\Controllers\BarangController::class, 'update'])->name('admin.barang.update');
    Route::delete('/admin/barang/{barang}', [App\Http\Controllers\BarangController::class, 'destroy'])->name('admin.barang.destroy');
});

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('user.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
