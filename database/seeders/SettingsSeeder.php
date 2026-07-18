<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Branding
            ['key' => 'logo',    'value' => 'AURACHELL-LOGO.WEBP', 'group' => 'general'],
            ['key' => 'favicon', 'value' => 'AURACHELL-ICON.PNG',  'group' => 'general'],
            // General
            ['key' => 'store_name', 'value' => 'Aurachell', 'group' => 'general'],
            ['key' => 'store_tagline', 'value' => 'Crafted for Calm. Designed for Home.', 'group' => 'general'],
            ['key' => 'store_email', 'value' => 'hello@aurachell.com', 'group' => 'general'],
            ['key' => 'store_phone', 'value' => '+234 800 000 0000', 'group' => 'general'],
            ['key' => 'store_address', 'value' => 'Lagos, Nigeria', 'group' => 'general'],
            ['key' => 'announcement_bar', 'value' => 'Crafted for calm — luxury home fragrance, delivered nationwide', 'group' => 'general'],
            ['key' => 'announcement_bar_active', 'value' => '1', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general'],
            // Social
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/aurachell', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => '', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => '', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => '', 'group' => 'social'],
            // Shipping
            ['key' => 'shipping_lagos', 'value' => '2500', 'group' => 'shipping'],
            ['key' => 'shipping_other_states', 'value' => '4500', 'group' => 'shipping'],
            ['key' => 'shipping_international', 'value' => '15000', 'group' => 'shipping'],
            // Tax
            ['key' => 'tax_rate', 'value' => '0', 'group' => 'tax'],
            // SEO
            ['key' => 'meta_title', 'value' => 'Aurachell — Luxury Home Diffusers', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Premium home diffusers crafted for calm, luxury living. Shop ceramic, glass, and travel diffusers with exotic scent blends.', 'group' => 'seo'],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }
    }
}
