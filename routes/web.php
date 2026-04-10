<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Buyer\DashboardController as BuyerDashboardController;
use App\Http\Controllers\Buyer\OrderController;
use App\Http\Controllers\CategoryController;
// Controllers
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Midtrans\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WarehouseController;
use App\Models\Category;
use App\Models\Customer;
use App\Models\User;
// Buyer
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

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

Route::get('/blog/{slug}', function ($slug) {

    $articles = config('blog');

    if (! isset($articles[$slug])) {
        abort(404);
    }

    return view('blogs.detail', [
        'title' => $articles[$slug]['title'],
        'content' => $articles[$slug]['content'],
        'image' => $articles[$slug]['image'],
    ]);
});

Route::get('/features/{slug}', function ($slug) {

    $features = config('features');

    abort_if(! isset($features[$slug]), 404);

    return view('features.detail', [
        'title' => $features[$slug]['title'],
        'excerpt' => $features[$slug]['excerpt'],
        'image' => $features[$slug]['image'],
        'description' => $features[$slug]['description'],
        'steps' => $features[$slug]['steps'],
        'benefits' => $features[$slug]['benefits'],
        'faqs' => $features[$slug]['faqs'],
        'use_cases' => $features[$slug]['use_cases'],
        'highlights' => $features[$slug]['highlights'],
    ]);
});

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

    // checkout
    Route::get('/checkout', [PaymentController::class, 'checkout']);

    // Subscription (HARUS bisa diakses walau belum aktif)
    Route::get('/subscription', [PaymentController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [PaymentController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/settings', [SettingController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
    Route::resource('/admin/categories', AdminCategoryController::class);
    Route::resource('/admin/products', AdminProductController::class);
    Route::resource('/admin/warehouse', AdminWarehouseController::class);
    Route::resource('/admin/customers', AdminCustomerController::class);
    Route::resource('/admin/store', StoreController::class);
    Route::resource('/admin/transactions', AdminTransactionController::class);
    Route::resource('admin/roles',  RoleController::class);

    Route::get('/admin/settings', [AdminSettingController::class, 'index']);
    Route::post('/admin/settings', [AdminSettingController::class, 'update']);
    Route::put('/admin/plans/{id}', [AdminSettingController::class, 'updatePlan']);

    // update gambar produk
    Route::put('/admin/product/update-img/{id}', [AdminProductController::class, 'updateImage']);

    // stock
    Route::post('/admin/stocks', [AdminStockController::class, 'store'])->name('stocks.store');
    Route::put('/admin/stocks/{id}', [AdminStockController::class, 'update'])->name('stocks.update');
    Route::put('/admin/stocks/{id}/reduce', [AdminStockController::class, 'reduce'])->name('stocks.reduce');
    Route::delete('/admin/stocks/{id}', [AdminStockController::class, 'destroy'])->name('stocks.delete');

    Route::get('/admin/categories-by-store/{store}', function ($storeId) {
        return Category::where('store_id', $storeId)->get();
    });

    // kirim email
    Route::post('/admin/customers/{id}/send-email', [CustomerController::class, 'sendEmail']);

    // 
    Route::get('/products/by-store', function () {
        $storeId = request('store_id');

        $products = \App\Models\Product::with('stocks')->where('store_id', $storeId)->get();

        return response()->json($products);
    });

    Route::get('/customers/search', function (Request $request) {
        return Customer::with('user')
            ->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->user->name
                ];
            });
    });

    Route::get('/customers/by-store', [AdminTransactionController::class, 'byStore']);
});

/*
|--------------------------------------------------------------------------
| Owner (BUTUH subscription)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner', 'subscription.active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/customers/search', function (Request $request) {
        return Customer::with('user')
            ->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->limit(5)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->user->name
                ];
            });
    });

    // Resources
    Route::resources([
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
    Route::put('/stocks/{id}/reduce', [StockController::class, 'reduce'])->name('stocks.reduce');
    Route::delete('/stocks/{id}', [StockController::class, 'destroy'])->name('stocks.delete');

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);

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

require __DIR__ . '/auth.php';
