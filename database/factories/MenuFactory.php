<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Menu\>
 */
class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug(),
            'description' => $this->faker->sentence(),
        ];
    }

    public function main(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '主导航',
            'slug' => 'main',
        ]);
    }

    public function footer(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => '页脚导航',
            'slug' => 'footer',
        ]);
    }
}
