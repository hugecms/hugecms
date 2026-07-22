<?php

namespace Database\Factories;

use App\Enums\PublishStatus;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Link\>
 */
class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'link' => $this->faker->url(),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'status' => PublishStatus::Published->value,
        ];
    }
}
