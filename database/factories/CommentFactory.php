<?php

namespace Database\Factories;

use App\Enums\CommentStatus;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<Comment\>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'status' => CommentStatus::Approved->value,
            'article_id' => Article::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'guest_name' => null,
            'guest_email' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CommentStatus::Pending->value,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CommentStatus::Approved->value,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CommentStatus::Rejected->value,
        ]);
    }

    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CommentStatus::Deleted->value,
        ]);
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'guest_name' => $this->faker->name(),
            'guest_email' => $this->faker->safeEmail(),
        ]);
    }

    public function forPage(): static
    {
        return $this->state(fn (array $attributes) => [
            'article_id' => null,
            'page_id' => Page::factory(),
        ]);
    }

    public function reply(Comment $parent): static
    {
        return $this->state(fn (array $attributes) => [
            'article_id' => $parent->article_id,
            'page_id' => $parent->page_id,
            'parent_id' => $parent->id,
        ]);
    }
}
