<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        // statistik chart
        $range = $request->get('range', 7);

    $data = collect();

    for ($i = $range - 1; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);

        $revenue = Transaction::whereDate('created_at', $date)->sum('total');
        $orders = Transaction::whereDate('created_at', $date)->count();

        $data->push([
            'date' => $date->format('d M'),
            'revenue' => $revenue,
            'orders' => $orders,
        ]);
    }

    $chartLabels = $data->pluck('date');
    $chartRevenue = $data->pluck('revenue');
    $chartOrders = $data->pluck('orders');


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
            'percentLow',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'range'
        ));
    }
}
