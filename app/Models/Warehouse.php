<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;

#[Guarded(['id'])]
class Warehouse extends Model
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
