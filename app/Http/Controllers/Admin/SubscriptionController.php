<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage subscription')->only(['index', 'create', 'store', 'toggle', 'destroy']);
    }

    public function index(Request $request)
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })->orWhereHas('user', function ($q) use ($search) {
                            $q->where('email', 'like', "%$search%");
                        });
                });
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->interval, function ($q) use ($request) {
                $q->where('interval', $request->interval);
            })
            ->when($request->type, function ($q) use ($request) {
                $q->where('plan_id', $request->type);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $plans = Plan::all();
        $stores = Store::all();

        return view('admin.subscription.index', compact('subscriptions', 'stores', 'plans'));
    }

    public function create()
    {
        $plans = Plan::all();
        $owners = User::role('owner')
            ->whereDoesntHave('subscription')
            ->get();


        return view('admin.subscription.create', compact('owners', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'interval' => 'required|in:monthly,yearly',
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = Plan::findOrFail($request->plan_id);

        $user->subscription()->create([
            'plan_id' => $plan->id,
            'interval' => $request->interval,
            'status' => 'active',
            'started_at' => now(),
            'current_period_end' => $request->interval === 'yearly'
                ? now()->addYear()
                : now()->addMonth(),
        ]);
        $user = User::find($request->user_id);
        $user->syncAllLimits();

        return redirect('/admin/subscriptions')
            ->with('success', 'Langganan berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $user = $subscription->user->id;

        $oldPlan = $subscription->plan;
        $newPlan = Plan::find($request->plan_id);

        $isUpgrade = $newPlan->price > $oldPlan->price;
        $isDowngrade = $newPlan->price < $oldPlan->price;

        $subscription->update([
            'plan_id' => $request->plan_id,
            'interval' => $request->interval,
            'started_at' => now(),
            'current_period_end' => $request->interval === 'yearly'
                ? now()->addYear()
                : now()->addMonth(),

        ]);

        $user = User::find($user);
        $user->syncAllLimits();

        if ($isUpgrade) {
            $message = 'Berhasil upgrade paket!';
        } elseif ($isDowngrade) {
            $message = 'Berhasil downgrade paket!';
        } else {
            $message = 'Langganan berhasil diperbarui!';
        }

        return back()->with('success', $message);
    }

    public function toggle($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->status === 'active') {
            $subscription->update([
                'status' => 'cancelled'
            ]);
        } else {
            $subscription->update([
                'status' => 'active'
            ]);
        }

        return back()->with('success', 'Status langganan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return redirect()->back()->with('success', 'Data langganan berhasil dihapus!');
    }
}
