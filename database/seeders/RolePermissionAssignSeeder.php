<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionAssignSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $buyer = Role::firstOrCreate(['name' => 'buyer']);

        $admin->syncPermissions(Permission::all());

        $owner->syncPermissions([
            'view dashboard stats',
            'view low stock indicator',

            'view products',
            'create products',
            'edit products',
            'delete products',
            'upload product images',

            'view transactions',
            'create transactions',
            'manage payments',
            'print receipts',

            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            'send customer email',

            'edit own profile',
            'edit customer profile',

            'manage store settings',
            'manage subscription',

            'manage warehouse',
            'manage stock movement',
        ]);

        $buyer->syncPermissions([
            'view own orders',
            'view order history',
            'edit own profile',
        ]);
    }
}
