<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductPerformanceController extends Controller
{
    public function index(Request $request): View
    {
        $period = $request->get('period', '30'); // 7 | 30 | 90 | all
        $sort   = $request->get('sort', 'best');  // best | units | worst | name | stock

        $from = $period === 'all' ? null : now()->subDays((int) $period)->startOfDay();

        // Per-product sales aggregates over PAID orders in the period
        $salesSub = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->when($from, fn ($q) => $q->where('orders.created_at', '>=', $from))
            ->select(
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.total_price) as revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as order_count')
            )
            ->groupBy('order_items.product_id');

        // LEFT JOIN so products with zero sales still appear
        $query = DB::table('products')
            ->leftJoinSub($salesSub, 'sales', fn ($j) => $j->on('sales.product_id', '=', 'products.id'))
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                'products.price',
                'products.stock_quantity',
                'products.is_active',
                'products.views_count',
                'categories.name as category',
                DB::raw('COALESCE(sales.units_sold, 0) as units_sold'),
                DB::raw('COALESCE(sales.revenue, 0) as revenue'),
                DB::raw('COALESCE(sales.order_count, 0) as order_count')
            );

        $query = match ($sort) {
            'units' => $query->orderByDesc('units_sold')->orderByDesc('revenue'),
            'worst' => $query->orderBy('units_sold')->orderBy('revenue'),
            'name'  => $query->orderBy('products.name'),
            'stock' => $query->orderBy('products.stock_quantity'),
            default => $query->orderByDesc('revenue')->orderByDesc('units_sold'), // best
        };

        $products = $query->get();

        // Summary tiles
        $totalRevenue = (float) $products->sum('revenue');
        $totalUnits   = (int) $products->sum('units_sold');
        $noSales      = $products->where('units_sold', 0)->count();
        $bestSeller   = $products->sortByDesc('revenue')->first();

        return view('admin.product-performance.index', compact(
            'products', 'period', 'sort', 'totalRevenue', 'totalUnits', 'noSales', 'bestSeller'
        ));
    }
}
