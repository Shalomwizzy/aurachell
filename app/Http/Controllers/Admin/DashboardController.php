<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs — paid orders only for revenue / order counts
        $todayRevenue = (float) Order::where('payment_status', 'paid')
            ->whereDate(DB::raw('COALESCE(paid_at, created_at)'), today())
            ->sum('total');

        $todayOrders = Order::where('payment_status', 'paid')
            ->whereDate(DB::raw('COALESCE(paid_at, created_at)'), today())
            ->count();

        $newCustomers = User::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Month / lifetime totals — paid only
        $monthRevenue = (float) Order::where('payment_status', 'paid')
            ->whereMonth(DB::raw('COALESCE(paid_at, created_at)'), now()->month)
            ->whereYear(DB::raw('COALESCE(paid_at, created_at)'), now()->year)
            ->sum('total');

        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $totalCustomers = User::count();
        $totalProducts = Product::count();

        // Recent 8 orders (any status)
        $recentOrders = Order::with('items')->latest()->limit(8)->get();

        // Inventory health
        $outOfStock = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $lowStock = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<=', 3)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        // Top products by units sold (last 30 days, paid orders only)
        // Use subquery to avoid MySQL ONLY_FULL_GROUP_BY issues
        $topSales = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as units_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->pluck('units_sold', 'product_id');

        $topProducts = Product::whereIn('id', $topSales->keys())->get()
            ->each(fn ($p) => $p->units_sold = (int) ($topSales[$p->id] ?? 0))
            ->sortByDesc('units_sold')
            ->values();

        // Sales chart — last 14 days (paid orders)
        $chartDays = collect(range(13, 0))->map(function ($d) {
            $date = now()->subDays($d);

            return [
                'label' => $date->format('M d'),
                'revenue' => (float) Order::where('payment_status', 'paid')
                    ->whereDate(DB::raw('COALESCE(paid_at, created_at)'), $date)
                    ->sum('total'),
                'orders' => Order::where('payment_status', 'paid')
                    ->whereDate(DB::raw('COALESCE(paid_at, created_at)'), $date)
                    ->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'todayRevenue', 'todayOrders', 'newCustomers', 'pendingOrders',
            'monthRevenue', 'totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'recentOrders', 'outOfStock', 'lowStock', 'topProducts', 'chartDays'
        ));
    }
}
