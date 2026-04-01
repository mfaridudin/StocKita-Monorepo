<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $plan = $subscription ? Plan::find($subscription->plan_id) : null;

        return view('settings.index', compact('subscription', 'plan'));
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
        //
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
    public function update(Request $request)
    {
        // STORE
        if ($request->has('store')) {
            foreach ($request->store as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => 'store.'.$key], // ✅ FIX
                    ['value' => $value]
                );
            }
        }

        // APP (kamu belum handle ini ❗)
        if ($request->has('app')) {
            foreach ($request->app as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => 'app.'.$key],
                    ['value' => $value]
                );
            }
        }

        // email
        if ($request->has('email')) {
            foreach ($request->email as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => 'email.'.$key],
                    ['value' => $value]
                );
            }
        }

        cache()->forget('settings');

        return back()->with('success', 'Setting berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
