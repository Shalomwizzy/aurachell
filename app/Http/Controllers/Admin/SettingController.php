<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            // General
            'store_name' => 'required|string|max:100',
            'store_email' => 'required|email',
            'store_phone' => 'nullable|string|max:30',
            'store_address' => 'nullable|string|max:255',
            'currency' => 'required|string|max:10',
            'announcement_bar' => 'nullable|string|max:255',
            'announcement_active' => 'nullable|boolean',
            // Shipping (rates now managed in Shipping Zones)
            'delivery_time_estimate' => 'nullable|string|max:60',
            // Products
            'low_stock_threshold' => 'nullable|integer|min:0',
            'out_of_stock_behavior' => 'nullable|in:hide,show',
            'products_per_page' => 'nullable|integer|min:6|max:60',
            'default_sort' => 'nullable|in:featured,newest,price_asc,price_desc',
            // Social
            'whatsapp_number' => 'nullable|string|max:30',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'show_social_whatsapp' => 'nullable|boolean',
            'show_social_facebook' => 'nullable|boolean',
            'show_social_instagram' => 'nullable|boolean',
            'show_social_twitter' => 'nullable|boolean',
            'show_social_tiktok' => 'nullable|boolean',
            // Appearance
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            // SEO
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'ga_measurement_id' => 'nullable|string|max:30',
            // Payments
            'paystack_public_key' => 'nullable|string',
            'paystack_secret_key' => 'nullable|string',
            // Referrals
            'referral_reward_percent' => 'nullable|integer|min:1|max:50',
            'referral_trigger_min_order' => 'nullable|integer|min:0',
            'referral_coupon_min_order' => 'nullable|integer|min:0',
            'referral_coupon_validity_days' => 'nullable|integer|min:7|max:365',
            // Returns
            'return_window_days' => 'nullable|integer|min:0|max:90',
            // Tracking
            'facebook_pixel_id' => 'nullable|digits_between:10,20',
            'facebook_pixel_enabled' => 'nullable|boolean',
        ]);

        $fields = [
            'store_name', 'store_email', 'store_phone', 'store_address', 'currency',
            'announcement_bar',
            'delivery_time_estimate',
            'low_stock_threshold', 'out_of_stock_behavior',
            'products_per_page', 'default_sort',
            'whatsapp_number', 'facebook_url', 'instagram_url', 'twitter_url', 'tiktok_url',
            'meta_title', 'meta_description', 'ga_measurement_id',
            'paystack_public_key', 'paystack_secret_key',
            'referral_reward_percent', 'referral_trigger_min_order',
            'referral_coupon_min_order', 'referral_coupon_validity_days',
            'return_window_days',
            'facebook_pixel_id',
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        Setting::set('announcement_active', $request->boolean('announcement_active') ? '1' : '0');
        Setting::set('facebook_pixel_enabled', $request->boolean('facebook_pixel_enabled') ? '1' : '0');

        foreach (['whatsapp', 'facebook', 'instagram', 'twitter', 'tiktok'] as $platform) {
            Setting::set('show_social_'.$platform, $request->boolean('show_social_'.$platform) ? '1' : '0');
        }

        if ($request->hasFile('logo')) {
            $old = Setting::get('logo');
            if ($old && file_exists(public_path('images/'.$old))) {
                @unlink(public_path('images/'.$old));
            }
            $filename = 'logo_'.time().'.'.$request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('images'), $filename);
            Setting::set('logo', $filename);
        }

        if ($request->hasFile('favicon')) {
            $filename = 'favicon_'.time().'.'.$request->file('favicon')->getClientOriginalExtension();
            $request->file('favicon')->move(public_path('images'), $filename);
            Setting::set('favicon', $filename);
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Cache cleared successfully.');
    }
}
