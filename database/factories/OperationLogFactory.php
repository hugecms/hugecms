<?php

namespace Database\Factories;

use App\Enums\OperationLogType;
use App\Models\OperationLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OperationLog>
 */
class OperationLogFactory extends Factory
{
    protected $model = OperationLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => fake()->ipv4(),
            'type' => fake()->randomElement(OperationLogType::cases()),
            'object_type' => fake()->randomElement(['article', 'page', 'user']),
            'object_id' => fake()->randomNumber(),
            'summary' => fake()->sentence(),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
