<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true) . ' ' . fake()->randomNumber(3);
        $price = fake()->numberBetween(500000, 5000000);

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(4),
            'brand' => fake()->randomElement(['Samsung', 'LG', 'Sharp', 'Polytron', 'Panasonic', 'Toshiba']),
            'model' => 'MOD-' . strtoupper(Str::random(5)),
            'description' => fake()->paragraph(),
            'condition_notes' => 'Kondisi fisik 90% mulus, fungsi 100% normal.',
            'condition' => 'Grade A (Mulus)',
            'condition_color' => '#10B981',
            'price' => $price,
            'promo_price' => null,
            'is_promo' => false,
            'stock' => 1,
            'weight' => fake()->numberBetween(5000, 45000), // in grams
            'length' => 60,
            'width' => 60,
            'height' => 120,
            'status' => 'available',
            'meta_title' => $name,
            'meta_description' => fake()->sentence(),
        ];
    }

    public function promo(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_promo' => true,
            'promo_price' => (float) $attributes['price'] * 0.85,
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn () => [
            'status' => 'sold',
            'stock' => 0,
        ]);
    }
}
