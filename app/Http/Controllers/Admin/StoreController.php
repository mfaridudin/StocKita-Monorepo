<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage store')->only(['index', 'store', 'show', 'update', 'destroy']);
    }

    public function index()
    {
        $stores = Store::all();

        return view('admin.store.index', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_name' => 'required',
            'owner_email'      => 'required|email|unique:users,email',
            'email'      => 'required|email|unique:stores,email',
            'name'       => 'required',
            'phone'      => 'nullable',
            'address'    => 'nullable',
        ], [
            'owner_name.required' => 'Nama pemilik wajib diisi!',
            'owner_email.required'      => 'Email wajib diisi!',
            'owner_email.email'         => 'Format email tidak valid!',
            'owner_email.unique'        => 'Email sudah digunakan!',
            'email.required'      => 'Email wajib diisi!',
            'email.email'         => 'Format email tidak valid!',
            'email.unique'        => 'Email sudah digunakan!',
            'name.required'       => 'Nama toko wajib diisi!',
        ]);

        $owner = User::create([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->password),
        ]);

        $owner->assignRole('owner');

        Store::create([
            'name' => $request->name,
            'email' => $request->email,
            'owner_id' => $owner->id,
            'address' => $request->address,
            'phone' => $request->phone,
            'slug' => $this->generateUniqueSlug($request->name),
        ]);

        return redirect()->back()->with('success', 'Toko berhasil dibuat');
    }

    public function show($id)
    {
        $store = Store::withCount([
            'products',
            'transactions',
            'customers',
            'warehouse'
        ])->with('owner')->findOrFail($id);

        return view('admin.store.show', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        $owner = User::findOrFail($store->owner->id);

        $request->validate([
            'owner_name' => 'required',
            'owner_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($owner->id),
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('stores', 'email')->ignore($store->id),
            ],

            'name'       => 'required',
            'phone'      => 'nullable',
            'address'    => 'nullable',
        ], [
            'owner_name.required' => 'Nama pemilik wajib diisi!',
            'owner_email.required'      => 'Email wajib diisi!',
            'owner_email.email'         => 'Format email tidak valid!',
            'owner_email.unique'        => 'Email sudah digunakan!',
            'email.required'      => 'Email wajib diisi!',
            'email.email'         => 'Format email tidak valid!',
            'email.unique'        => 'Email sudah digunakan!',
            'name.required'       => 'Nama toko wajib diisi!',
        ]);

        $owner->update([
            'name' => $request->owner_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->password),
        ]);

        $store->update([
            'name' => $request->name,
            'email' => $request->email,
            'owner_id' => $owner->id,
            'address' => $request->address,
            'phone' => $request->phone,
            'slug' => $this->generateUniqueSlug($request->name),
        ]);

        return redirect()->back()->with('success', 'Informasi toko berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->back()->with('success', 'Toko berhasil dihapus!');
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = Store::where('slug', 'LIKE', $slug . '%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
