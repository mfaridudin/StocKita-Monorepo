<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseStoreRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    // generate code
    private function generateWarehouseCode()
    {
        $prefix = 'GDG-';

        $lastRecord = Warehouse::orderBy('code', 'desc')->first();
        if (! $lastRecord) {
            return $prefix.'0001';
        }
        $lastNumber = substr($lastRecord->code, strlen($prefix));
        $nextNumber = (int) $lastNumber + 1;
        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $prefix.$formattedNumber;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::get();

        return view('warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseStoreRequest $request)
    {

        $product = Warehouse::create([
            'name' => $request->name,
            'code' => $this->generateWarehouseCode(),
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return view('warehouse.index');
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
        //
    }
}
