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

        return $this->products()->count() < $subscription->plan->max_products;
    }

    public function canCreateTransaction()
    {
        $subscription = $this->subscription;

        // Cek ada subscription aktif
        if (! $subscription || $subscription->status !== 'active') {
            return false;
        }

        // Hitung transaksi yang sudah dibuat
        $transactionCount = $this->transactions()->count();

        // Bandingkan dengan limit plan
        return $transactionCount < $subscription->plan->max_orders;
    }
}
