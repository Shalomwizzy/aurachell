<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Auto-record activity for key models ─────────────────────────────
        // Log only when an authenticated admin/user is making the change.
        $models = [Product::class, Category::class, Coupon::class, Order::class, User::class];

        foreach ($models as $modelClass) {
            $modelClass::created(fn (Model $m) => $this->logActivity('created', $m));
            $modelClass::updated(fn (Model $m) => $this->logActivity('updated', $m, $m->getChanges()));
            $modelClass::deleted(fn (Model $m) => $this->logActivity('deleted', $m));
        }
    }

    private function logActivity(string $action, Model $model, array $changes = []): void
    {
        // Skip if no authenticated user (e.g. guest checkout placing an order).
        if (! auth()->check()) {
            return;
        }

        // Skip the ActivityLog model itself to avoid recursion.
        if ($model instanceof ActivityLog) {
            return;
        }

        // Strip noisy/sensitive fields from the change diff.
        unset($changes['updated_at'], $changes['password'], $changes['remember_token']);

        try {
            ActivityLog::record($action.' '.class_basename($model), $model, $changes);
        } catch (\Throwable $e) {
            // Never let logging break the request.
            Log::warning('ActivityLog failed: '.$e->getMessage());
        }
    }
}
