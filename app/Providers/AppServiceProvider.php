<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $appName = Setting::where('key', 'app.name')->value('value');

            if ($appName) {
                Config::set('app.name', $appName);
            }
        }

        View::composer('*', function ($view) {
            $user = Auth::user();

            $prefix = '';

            if ($user && $user->hasRole('admin')) {
                $prefix = '/admin';
            }

            $notifications = collect();

            $stockQuery = Stock::with(['product', 'warehouse']);

            if ($user && $user->hasRole('owner')) {
                $stockQuery->whereHas('warehouse', function ($q) use ($user) {
                    $q->where('store_id', $user->store->id);
                });
            }

            // stok habis
            $outStock = (clone $stockQuery)
                ->where('qty', 0)
                ->get();

            foreach ($outStock as $stock) {
                $notifications->push([
                    'type' => 'danger',
                    'title' => 'Stok Habis',
                    'message' => $stock->product->name . ' - ' . $stock->warehouse->name,
                    'url' => $prefix . '/warehouse/' . $stock->warehouse_id,
                ]);
            }

            // stok menipis
            $lowStock = (clone $stockQuery)
                ->where('qty', '>', 0)
                ->where('qty', '<=', 5)
                ->get();

            foreach ($lowStock as $stock) {
                $notifications->push([
                    'type' => 'warning',
                    'title' => 'Stok Menipis',
                    'message' => $stock->product->name . ' sisa ' . $stock->qty,
                    'url' =>  $prefix . '/warehouse/' . $stock->warehouse_id,
                ]);
            }

            $newOwners = User::role('owner')
                ->whereDate('created_at', today())
                ->get();

            if ($user && $user->hasRole('admin') && $newOwners->count() > 0) {
                foreach ($newOwners as $owner) {
                    $notifications->push([
                        'type' => 'success',
                        'title' => 'Owner Baru',
                        'message' => $owner->name . ' baru daftar',
                        'url' => $prefix . '/store/' . ($owner->store->id ?? ''),
                    ]);
                }
            }

            $view->with(compact('notifications'));
        });
    }
}
