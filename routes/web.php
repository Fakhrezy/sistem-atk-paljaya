<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMonitoringController;
use App\Http\Controllers\UserPengambilanController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// Dashboard route
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

//!------------------------------------------------------------------ Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(\App\Http\Controllers\AdminController::class)->group(function () {
        Route::get('/admin/dashboard', 'dashboard')->name('admin.dashboard');
    });

    Route::controller(\App\Http\Controllers\BarangController::class)->group(function () {
        Route::get('/admin/barang', 'index')->name('admin.barang');
        Route::get('/admin/barang/print', 'print')->name('admin.barang.print');
        Route::get('/admin/barang/export', 'export')->name('admin.barang.export');
        Route::get('/admin/barang/create', 'create')->name('admin.barang.create');
        Route::post('/admin/barang', 'store')->name('admin.barang.store');
        Route::get('/admin/barang/{barang}/edit', 'edit')->name('admin.barang.edit');
        Route::put('/admin/barang/{barang}', 'update')->name('admin.barang.update');
        Route::delete('/admin/barang/{barang}', 'destroy')->name('admin.barang.destroy');
    });

    // Route :-> user management
    Route::controller(App\Http\Controllers\AdminUserController::class)->group(function () {
        Route::get('/admin/users', 'index')->name('admin.users');
        Route::get('/admin/users/create', 'create')->name('admin.users.create');
        Route::post('/admin/users', 'store')->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', 'edit')->name('admin.users.edit');
        Route::put('/admin/users/{user}', 'update')->name('admin.users.update');
        Route::delete('/admin/users/{user}', 'destroy')->name('admin.users.destroy');
    });

    // Pengambilan Barang Routes
    Route::controller(App\Http\Controllers\Admin\PengambilanController::class)->group(function () {
        Route::get('/admin/pengambilan', 'index')->name('admin.pengambilan.index');
        Route::get('/admin/pengambilan/stock/{barang}', 'getStock')->name('admin.pengambilan.stock');
    });

    // Route :-> monitoring pengambilan
    Route::controller(App\Http\Controllers\MonitoringController::class)->group(function () {
        Route::get('/admin/monitoring', 'index')->name('admin.monitoring');
        Route::get('/admin/monitoring/create', 'create')->name('admin.monitoring.create');
        Route::post('/admin/monitoring', 'store')->name('admin.monitoring.store');
        Route::get('/admin/monitoring/{monitoring}', 'show')->name('admin.monitoring.show');
        Route::get('/admin/monitoring/{monitoring}/edit', 'edit')->name('admin.monitoring.edit');
        Route::put('/admin/monitoring/{monitoring}', 'update')->name('admin.monitoring.update');
        Route::delete('/admin/monitoring/{monitoring}', 'destroy')->name('admin.monitoring.destroy');
    });
    Route::controller(App\Http\Controllers\MonitoringBarangController::class)->group(function () {
        Route::get('/admin/monitoring-barang', 'index')->name('admin.monitoring-barang.index');
        Route::get('/admin/monitoring-barang/{id}/edit', 'edit')->name('admin.monitoring-barang.edit');
        Route::put('/admin/monitoring-barang/{id}', 'update')->name('admin.monitoring-barang.update');
        Route::post('/admin/monitoring-barang/{id}/update-status', 'updateStatus')->name('admin.monitoring-barang.update-status');
        Route::delete('/admin/monitoring-barang/{id}', 'destroy')->name('admin.monitoring-barang.destroy');
    });
    // Cart Routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/admin/cart', 'index')->name('admin.cart.index');
        Route::post('/admin/cart/add', 'add')->name('admin.cart.add');
        Route::post('/admin/cart/update/{cart}', 'update')->name('admin.cart.update');
        Route::delete('/admin/cart/remove/{cart}', 'remove')->name('admin.cart.remove');
        Route::delete('/admin/cart/clear', 'clear')->name('admin.cart.clear');
        Route::get('/admin/cart/count', 'count')->name('admin.cart.count');
        Route::post('/admin/cart/checkout', 'checkout')->name('admin.cart.checkout');
    });

    // Route :-> monitoring pengadaan
    Route::controller(App\Http\Controllers\Admin\MonitoringPengadaanController::class)->group(function () {
        Route::get('/admin/monitoring-pengadaan', 'index')->name('admin.monitoring-pengadaan.index');
        Route::get('/admin/monitoring-pengadaan/{id}/edit', 'edit')->name('admin.monitoring-pengadaan.edit');
        Route::put('/admin/monitoring-pengadaan/{id}', 'update')->name('admin.monitoring-pengadaan.update');
        Route::post('/admin/monitoring-pengadaan/{id}/status', 'updateStatus')->name('admin.monitoring-pengadaan.update-status');
        Route::delete('/admin/monitoring-pengadaan/{id}', 'destroy')->name('admin.monitoring-pengadaan.destroy');
    });

    // Usulan Pengadaan Admin Routes
    Route::controller(App\Http\Controllers\Admin\UsulanPengadaanController::class)->group(function () {
        Route::get('/admin/usulan', 'index')->name('admin.usulan.index');
        Route::post('/admin/usulan', 'store')->name('admin.usulan.store');
    });

    // Usulan Cart Admin Routes
    Route::controller(App\Http\Controllers\Admin\KeranjangUsulanController::class)->group(function () {
        Route::get('/admin/usulan/cart', 'index')->name('admin.usulan.cart.index');
        Route::post('/admin/usulan/cart/add', 'add')->name('admin.usulan.cart.add');
        Route::post('/admin/usulan/cart/update/{cart}', 'update')->name('admin.usulan.cart.update');
        Route::delete('/admin/usulan/cart/remove/{cart}', 'remove')->name('admin.usulan.cart.remove');
        Route::delete('/admin/usulan/cart/clear', 'clear')->name('admin.usulan.cart.clear');
        Route::get('/admin/usulan/cart/count', 'count')->name('admin.usulan.cart.count');
        Route::post('/admin/usulan/cart/submit', 'submit')->name('admin.usulan.cart.submit');
    });
});



