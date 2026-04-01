<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::where('slug', 'stoc-kita')->first();
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'store_id' => $store->id,
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@gmail.com',
                'role' => 'owner',
                'store_id' => $store->id,
            ],
            [
                'name' => 'Buyer',
                'email' => 'buyer@gmail.com',
                'role' => 'buyer',
                'store_id' => $store->id,
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'store_id' => $data['store_id'],
                ]
            );

            $user->syncRoles([$data['role']]);

            if ($data['role'] === 'buyer') {
                Customer::firstOrCreate([
                    'user_id' => $user->id,
                    'phone' => '+13059565677',
                    'store_id' => $store->id,
                ]);
            }
        }
    }
}
