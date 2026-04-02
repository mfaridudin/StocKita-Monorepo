<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ], [
            'product_id.required' => 'Produk wajib diisi.',
            'product_id.exists' => 'Produk tidak valid.',

            'warehouse_id.required' => 'Gudang wajib diisi.',
            'warehouse_id.exists' => 'Gudang tidak valid.',

            'qty.required' => 'Jumlah wajib diisi.',
            'qty.integer' => 'Jumlah harus berupa angka.',
            'qty.min' => 'Jumlah minimal 1.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'createStock')
                ->with('open_modal', 'create-stock')
                ->withInput();
        }

        $stock = Stock::firstOrCreate(
            [
                'product_id' => $request->product_id,
                'warehouse_id' => $request->warehouse_id,
            ],
            [
                'qty' => 0,
            ]
        );

        $stock->increment('qty', $request->qty);

        return redirect()->back()->with('success', 'Barang berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $validator = Validator::make($request->all(), [
            'qty' => ['required', 'integer', 'min:1'],
        ], [
            'qty.required' => 'Jumlah wajib diisi.',
            'qty.integer' => 'Jumlah harus berupa angka.',
            'qty.min' => 'Jumlah minimal 1.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'addStock')
                ->with('open_modal', 'add-stock')
                ->with('stock_id', $id)
                ->withInput();
        }

        $stock = Stock::findOrFail($id);

        $qty = $stock->qty;
        $addQty = $request->qty;
        $updateQty = $qty + $addQty;

        $stock->update([
            'qty' => $updateQty,
        ]);

        return redirect()->back()->with('success', 'Stok barang berhasil ditambah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
