<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Kulkas', 'Televisi', 'Mesin Cuci', 'AC', 'Dispenser', 'Microwave']) . ' ' . fake()->randomNumber(3);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => 'fa-solid fa-tv',
        ];
    }
}
