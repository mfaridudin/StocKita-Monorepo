<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $owner = User::role('owner')->where('store_id', $user->store_id)->first();
        $store = $user->store;

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $plan = $subscription ? Plan::find($subscription->plan_id) : null;

        return view('settings.index', compact('subscription', 'plan', 'owner', 'store'));
    }

    public function update(Request $request)
    {
        // STORE
        // if ($request->has('store')) {
        //         Store::updateOrCreate(
        //             ['name' => $request->

        //         );
        // }

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

    public function storeOwner(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => ['required',  Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ],
        ]);

        DB::beginTransaction();

        try {
            $oldOwner = User::role('owner')->first();
            if ($oldOwner) {
                $oldOwner->removeRole('owner');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'store_id' => $user->store_id,
            ]);

            $user->assignRole('owner');

            DB::commit();

            return back()->with('success', 'Owner berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan owner');
        }
    }

    public function updateOwner(Request $request, $id)
    {

        $owner = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$id",
            'password' => ['nullable',  Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $owner->update($data);

        return back()->with('success', 'Owner berhasil diupdate');
    }

    public function updateStore(Request $request, $id)
    {
        $data = $request->store;

        Store::updateOrCreate(
            ['id' => $id],

            [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]
        );

        return redirect()->back()->with('success', 'Informasi toko berhasil diperbarui!');
    }
}
