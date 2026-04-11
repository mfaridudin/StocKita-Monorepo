<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Mail\SendCustomerEmail;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view customers')->only(['index', 'show']);

        $this->middleware('permission:create customers')->only(['create', 'store']);

        $this->middleware('permission:edit customers')->only(['edit', 'update']);

        $this->middleware('permission:delete customers')->only(['destroy']);

        $this->middleware('permission:send customer email')->only(['sendEmail']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::where('store_id', Auth::user()->store->id)->filter([
            'search' => $request->search,
            'type' => $request->type,
            'status' => $request->status,
        ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Customer::where('store_id', Auth::user()->store->id)->count(),
            'exclusive' => Customer::where('store_id', Auth::user()->store->id)->exclusive()->count(),
            'active' => Customer::where('store_id', Auth::user()->store->id)->active()->count(),
            'total_spent' => Transaction::where('store_id', Auth::user()->store->id)->where('status', 'paid')
                ->sum('total'),
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
        if (! auth()->user()->canCreateCustomers()) {
            return back()->with('error', 'Limit pelanggan habis');
        }

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        if (! str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'store_id' => Auth::user()->store->id,
        ]);

        $user->assignRole('buyer');

        $customer = Customer::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'status' => $request->status,
            'phone' => $phone,
            'store_id' => Auth::user()->store->id,
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);
        $totalOrders = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->count();
        $totalSpent = Transaction::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->sum('total');

        $lastOrder = optional(Transaction::where('customer_id', $customer->id)
            ->latest()
            ->first())->created_at;
        $orders = Transaction::where('customer_id', $customer->id)->get();

        // dd($orders);

        return view('pelanggan.show', compact('customer', 'totalOrders', 'totalSpent', 'lastOrder', 'orders'));
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
    public function update(CustomerStoreRequest $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user->id);

        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        if (! str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            }
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $customer->update([
            'type' => $request->type,
            'status' => $request->status,
            'phone' => $phone,
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $user = User::findOrFail($customer->user->id);
        $user->delete();

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus!');
    }

    // kirim email
    public function sendEmail($id)
    {
        $customer = Customer::findOrFail($id);

        Mail::to($customer->user->email)
            ->send(new SendCustomerEmail($customer));

        return back()->with('success', 'Email berhasil dikirim!');
    }
}
