<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SettingsSeeder;
use Database\Seeders\ShippingZoneSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function index(): View
    {
        return view('install.index');
    }

    public function preflight(): JsonResponse
    {
        $checks = [
            ['label' => 'PHP 8.1+',                'pass' => version_compare(PHP_VERSION, '8.1.0', '>='), 'detail' => PHP_VERSION],
            ['label' => 'PDO MySQL extension',     'pass' => extension_loaded('pdo_mysql')],
            ['label' => 'Mbstring extension',      'pass' => extension_loaded('mbstring')],
            ['label' => 'OpenSSL extension',       'pass' => extension_loaded('openssl')],
            ['label' => 'BCMath extension',        'pass' => extension_loaded('bcmath')],
            ['label' => 'GD or Imagick extension', 'pass' => extension_loaded('gd') || extension_loaded('imagick')],
            ['label' => 'Fileinfo extension',      'pass' => extension_loaded('fileinfo')],
            ['label' => 'XML extension',           'pass' => extension_loaded('xml')],
            ['label' => 'storage/ writable',       'pass' => is_writable(storage_path())],
            ['label' => 'bootstrap/cache/ writable', 'pass' => is_writable(base_path('bootstrap/cache'))],
            ['label' => '.env file exists',        'pass' => file_exists(base_path('.env'))],
        ];

        $allPass = collect($checks)->every(fn ($c) => $c['pass']);

        return response()->json(['checks' => $checks, 'ready' => $allPass]);
    }

    public function testDatabase(Request $request): JsonResponse
    {
        $request->validate([
            'db_host' => 'required|string|max:100',
            'db_port' => 'required|integer|min:1|max:65535',
            'db_database' => 'required|string|max:100',
            'db_username' => 'required|string|max:100',
            'db_password' => 'nullable|string|max:200',
        ]);

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $request->db_host,
                $request->db_port,
                $request->db_database
            );

            new \PDO($dsn, $request->db_username, $request->db_password ?? '', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_TIMEOUT => 5,
            ]);

            return response()->json(['success' => true, 'message' => 'Connection successful!']);
        } catch (\PDOException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function run(Request $request): JsonResponse
    {
        $request->validate([
            'db_host' => 'required|string|max:100',
            'db_port' => 'required|integer|min:1|max:65535',
            'db_database' => 'required|string|max:100',
            'db_username' => 'required|string|max:100',
            'db_password' => 'nullable|string|max:200',
            'admin_name' => 'required|string|max:100',
            'admin_email' => 'required|email|max:150',
            'admin_password' => 'required|string|min:8|confirmed',
            'store_name' => 'required|string|max:100',
            'app_url' => 'required|url|max:200',
            'store_email' => 'required|email|max:150',
            'currency' => 'required|string|max:10',
        ]);

        // 1 — Write credentials to .env so seeders and config pick them up
        $this->writeEnv([
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password ?? '',
            'APP_URL' => rtrim($request->app_url, '/'),
            'ADMIN_EMAIL' => $request->admin_email,
            'ADMIN_PASSWORD' => $request->admin_password,
        ]);

        // 2 — Re-connect with new credentials at runtime
        config([
            'database.connections.mysql.host' => $request->db_host,
            'database.connections.mysql.port' => $request->db_port,
            'database.connections.mysql.database' => $request->db_database,
            'database.connections.mysql.username' => $request->db_username,
            'database.connections.mysql.password' => $request->db_password ?? '',
        ]);

        DB::purge('mysql');

        try {
            DB::reconnect('mysql');
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'step' => 'database', 'message' => 'Could not connect to the database: '.$e->getMessage()]);
        }

        // 3 — Generate APP_KEY if missing
        if (! config('app.key')) {
            try {
                Artisan::call('key:generate', ['--force' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'step' => 'key', 'message' => 'Could not generate app key: '.$e->getMessage()]);
            }
        }

        // 4 — Run migrations
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'step' => 'migrate', 'message' => 'Migration failed: '.$e->getMessage()]);
        }

        // 5 — Seed: roles, admin user, default settings, shipping zones
        $seeders = [
            RoleSeeder::class,
            AdminUserSeeder::class,
            SettingsSeeder::class,
            ShippingZoneSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            try {
                Artisan::call('db:seed', ['--class' => $seeder, '--force' => true]);
            } catch (\Exception $e) {
                // Non-fatal — log and continue
            }
        }

        // 6 — Ensure admin user has exact installer credentials and super_admin role
        try {
            $admin = User::updateOrCreate(
                ['email' => $request->admin_email],
                [
                    'name' => $request->admin_name,
                    'password' => Hash::make($request->admin_password),
                    'email_verified_at' => now(),
                    'referral_code' => strtoupper(Str::random(8)),
                ]
            );
            $admin->syncRoles(['super_admin']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'step' => 'admin', 'message' => 'Could not create admin account: '.$e->getMessage()]);
        }

        // 7 — Save store settings from installer form
        try {
            Setting::set('store_name', $request->store_name);
            Setting::set('store_email', $request->store_email);
            Setting::set('currency', $request->currency);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'step' => 'settings', 'message' => 'Could not save settings: '.$e->getMessage()]);
        }

        // 8 — Clear caches and write lock file
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
        } catch (\Exception) {
        }

        file_put_contents(storage_path('installed.lock'), now()->toDateTimeString());

        return response()->json(['success' => true]);
    }

    private function writeEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath) && file_exists(base_path('.env.example'))) {
            copy(base_path('.env.example'), $envPath);
        }

        $content = file_exists($envPath) ? file_get_contents($envPath) : '';

        foreach ($values as $key => $value) {
            $value = (string) $value;
            $needsQuotes = $value === '' || str_contains($value, ' ') || str_contains($value, '#');
            $escaped = $needsQuotes ? '"'.str_replace('"', '\\"', $value).'"' : $value;

            if (preg_match('/^'.preg_quote($key, '/').'=/m', $content)) {
                $content = preg_replace('/^'.preg_quote($key, '/').'=.*/m', $key.'='.$escaped, $content);
            } else {
                $content .= "\n".$key.'='.$escaped;
            }
        }

        file_put_contents($envPath, $content);
    }
}
