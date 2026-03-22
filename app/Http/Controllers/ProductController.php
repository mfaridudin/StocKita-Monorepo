<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // generate sku
    private function generateSku()
    {
        $date = now()->format('Ymd');

        $lastProduct = Product::whereDate('created_at', now())
            ->latest()
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "PRD-$date-$newNumber";
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::get();

        return view('produk.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();

        return view('produk.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'sku' => $this->generateSku(),
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'created_by' => auth()->id(),
            'warehouse_id' => $request->warehouse_id,
        ]);

        return view('produk.index');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back();
    }
}
