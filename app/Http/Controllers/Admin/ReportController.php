<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        $from = now()->subDays((int) $period)->startOfDay();
        $to = now()->endOfDay();

        $revenue = (float) Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        $ordersCount = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $newCustomers = User::whereBetween('created_at', [$from, $to])
            ->whereDoesntHave('roles')
            ->count();

        $avgOrderValue = $ordersCount > 0 ? round($revenue / $ordersCount, 2) : 0;

        // Revenue by day
        $revenueByDay = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to])
            ->select('products.name', DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Orders by status (all orders in period for full picture)
        $ordersByStatus = Order::whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.reports.index', compact(
            'revenue', 'ordersCount', 'newCustomers', 'avgOrderValue',
            'revenueByDay', 'topProducts', 'ordersByStatus', 'period'
        ));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', '30');
        $from = now()->subDays((int) $period)->startOfDay();
        $to = now()->endOfDay();

        $orders = Order::with(['items.product', 'user'])
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order #', 'Customer', 'Email', 'Items', 'Total', 'Status', 'Payment', 'Date']);
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->items->sum('quantity'),
                    $order->total,
                    $order->status,
                    $order->payment_status,
                    $order->placed_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
