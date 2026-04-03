<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = Category::where('slug', 'LIKE', $slug.'%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('store_id', Auth::user()->store->id)->latest()->get();

        return view('category.index', compact('categories'));
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
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Nama kategori wajib diisi!',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
            'store_id' => Auth::user()->store->id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
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
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required'],
        ], [
            'name.required' => 'Nama kategori wajib diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'editCategory')
                ->with('open_modal', 'edit-category')
                ->with('category_id', $id)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'slug' => $this->generateUniqueSlug($request->name),
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk!');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
