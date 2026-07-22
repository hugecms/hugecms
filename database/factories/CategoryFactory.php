<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Category\>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'slug' => $this->faker->unique()->slug(2),
            'description' => $this->faker->sentence(),
            'seo_title' => $this->faker->sentence(),
            'seo_description' => $this->faker->sentence(),
            'seo_keywords' => implode(',', $this->faker->words(3)),
        ];
    }
}
