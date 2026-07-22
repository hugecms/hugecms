<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Page\>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => $this->faker->unique()->slug(3),
            'content' => $this->faker->paragraphs(3, true),
            'template' => 'default',
            'status' => ContentStatus::Published->value,
            'user_id' => User::factory(),
            'seo_title' => $title,
            'seo_description' => $this->faker->sentence(),
            'seo_keywords' => implode(',', $this->faker->words(3)),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Draft->value,
        ]);
    }
}
