<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Article\>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'title' => $title,
            'slug' => $this->faker->unique()->slug(4),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(5, true),
            'status' => ContentStatus::Published->value,
            'published_at' => now()->subDays($this->faker->numberBetween(0, 30)),
            'user_id' => User::factory(),
            'is_pinned' => false,
            'is_recommended' => false,
            'seo_title' => $title,
            'seo_description' => $this->faker->sentence(),
            'seo_keywords' => implode(',', $this->faker->words(3)),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Draft->value,
            'published_at' => null,
        ]);
    }

    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }

    public function recommended(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_recommended' => true,
        ]);
    }
}
