<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password',])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('current_period_end', '>', now());
    }

    public function hasActiveSubscription()
    {
        if (! $this->hasRole('owner')) {
            return true;
        }

        return $this->activeSubscription()->exists();
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            Store::class,
            'owner_id',
            'store_id',
            'id',
            'id'
        );
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function midtransTransaction()
    {
        return $this->hasMany(MidtransTransaction::class);
    }

    // public function store()
    // {
    //     return $this->belongsTo(Store::class);
    // }

    public function store()
    {
        return $this->hasOne(Store::class, 'owner_id');
    }

    public function transactions()
    {
        return $this->store ? $this->store->transactions() : collect();
    }

    // batas subsribe
    public function canCreateProduct()
    {
        $subscription = $this->subscription;

        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        return $this->products()->where('is_active', true)->count() < $subscription->plan->max_products;
    }

    public function canCreateTransaction()
    {
        $subscription = $this->subscription;

        // Cek ada subscription aktif
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        // Hitung transaksi yang sudah dibuat
        $transactionCount = $this->transactions()->where('is_active', true)->count();

        // Bandingkan dengan limit plan
        return $transactionCount < $subscription->plan->max_orders;
    }

    public function canCreateWarehouse()
    {
        $subscription = $this->subscription;

        // Cek ada subscription aktif
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        // Hitung warehouse yang sudah dibuat
        $warehouseCount = $this->store->warehouse()->where('is_active', true)->count();

        // Bandingkan dengan limit plan
        return $warehouseCount < $subscription->plan->max_warehouses;
    }

    public function canCreateCategories()
    {
        $subscription = $this->subscription;

        // Cek ada subscription aktif
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        // Hitung kategori yang sudah dibuat
        $categoriesCount = $this->store->categories()->where('is_active', true)->count();

        // Bandingkan dengan limit plan
        return $categoriesCount < $subscription->plan->max_categories;
    }

    public function canCreateCustomers()
    {
        $subscription = $this->subscription;

        // Cek ada subscription aktif
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        // Hitung kategori yang sudah dibuat
        $customersCount = $this->store->customers()->where('is_active', true)->count();

        // Bandingkan dengan limit plan
        return $customersCount < $subscription->plan->max_customers;
    }

    // sync syncProductsLimit
    private function syncLimit($query, $limit)
    {
        $items = $query->orderBy('created_at', 'asc')->get();

        foreach ($items as $index => $item) {
            $item->is_active = $index < $limit;
            $item->save();
        }
    }

    public function syncAllLimits()
    {
        if (! $this->store) {
            return;
        }

        $subscription = $this->subscription()->with('plan')->first();

        if (! $subscription || $subscription->status !== 'active') {
            return;
        }

        $plan = $subscription->plan;

        // produk
        $this->syncLimit(
            $this->products(),
            $plan->max_products
        );

        // kategori
        $this->syncLimit(
            $this->store->categories(),
            $plan->max_categories
        );

        // gudang
        $this->syncLimit(
            $this->store->warehouse(),
            $plan->max_warehouses
        );

        // customer
        $this->syncLimit(
            $this->store->customers(),
            $plan->max_customers
        );

        // transaksi
        $this->syncLimit(
            $this->transactions(),
            $plan->max_orders
        );
    }

    public function deactivateAllData()
    {
        if (! $this->store) return;

        $this->products()->update(['is_active' => false]);

        $this->store->categories()->update(['is_active' => false]);

        $this->store->warehouse()->update(['is_active' => false]);

        $this->store->customers()->update(['is_active' => false]);

        $this->transactions()->update(['is_active' => false]);
    }
}
