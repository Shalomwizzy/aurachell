<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete', 'orders.update_status',
            'categories.manage',
            'coupons.manage',
            'users.view', 'users.edit', 'users.delete',
            'roles.manage',
            'staff.invite', 'staff.manage',
            'payments.view',
            'reports.view',
            'settings.manage',
            'reviews.moderate',
            'messages.respond',
            'chat.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'super_admin' => $permissions,
            'admin' => array_diff($permissions, ['roles.manage']),
            'sales_rep' => ['orders.view', 'orders.update_status', 'users.view', 'products.view', 'messages.respond', 'chat.view'],
            'inventory_manager' => ['products.view', 'products.create', 'products.edit', 'categories.manage'],
            'support' => ['orders.view', 'messages.respond', 'chat.view'],
            'customer' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
