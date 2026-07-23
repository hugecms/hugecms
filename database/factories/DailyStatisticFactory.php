<?php

namespace Database\Factories;

use App\Models\DailyStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<DailyStatistic\>
 */
class DailyStatisticFactory extends Factory
{
    protected $model = DailyStatistic::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'page_views' => $this->faker->numberBetween(0, 1000),
            'unique_visitors' => $this->faker->numberBetween(0, 500),
            'new_users' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => ['date' => $date]);
    }
}
