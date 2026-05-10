<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $ceramic = Category::where('slug', 'ceramic-diffusers')->first();
        $glass = Category::where('slug', 'glass-diffusers')->first();
        $travel = Category::where('slug', 'travel-diffusers')->first();
        $reed = Category::where('slug', 'reed-diffusers')->first();
        $oils = Category::where('slug', 'essential-oils')->first();
        $gifts = Category::where('slug', 'gift-sets')->first();

        $products = [
            ['name' => 'Sage & Cedarwood Ceramic Diffuser', 'category_id' => $ceramic?->id, 'price' => 28500, 'compare_at_price' => 35000, 'scent_notes' => 'Sage, Cedarwood, White Musk', 'capacity_ml' => 200, 'burn_time_hours' => 40, 'is_featured' => true, 'stock_quantity' => 30],
            ['name' => 'Lavender Dreams Ceramic Diffuser', 'category_id' => $ceramic?->id, 'price' => 24000, 'scent_notes' => 'French Lavender, Vanilla, Bergamot', 'capacity_ml' => 150, 'burn_time_hours' => 35, 'is_featured' => true, 'stock_quantity' => 45],
            ['name' => 'Oud & Amber Ceramic Diffuser', 'category_id' => $ceramic?->id, 'price' => 38000, 'compare_at_price' => 45000, 'scent_notes' => 'Oud, Amber, Sandalwood', 'capacity_ml' => 300, 'burn_time_hours' => 55, 'is_featured' => true, 'stock_quantity' => 20],
            ['name' => 'Rose Botanica Ceramic Diffuser', 'category_id' => $ceramic?->id, 'price' => 26500, 'scent_notes' => 'Turkish Rose, Jasmine, Patchouli', 'capacity_ml' => 200, 'burn_time_hours' => 40, 'stock_quantity' => 25],
            ['name' => 'Hinoki Forest Glass Diffuser', 'category_id' => $glass?->id, 'price' => 32000, 'compare_at_price' => 39000, 'scent_notes' => 'Hinoki Wood, Green Tea, Bamboo', 'capacity_ml' => 250, 'burn_time_hours' => 48, 'is_featured' => true, 'stock_quantity' => 18],
            ['name' => 'Mediterranean Citrus Glass Diffuser', 'category_id' => $glass?->id, 'price' => 27500, 'scent_notes' => 'Bergamot, Lemon, Orange Blossom', 'capacity_ml' => 200, 'burn_time_hours' => 38, 'stock_quantity' => 35],
            ['name' => 'Noir & Velvet Glass Diffuser', 'category_id' => $glass?->id, 'price' => 41000, 'scent_notes' => 'Black Pepper, Vetiver, Dark Musk', 'capacity_ml' => 350, 'burn_time_hours' => 60, 'stock_quantity' => 12],
            ['name' => 'Pearl Mist Glass Diffuser', 'category_id' => $glass?->id, 'price' => 29000, 'compare_at_price' => 34000, 'scent_notes' => 'Sea Salt, White Lily, Coconut', 'capacity_ml' => 200, 'burn_time_hours' => 40, 'stock_quantity' => 22],
            ['name' => 'Pocket Calm Travel Diffuser', 'category_id' => $travel?->id, 'price' => 12500, 'scent_notes' => 'Eucalyptus, Mint, Lemongrass', 'capacity_ml' => 50, 'burn_time_hours' => 15, 'stock_quantity' => 60],
            ['name' => 'Wanderlust Travel Diffuser Set', 'category_id' => $travel?->id, 'price' => 18000, 'compare_at_price' => 22000, 'scent_notes' => '3 Scents: Lavender, Citrus, Oud', 'capacity_ml' => 50, 'burn_time_hours' => 15, 'stock_quantity' => 40],
            ['name' => 'Classic Reed Diffuser — Linen & Cotton', 'category_id' => $reed?->id, 'price' => 15500, 'scent_notes' => 'Clean Linen, Cotton, Soft Musk', 'capacity_ml' => 200, 'burn_time_hours' => 720, 'stock_quantity' => 50],
            ['name' => 'Signature Reed Diffuser — Oud Royale', 'category_id' => $reed?->id, 'price' => 22000, 'compare_at_price' => 27000, 'scent_notes' => 'Oud, Rose, Saffron', 'capacity_ml' => 300, 'burn_time_hours' => 1080, 'stock_quantity' => 28],
            ['name' => 'Pure Lavender Essential Oil 10ml', 'category_id' => $oils?->id, 'price' => 6500, 'scent_notes' => '100% Pure Lavender', 'capacity_ml' => 10, 'stock_quantity' => 80],
            ['name' => 'Eucalyptus & Mint Blend 30ml', 'category_id' => $oils?->id, 'price' => 9500, 'compare_at_price' => 12000, 'scent_notes' => 'Eucalyptus, Spearmint, Camphor', 'capacity_ml' => 30, 'stock_quantity' => 65],
            ['name' => 'Signature Oud Blend 15ml', 'category_id' => $oils?->id, 'price' => 14500, 'scent_notes' => 'Oud, Sandalwood, Rose Attar', 'capacity_ml' => 15, 'stock_quantity' => 35],
            ['name' => 'The Calm Collection Gift Set', 'category_id' => $gifts?->id, 'price' => 52000, 'compare_at_price' => 65000, 'scent_notes' => 'Lavender, Eucalyptus, Oud', 'is_featured' => true, 'stock_quantity' => 15],
            ['name' => 'Bridal Bliss Gift Set', 'category_id' => $gifts?->id, 'price' => 78000, 'compare_at_price' => 95000, 'scent_notes' => 'Rose, Jasmine, White Musk', 'stock_quantity' => 10],
            ['name' => 'Home Starter Kit', 'category_id' => $gifts?->id, 'price' => 35000, 'compare_at_price' => 42000, 'scent_notes' => 'Your choice of 2 scents + reed set', 'stock_quantity' => 20],
            ['name' => 'Zen Garden Ceramic Diffuser', 'category_id' => $ceramic?->id, 'price' => 31000, 'scent_notes' => 'Green Tea, Ginger, Bamboo', 'capacity_ml' => 200, 'burn_time_hours' => 40, 'stock_quantity' => 22],
            ['name' => 'Coastal Breeze Glass Diffuser', 'category_id' => $glass?->id, 'price' => 29500, 'scent_notes' => 'Sea Salt, Driftwood, Aqua Musk', 'capacity_ml' => 250, 'burn_time_hours' => 45, 'stock_quantity' => 28],
        ];

        foreach ($products as $data) {
            $name = $data['name'];
            $slug = Str::slug($name);
            $sku = 'AUR-'.strtoupper(Str::random(6));

            $product = Product::firstOrCreate(['slug' => $slug], array_merge($data, [
                'slug' => $slug,
                'sku' => $sku,
                'short_description' => 'A luxurious diffuser crafted for calm, elegant living.',
                'description' => '<p>Experience the art of home fragrance with our '.$name.'. Each piece is thoughtfully crafted to transform your space into a sanctuary of calm and beauty.</p><p>Our expert perfumers source only the finest ingredients from around the world, blending them into signature scents that tell a story.</p>',
                'is_active' => true,
                'weight' => 0.5,
            ]));

            // Placeholder image
            if ($product->wasRecentlyCreated) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'products/placeholder.jpg',
                    'alt_text' => $name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
