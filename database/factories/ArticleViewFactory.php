<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory\<ArticleView\>
 */
class ArticleViewFactory extends Factory
{
    protected $model = ArticleView::class;

    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'visitor_hash' => $this->faker->md5(),
            'ip_address' => $this->faker->ipv4(),
            'viewed_at' => $this->faker->dateTimeThisYear(),
        ];
    }

    public function forArticle(Article $article): static
    {
        return $this->state(fn (array $attributes) => [
            'article_id' => $article->id,
        ]);
    }
}
