<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage stock movement')->only(['store', 'update', 'reduce', 'destroy']);
    }

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

    // tambah
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

    // kuarngi
    public function reduce(Request $request, string $id)
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

        $stock->update([
            'qty' => $request->qty,
        ]);

        return redirect()->back()->with('success', 'Stok barang berhasil dikurangi!');
    }

    // hapus
    public function destroy(string $id)
    {
        $warehouse = Stock::findOrFail($id);

        $warehouse->delete();

        return redirect()->back()->with('success', 'Stok barang berhasil dihapus!');
    }
}
