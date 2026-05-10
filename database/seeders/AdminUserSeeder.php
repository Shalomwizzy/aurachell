<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@aurachell.com');
        $password = env('ADMIN_PASSWORD', 'Aurachell@2026!');

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Aurachell Admin',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'phone' => env('ADMIN_PHONE', ''),
            ]
        );
        $admin->assignRole('super_admin');

        $sales = User::firstOrCreate(
            ['email' => 'sales@aurachell.com'],
            [
                'name' => 'Sales Rep',
                'password' => Hash::make('Sales@1234!'),
                'email_verified_at' => now(),
            ]
        );
        $sales->assignRole('sales_rep');
    }
}
