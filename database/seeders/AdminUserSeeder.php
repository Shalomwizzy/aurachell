<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL', 'admin@aurachell.com');
        $password = env('ADMIN_PASSWORD', 'Aurachell@2026!');

        // Find existing super_admin regardless of current email, then update
        $admin = User::role('super_admin')->first();
        if ($admin) {
            $admin->update([
                'email'             => $email,
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
            ]);
        } else {
            $admin = User::create([
                'name'              => 'Aurachell Admin',
                'email'             => $email,
                'password'          => Hash::make($password),
                'email_verified_at' => now(),
                'phone'             => env('ADMIN_PHONE', ''),
            ]);
            $admin->assignRole('super_admin');
        }

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
