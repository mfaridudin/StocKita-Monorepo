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
            'view low stock indicator',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',
            'upload product images',

            // Transactions
            'view transactions',
            'create transactions',
            'manage payments',
            'print receipts',

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

            // Profile
            'edit own profile',
            'edit customer profile',

            // Settings
            'manage store settings',
            'manage subscription',

            // Inventory
            'manage warehouse',
            'manage stock movement',

            // Buyer
            'view own orders',
            'view order history',

            // Admin
            'manage users',
            'manage roles',
            'manage permissions',

            // Legal
            'view privacy policy',
            'view terms',
            'view dmca',

            // Cookie
            'accept cookies',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }
}
