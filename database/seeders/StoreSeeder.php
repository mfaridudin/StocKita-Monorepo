<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::firstOrCreate([
            'name' => 'Stockita',
            'slug' => 'stoc-kita',
            'email' => 'StocKita@email.com',
            'phone' => '08123456789',
            'address' => 'Jl. Pandanaran No. 123, Semarang, Jawa Tengah, 50241, Indonesia',
        ]);
    }
}
