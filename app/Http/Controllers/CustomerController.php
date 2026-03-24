<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::filter([
            'search' => $request->search,
            'type' => $request->type,
            'status' => $request->status,
        ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Customer::count(),
            'exclusive' => Customer::exclusive()->count(),
            'active' => Customer::active()->count(),
            'total_spent' => Customer::sum('total_spent'),
        ];

        return view('pelanggan.index', compact('customers', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        if (! str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62'.substr($phone, 1);
            }
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'status' => $request->status,
            'phone' => $phone,
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);
        $totalOrders = 10;
        $totalSpent = 10;

        return view('pelanggan.show', compact('customer', 'totalOrders', 'totalSpent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        if (! str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62'.substr($phone, 1);
            }
        }

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'status' => $request->status,
            'phone' => $phone,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->back();
    }
}
