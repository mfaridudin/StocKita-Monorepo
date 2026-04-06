<?php

use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Buyer\OrderController;
use App\Http\Controllers\CategoryController;
// Controllers
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Midtrans\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WarehouseController;
use App\Models\User;
// Buyer
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/pay', [PaymentController::class, 'pay']);
Route::post('/midtrans/webhook', [PaymentController::class, 'webhook']);

Route::view('/privacy', 'privacy');
Route::view('/terms', 'terms');
Route::view('/dmca', 'dmca');

/*
|--------------------------------------------------------------------------
| Auth Routes (tanpa subscription)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Subscription (HARUS bisa diakses walau belum aktif)
    Route::get('/subscription', [PaymentController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [PaymentController::class, 'upgrade'])->name('subscription.upgrade');
});

/*
|--------------------------------------------------------------------------
| Admin & Owner (BUTUH subscription)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|owner', 'subscription.active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/checkout', [PaymentController::class, 'checkout']);

    // Search
    Route::get('/customers/search', function (Request $request) {
        return User::role('buyer')
            ->where('name', 'like', '%'.$request->q.'%')
            ->limit(5)
            ->get(['id', 'name']);
    });

    // Resources
    Route::resources([
        'roles' => RoleController::class,
        'products' => ProductController::class,
        'categories' => CategoryController::class,
        'warehouse' => WarehouseController::class,
        'customers' => CustomerController::class,
        'transactions' => TransactionController::class,
    ]);

    // Custom Actions
    Route::post('/customers/{id}/send-email', [CustomerController::class, 'sendEmail'])
        ->name('customers.sendEmail');

    Route::put('/product/update-img/{id}', [ProductController::class, 'updateImage'])
        ->name('products.update-image');

    Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
    Route::put('/stocks/{id}', [StockController::class, 'update'])->name('stocks.update');

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);

    Route::post('/owner', [SettingController::class, 'storeOwner'])->name('owners.store');
    Route::put('/owner/{id}', [SettingController::class, 'updateOwner']);
    Route::put('/store/{id}', [SettingController::class, 'updateStore']);
});

/*
|--------------------------------------------------------------------------
| Buyer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {

        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    });

require __DIR__.'/auth.php';
