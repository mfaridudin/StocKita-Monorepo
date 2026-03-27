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
            $appName = Setting::where('key', 'app_name')->value('value');

            if ($appName) {
                Config::set('app.name', $appName);
            }
        }

        View::composer('*', function ($view) {

            $stocks = Stock::with(['product', 'warehouse'])->get();

            $outStock = $stocks->where('qty', 0);

            $view->with(compact('outStock'));
        });
    }
}
