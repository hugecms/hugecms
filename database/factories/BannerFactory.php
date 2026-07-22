<?php

namespace Database\Factories;

use App\Enums\PublishStatus;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Banner\>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'link' => $this->faker->url(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'status' => PublishStatus::Published->value,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDays(7),
        ];
    }
}
