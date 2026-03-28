<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // total order
        $totalOrder = Transaction::count();
        $today = Transaction::whereDate('created_at', Carbon::today())->count();
        $yesterday = Transaction::whereDate('created_at', Carbon::yesterday())->count();
        $percent = 0;
        if ($yesterday > 0) {
            $percent = (($today - $yesterday) / $yesterday) * 100;
        }

        // revenue
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->sum('total');
        $yesterdayRevenue = Transaction::whereDate('created_at', Carbon::yesterday())
            ->sum('total');

        $percentRevenue = 0;

        if ($yesterdayRevenue > 0) {
            $percentRevenue = (($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100;
        }

        // stok ready
        $totalStock = Stock::sum('qty');

        $todayStock = Stock::whereDate('updated_at', Carbon::today())
            ->sum('qty');

        $yesterdayStock = Stock::whereDate('updated_at', Carbon::yesterday())
            ->sum('qty');

        $percentStock = 0;

        if ($yesterdayStock > 0) {
            $percentStock = (($todayStock - $yesterdayStock) / $yesterdayStock) * 100;
        }

        // low stok
        $lowStockCount = Stock::where('qty', '<=', 5)
            ->where('qty', '>', 0)
            ->count();

        $todayLow = Stock::where('qty', '<=', 5)
            ->where('qty', '>', 0)
            ->whereDate('updated_at', Carbon::today())
            ->count();

        $yesterdayLow = Stock::where('qty', '<=', 5)
            ->where('qty', '>', 0)
            ->whereDate('updated_at', Carbon::yesterday())
            ->count();

        $percentLow = 0;

        if ($yesterdayLow > 0) {
            $percentLow = (($todayLow - $yesterdayLow) / $yesterdayLow) * 100;
        }

        return view('dashboard', compact(
            'totalOrder',
            'today',
            'yesterday',
            'percent',
            'todayRevenue',
            'yesterdayRevenue',
            'percentRevenue',
            'totalStock',
            'todayStock',
            'yesterdayStock',
            'percentStock',
            'lowStockCount',
            'percentLow'
        ));
    }
}
