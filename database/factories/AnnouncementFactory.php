<?php

namespace Database\Factories;

use App\Enums\PublishStatus;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Announcement\>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['info', 'warning', 'success', 'danger']),
            'status' => PublishStatus::Published->value,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDays(7),
        ];
    }
}
