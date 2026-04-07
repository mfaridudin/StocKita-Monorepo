<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

            $notifications = collect();

            // stok habis
            $outStock = Stock::with(['product', 'warehouse'])
                ->where('qty', 0)
                ->get();

            foreach ($outStock as $stock) {
                $notifications->push([
                    'type' => 'danger',
                    'title' => 'Stok Habis',
                    'message' => $stock->product->name.' - '.$stock->warehouse->name,
                    'url' => '/warehouse/'.$stock->warehouse_id,
                ]);
            }

            // stok menipis
            $lowStock = Stock::with(['product', 'warehouse'])
                ->where('qty', '>', 0)
                ->where('qty', '<=', 5) // batas bisa lo ubah
                ->get();

            foreach ($lowStock as $stock) {
                $notifications->push([
                    'type' => 'warning',
                    'title' => 'Stok Menipis',
                    'message' => $stock->product->name.' sisa '.$stock->qty,
                    'url' => '/warehouse/'.$stock->warehouse_id,
                ]);
            }

            // $notifications->push([
            //     'type' => 'info',
            //     'title' => 'Transaksi Baru',
            //     'message' => 'Ada transaksi baru hari ini',
            //     'url' => '/transactions'
            // ]);

            $view->with(compact('notifications'));
        });
    }
}
