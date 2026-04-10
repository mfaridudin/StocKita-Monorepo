<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Imagick;

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
        $products = Product::where('store_id', Auth::user()->store->id)->get();
        $categories = Category::where('store_id', Auth::user()->store->id)->get();

        return view('produk.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        if (! auth()->user()->canCreateProduct()) {
            return back()->with('error', 'Limit produk habis');
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'prd-' . time() . '.webp';

            $path = storage_path('app/public/products/' . $filename);

            $image = new Imagick($file->getRealPath());

            $size = min($image->getImageWidth(), $image->getImageHeight());
            $image->cropImage(
                $size,
                $size,
                ($image->getImageWidth() - $size) / 2,
                ($image->getImageHeight() - $size) / 2
            );

            $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

            $image->setImageFormat('webp');
            $image->setImageCompressionQuality(30);
            $image->setOption('webp:method', '6');

            $image->stripImage();

            $image->writeImage($path);

            $image->clear();
            $image->destroy();

            $imagePath = 'products/' . $filename;
        }

        Product::create([
            'name' => $request->name,
            'sku' => $this->generateSku(),
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'created_by' => auth()->id(),
            'store_id' => auth()->user()->store->id,
            'warehouse_id' => $request->warehouse_id,
        ]);

        return redirect()->back()->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::where('store_id', Auth::user()->store->id)->findOrFail($id);
        $categories = Category::where('store_id', Auth::user()->store->id)->get();

        return view('produk.show', compact('product', 'categories'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',

        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.max' => 'Nama produk maksimal 255 karakter.',

            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',

            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    // update image
    public function updateImage(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $file = $request->file('image');
            $filename = 'prd-' . time() . '.webp';
            $directory = storage_path('app/public/products');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $path = $directory . '/' . $filename;

            try {
                $image = new Imagick($file->getRealPath());

                $size = min($image->getImageWidth(), $image->getImageHeight());
                $image->cropImage(
                    $size,
                    $size,
                    ($image->getImageWidth() - $size) / 2,
                    ($image->getImageHeight() - $size) / 2
                );

                $image->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);

                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(30);
                $image->setOption('webp:method', '6');

                $image->stripImage();

                $image->writeImage($path);

                $image->clear();
                $image->destroy();

                $imagePath = 'products/' . $filename;

                $product->update([
                    'image' => $imagePath,
                ]);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Gambar produk berhasil diperbarui!');
    }
}
