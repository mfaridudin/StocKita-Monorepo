<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

#[Guarded(['id'])]
class Customer extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query)
    {
        return $query
            ->when(request('search'), function ($q) {
                $search = request('search');

                return $q->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            })
            ->when(request('type'), function ($q) {
                return $q->where('type', request('type'));
            })
            ->when(request('status'), function ($q) {
                return $q->where('status', request('status'));
            });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExclusive($query)
    {
        return $query->where('type', 'exclusive');
    }

    public function getFormattedPhoneAttribute()
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse($this->phone, null);

            return $phoneUtil->format($number, PhoneNumberFormat::INTERNATIONAL);
        } catch (\Exception $e) {
            return $this->phone;
        }
    }
}
