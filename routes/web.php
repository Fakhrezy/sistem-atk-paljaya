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

// Dashboard route - redirect based on user role
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(App\Http\Controllers\AdminController::class)->group(function () {
        Route::get('/admin/dashboard', 'dashboard')->name('admin.dashboard');
    });

    Route::controller(App\Http\Controllers\BarangController::class)->group(function () {
        Route::get('/admin/barang', 'index')->name('admin.barang');
        Route::get('/admin/barang/print', 'print')->name('admin.barang.print');
        Route::get('/admin/barang/export', 'export')->name('admin.barang.export');
        Route::get('/admin/barang/create', 'create')->name('admin.barang.create');
        Route::post('/admin/barang', 'store')->name('admin.barang.store');
        Route::get('/admin/barang/{barang}/edit', 'edit')->name('admin.barang.edit');
        Route::put('/admin/barang/{barang}', 'update')->name('admin.barang.update');
        Route::delete('/admin/barang/{barang}', 'destroy')->name('admin.barang.destroy');
    });

    Route::controller(App\Http\Controllers\AdminUserController::class)->group(function () {
        Route::get('/admin/users', 'index')->name('admin.users');
        Route::get('/admin/users/create', 'create')->name('admin.users.create');
        Route::post('/admin/users', 'store')->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', 'edit')->name('admin.users.edit');
        Route::put('/admin/users/{user}', 'update')->name('admin.users.update');
        Route::delete('/admin/users/{user}', 'destroy')->name('admin.users.destroy');
    });

    Route::controller(App\Http\Controllers\MonitoringController::class)->group(function () {
        Route::get('/admin/monitoring', 'index')->name('admin.monitoring');
        Route::get('/admin/monitoring/create', 'create')->name('admin.monitoring.create');
        Route::post('/admin/monitoring', 'store')->name('admin.monitoring.store');
        Route::get('/admin/monitoring/{monitoring}', 'show')->name('admin.monitoring.show');
        Route::get('/admin/monitoring/{monitoring}/edit', 'edit')->name('admin.monitoring.edit');
        Route::put('/admin/monitoring/{monitoring}', 'update')->name('admin.monitoring.update');
        Route::delete('/admin/monitoring/{monitoring}', 'destroy')->name('admin.monitoring.destroy');
    });
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
