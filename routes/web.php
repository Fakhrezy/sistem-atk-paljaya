<?php

use App\Http\Controllers\ProfileController;
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

    // Monitoring Barang Routes
    Route::controller(App\Http\Controllers\MonitoringBarangController::class)->group(function () {
        Route::get('/admin/monitoring-barang', 'index')->name('admin.monitoring-barang.index');
        Route::post('/admin/monitoring-barang/{id}/update-status', 'updateStatus')->name('admin.monitoring-barang.update-status');
        Route::delete('/admin/monitoring-barang/{id}', 'destroy')->name('admin.monitoring-barang.destroy');
    });
});

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->name('user.dashboard');

    // Pengambilan Barang Routes (view items for cart)
    Route::controller(App\Http\Controllers\UserPengambilanController::class)->group(function () {
        Route::get('/user/pengambilan', 'index')->name('user.pengambilan.index');
        Route::get('/user/pengambilan/stock/{barang}', 'getStock')->name('user.pengambilan.stock');
    });

    // Cart Routes
    Route::controller(App\Http\Controllers\CartController::class)->group(function () {
        Route::get('/user/cart', 'index')->name('user.cart.index');
        Route::post('/user/cart/add', 'add')->name('user.cart.add');
        Route::post('/user/cart/update/{cart}', 'update')->name('user.cart.update');
        Route::delete('/user/cart/remove/{cart}', 'remove')->name('user.cart.remove');
        Route::delete('/user/cart/clear', 'clear')->name('user.cart.clear');
        Route::get('/user/cart/count', 'count')->name('user.cart.count');
        Route::post('/user/cart/checkout', 'checkout')->name('user.cart.checkout');
    });

        // Debug route - can be removed later
    Route::get('/user/test-cart', function() {
        $user = auth()->user();
        $barang = \App\Models\Barang::first();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $cartItems = \App\Models\Cart::with('barang')->where('user_id', $user->id)->get();
        $cartByBidang = $cartItems->groupBy('bidang');

        return response()->json([
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
            'is_authenticated' => auth()->check(),
            'sample_barang' => $barang ? [
                'id' => $barang->id_barang,
                'nama' => $barang->nama_barang,
                'stok' => $barang->stok
            ] : null,
            'cart_count' => $cartItems->count(),
            'cart_items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'barang_id' => $item->id_barang,
                    'barang_nama' => $item->barang ? $item->barang->nama_barang : 'N/A',
                    'quantity' => $item->quantity,
                    'bidang' => $item->bidang,
                    'keterangan' => $item->keterangan
                ];
            }),
            'cart_by_bidang' => $cartByBidang->map(function($items, $bidang) {
                return [
                    'bidang' => $bidang,
                    'count' => $items->count(),
                    'items' => $items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'barang_nama' => $item->barang ? $item->barang->nama_barang : 'N/A',
                            'quantity' => $item->quantity
                        ];
                    })
                ];
            })
        ]);
    })->name('user.test.cart');

    Route::post('/user/test-cart-add', function(Request $request) {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Not authenticated'], 401);
            }

            $barang = \App\Models\Barang::first();
            if (!$barang) {
                return response()->json(['error' => 'No barang found'], 404);
            }

            $cart = \App\Models\Cart::create([
                'user_id' => $user->id,
                'id_barang' => $barang->id_barang,
                'quantity' => 1,
                'bidang' => 'test',
                'keterangan' => 'test item via debug route'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test cart item added successfully',
                'cart_id' => $cart->id,
                'user_id' => $user->id,
                'barang_id' => $barang->id_barang,
                'total_cart_items' => \App\Models\Cart::where('user_id', $user->id)->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception: ' . $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : 'Set APP_DEBUG=true to see trace'
            ], 500);
        }
    })->name('user.test.cart.add')->withoutMiddleware(['auth']);

    // Quick login route for testing
    Route::get('/quick-login/{role?}', function($role = 'user') {
        $user = \App\Models\User::where('role', $role)->first();
        if ($user) {
            auth()->login($user);
            return redirect()->route('user.cart.index');
        }
        return redirect()->route('login')->with('error', 'User not found');
    })->name('quick.login')->withoutMiddleware(['auth']);

    // Test cart add via GET for debugging
    Route::get('/user/test-cart-add', function() {
        $user = auth()->user();
        $barang = \App\Models\Barang::first();

        if (!$user || $user->role !== 'user') {
            return response()->json(['error' => 'Must be logged in as user']);
        }

        if (!$barang) {
            return response()->json(['error' => 'No barang found']);
        }

        try {
            $cart = \App\Models\Cart::create([
                'user_id' => $user->id,
                'id_barang' => $barang->id_barang,
                'quantity' => 1,
                'bidang' => 'umum',
                'keterangan' => 'test via GET',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart item created successfully!',
                'cart_id' => $cart->id,
                'cart_data' => $cart->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    })->name('user.test.cart.add');

    // Cart test page
    Route::get('/cart-test', function() {
        return view('cart-test');
    })->name('cart.test');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
