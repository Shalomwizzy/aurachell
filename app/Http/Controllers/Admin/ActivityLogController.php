<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($action = $request->query('action')) {
            $query->where('action', 'like', "%{$action}%");
        }
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->paginate(50)->withQueryString();

        $totals = [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week' => ActivityLog::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => ActivityLog::where('created_at', '>=', now()->subMonth())->count(),
        ];

        $actionTypes = ActivityLog::distinct('action')->pluck('action')->take(20);
        $users = User::whereHas('activityLogs')->select('id', 'name')->get();

        return view('admin.activity.index', compact('logs', 'totals', 'actionTypes', 'users'));
    }

    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return view('admin.activity.show', compact('log'));
    }

    public function clear()
    {
        ActivityLog::truncate();

        return redirect()->route('admin.activity.index')->with('success', 'Activity log cleared successfully.');
    }
}
