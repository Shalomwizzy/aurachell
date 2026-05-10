<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.uniqid(),
            'sku' => 'AUR-'.strtoupper(fake()->unique()->lexify('??????')),
            'price' => fake()->numberBetween(5000, 50000),
            'stock_quantity' => 10,
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock_quantity' => 0]);
    }
}
