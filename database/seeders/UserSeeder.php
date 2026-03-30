<?php

namespace Database\Seeders;

use App\Models\Customer;
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
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@mail.com',
                'role' => 'owner',
            ],
            [
                'name' => 'Buyer',
                'email' => 'buyer@mail.com',
                'role' => 'buyer',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );

            $user->syncRoles([$data['role']]);

            if ($data['role'] === 'buyer') {
                Customer::firstOrCreate([
                    'user_id' => $user->id,
                    'phone' => '+13059565677',
                ]);
            }
        }
    }
}
