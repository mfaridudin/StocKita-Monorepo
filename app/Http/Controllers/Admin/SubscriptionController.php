<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Store;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
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
