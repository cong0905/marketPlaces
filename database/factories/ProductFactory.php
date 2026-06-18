<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use App\Models\Province;
use App\Models\District;
use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'province_id' => Province::factory(),
            'district_id' => District::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'description' => fake()->paragraphs(3, true),
            'price' => fake()->randomFloat(0, 10000, 10000000),
            'is_negotiable' => fake()->boolean(),
            'condition_percent' => fake()->numberBetween(10, 100),
            'brand' => fake()->optional()->word(),
            'model' => fake()->optional()->word(),
            'status' => ProductStatus::ACTIVE,
            'quantity' => fake()->numberBetween(1, 100),
            'view_count' => fake()->numberBetween(0, 1000),
            'approved_at' => now(),
        ];
    }
}
