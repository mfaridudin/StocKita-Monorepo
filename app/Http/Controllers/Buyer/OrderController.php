<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('items.product')
            ->where('customer_id', Auth::id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [
                $request->start,
                $request->end,
            ]);
        }

        $orders = $query->latest()->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Transaction::with(['items.product'])
            ->where('customer_id', auth()->id()) // 🔒 penting
            ->findOrFail($id);

        return view('buyer.orders.show', compact('order'));
    }
}
