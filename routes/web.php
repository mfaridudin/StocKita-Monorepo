<?php

use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Buyer\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Midtrans\PaymentController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WarehouseController;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/tes-pay', function () {
    return view('test-pay');
});

Route::post('/pay', [PaymentController::class, 'pay']);
Route::post('/midtrans/webhook', [PaymentController::class, 'webhook']);

Route::middleware(['auth', 'role:admin|owner'])->group(function () {
    Route::get('/customers/search', function (Request $request) {
        return User::role('buyer')
            ->where('name', 'like', '%'.$request->q.'%')
            ->limit(5)
            ->get(['id', 'name']);
    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/products', ProductController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/warehouse', WarehouseController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('transactions', TransactionController::class);

    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'update']);
    Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
    Route::put('/stocks/{id}', [StockController::class, 'update'])->name('stocks.update');

    Route::put('/product/update-img/{id}', [ProductController::class, 'updateImage'])->name('products.update-image');

    // Route::get('/subscribe', [SubscriptionController::class, 'create']);
    // Route::post('/midtrans/webhook', [MidtransController::class, 'webhook'])->withoutMiddleware([ValidateCsrfToken::class]);
});

Route::middleware(['auth', 'role:buyer'])
    ->prefix('buyer')
    ->name('buyer.')
    ->group(function () {

        Route::get('/dashboard', [BuyerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders');

        Route::get('/orders/{id}', [OrderController::class, 'show'])
            ->name('orders.show');
    });

require __DIR__.'/auth.php';
