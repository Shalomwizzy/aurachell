<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with(['referrer', 'referred', 'order'])
            ->latest()
            ->paginate(25);

        // Top referrers: only count REWARDED referrals (friend actually purchased)
        $top = User::withCount([
            'referrals as rewarded_count' => fn ($q) => $q->where('status', 'rewarded'),
        ])
            ->having('rewarded_count', '>', 0)
            ->orderByDesc('rewarded_count')
            ->limit(5)
            ->get();

        $settings = Setting::all()->pluck('value', 'key');

        $stats = [
            'total' => Referral::count(),
            'rewarded' => Referral::where('status', 'rewarded')->count(),
            'pending' => Referral::where('status', 'pending')->count(),
            'top' => $top,
            'settings' => $settings,
        ];

        return view('admin.referrals.index', compact('referrals', 'stats'));
    }

    public function settingsPage()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.referrals.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'referral_reward_percent' => 'required|integer|min:1|max:50',
            'referral_trigger_min_order' => 'required|integer|min:0',
            'referral_coupon_min_order' => 'required|integer|min:0',
            'referral_coupon_validity_days' => 'required|integer|min:7|max:365',
        ]);

        foreach (['referral_reward_percent', 'referral_trigger_min_order', 'referral_coupon_min_order', 'referral_coupon_validity_days'] as $key) {
            Setting::set($key, $request->input($key));
        }

        return back()->with('success', 'Referral settings saved.');
    }
}
