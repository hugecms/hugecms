<?php

namespace Database\Factories;

use App\Models\MediaCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<MediaCategory\>
 */
class MediaCategoryFactory extends Factory
{
    protected $model = MediaCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'slug' => $this->faker->unique()->slug(2),
            'description' => $this->faker->sentence(),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