//!----------------------------------------------------------------- User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

    // Route untuk monitoring barang user
    Route::get('/user/monitoring', [UserMonitoringController::class, 'index'])
        ->name('user.monitoring.index');
    // Pengambilan Barang Routes (User)
    Route::controller(UserPengambilanController::class)->group(function () {
        Route::get('/user/pengambilan', 'index')->name('user.pengambilan.index');
        Route::get('/user/pengambilan/stock/{barang}', 'getStock')->name('user.pengambilan.stock');
    });
    // Usulan Routes
    Route::controller(App\Http\Controllers\User\UsulanController::class)->group(function () {
        Route::get('/user/usulan', 'index')->name('user.usulan.index');
        Route::post('/user/usulan', 'store')->name('user.usulan.store');
    });
    // Usulan Cart Routes
    Route::controller(App\Http\Controllers\User\KeranjangUsulanController::class)->group(function () {
        Route::get('/user/usulan/cart', 'index')->name('user.usulan.cart.index');
        Route::post('/user/usulan/cart/add', 'add')->name('user.usulan.cart.add');
        Route::post('/user/usulan/cart/update/{cart}', 'update')->name('user.usulan.cart.update');
        Route::delete('/user/usulan/cart/remove/{cart}', 'remove')->name('user.usulan.cart.remove');
        Route::delete('/user/usulan/cart/clear', 'clear')->name('user.usulan.cart.clear');
        Route::get('/user/usulan/cart/count', 'count')->name('user.usulan.cart.count');
        Route::post('/user/usulan/cart/submit', 'submit')->name('user.usulan.cart.submit');
    });
    // Cart Routes
    Route::controller(CartController::class)->group(function () {
        Route::get('/user/cart', 'index')->name('user.cart.index');
        Route::post('/user/cart/add', 'add')->name('user.cart.add');
        Route::post('/user/cart/update/{cart}', 'update')->name('user.cart.update');
        Route::delete('/user/cart/remove/{cart}', 'remove')->name('user.cart.remove');
        Route::delete('/user/cart/clear', 'clear')->name('user.cart.clear');
        Route::get('/user/cart/count', 'count')->name('user.cart.count');
        Route::post('/user/cart/checkout', 'checkout')->name('user.cart.checkout');
    });


    if (app()->environment('local')) {
        Route::prefix('test')->name('test.')->middleware(['auth', 'role:admin'])->group(function () {
            Route::get('/cart', function () {
                $user = auth()->user();
                $barang = \App\Models\Barang::first();

                if (!$user) {
                    return response()->json(['error' => 'Not authenticated'], 401);
                }

                $cartItems = \App\Models\Cart::with('barang')->where('user_id', $user->id)->get();
                return response()->json([
                    'user_id' => $user->id,
                    'cart_count' => $cartItems->count(),
                    'cart_items' => $cartItems->map(fn($item) => [
                        'id' => $item->id,
                        'barang_nama' => $item->barang?->nama_barang,
                        'quantity' => $item->quantity
                    ])
                ]);
            })->name('cart.view');

            Route::post('/cart/add', function (Request $request) {
                try {
                    $barang = \App\Models\Barang::first();
                    if (!$barang) {
                        return response()->json(['error' => 'No barang found'], 404);
                    }

                    $cart = \App\Models\Cart::create([
                        'user_id' => auth()->id(),
                        'id_barang' => $barang->id_barang,
                        'quantity' => 1,
                        'bidang' => 'test',
                        'keterangan' => 'Test item'
                    ]);

                    return response()->json([
                        'success' => true,
                        'cart_id' => $cart->id
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            })->name('cart.add');
        });
    }
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
