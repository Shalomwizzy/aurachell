<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $days30 = now()->subDays(30);
        $days7 = now()->subDays(7);

        $totalPageviews = PageView::where('created_at', '>=', $days30)->count();
        $uniqueVisitors = PageView::where('created_at', '>=', $days30)->distinct('session_id')->count();
        $weekPageviews = PageView::where('created_at', '>=', $days7)->count();
        $weekVisitors = PageView::where('created_at', '>=', $days7)->distinct('session_id')->count();

        $topPages = PageView::where('created_at', '>=', $days30)
            ->select('url', DB::raw('COUNT(*) as views'))
            ->groupBy('url')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $referrers = PageView::where('created_at', '>=', $days30)
            ->whereNotNull('referrer')
            ->select('referrer', DB::raw('COUNT(*) as visits'))
            ->groupBy('referrer')
            ->orderByDesc('visits')
            ->limit(8)
            ->get();

        $devices = PageView::where('created_at', '>=', $days30)
            ->select('device', DB::raw('COUNT(*) as count'))
            ->groupBy('device')
            ->orderByDesc('count')
            ->get();

        // Daily chart: last 30 days
        $daily = PageView::where('created_at', '>=', $days30)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT session_id) as visitors')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing days with zeros
        $chartLabels = [];
        $chartViews = [];
        $chartVisitors = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('M j');
            $chartLabels[] = $label;
            $chartViews[] = $daily[$date]->views ?? 0;
            $chartVisitors[] = $daily[$date]->visitors ?? 0;
        }

        return view('admin.analytics.index', [
            'totalPageviews' => $totalPageviews,
            'uniqueVisitors' => $uniqueVisitors,
            'weekPageviews' => $weekPageviews,
            'weekVisitors' => $weekVisitors,
            'topPages' => $topPages,
            'referrers' => $referrers,
            'devices' => $devices,
            'chartLabels' => json_encode($chartLabels),
            'chartViews' => json_encode($chartViews),
            'chartVisitors' => json_encode($chartVisitors),
        ]);
    }
}
