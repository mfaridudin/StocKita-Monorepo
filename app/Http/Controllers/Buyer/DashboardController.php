<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalOrders = Transaction::where('customer_id', $userId)->count();

        $totalSpent = Transaction::where('customer_id', $userId)
            ->sum('total');

        $lastOrder = Transaction::where('customer_id', $userId)
            ->latest()
            ->first();

        $recentOrders = Transaction::where('customer_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('buyer.dashboard', compact(
            'totalOrders',
            'totalSpent',
            'lastOrder',
            'recentOrders'
        ));
    }
}
