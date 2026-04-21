<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [

            // Dashboard
            'view dashboard stats',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',
            'upload product images',

            // Transactions
            'view transactions',
            'create transactions',
            'delete transactions',

            // Customers
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'send customer email',

            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Settings
            'manage store',
            'manage store settings',
            'manage subscription',
            'edit own profile',

            // Inventory
            'manage warehouse',
            'manage stock movement',

            // Buyer
            'view own orders',
            'view order history',

            // Admin
            'manage roles & permissions',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}
