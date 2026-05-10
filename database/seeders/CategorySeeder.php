<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ceramic Diffusers', 'slug' => 'ceramic-diffusers', 'description' => 'Handcrafted ceramic diffusers for a timeless aesthetic.', 'sort_order' => 1],
            ['name' => 'Glass Diffusers', 'slug' => 'glass-diffusers', 'description' => 'Elegant glass diffusers with a contemporary feel.', 'sort_order' => 2],
            ['name' => 'Travel Diffusers', 'slug' => 'travel-diffusers', 'description' => 'Compact diffusers for fragrance on the go.', 'sort_order' => 3],
            ['name' => 'Reed Diffusers', 'slug' => 'reed-diffusers', 'description' => 'Classic reed diffusers for continuous fragrance.', 'sort_order' => 4],
            ['name' => 'Essential Oils', 'slug' => 'essential-oils', 'description' => 'Premium pure essential oils and blends.', 'sort_order' => 5],
            ['name' => 'Gift Sets', 'slug' => 'gift-sets', 'description' => 'Curated gift sets for every occasion.', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
