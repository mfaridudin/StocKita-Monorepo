<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productQuery = Product::with('store')
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhereHas('category', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('store', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('store.owner', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                });
            })
            ->when($request->store, function ($q) use ($request) {
                $q->where('store_id', $request->store);
            });

        $products = $productQuery->latest()->get();
        $stores = Store::all();
        $categories = Category::all();

        return view('admin.produk.index', compact('products', 'categories', 'stores'));
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
    public function store(ProductStoreRequest $request)
    {
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
            'store_id' => $request->store_id,
            'warehouse_id' => $request->warehouse_id,
        ]);

        return redirect()->back()->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('store_id', $product->store->id)->get();

        return view('.admin.produk.show', compact('product', 'categories'));
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

    // update image
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

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
}
