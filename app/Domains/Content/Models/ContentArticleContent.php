<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class ContentArticleContent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_article_contents';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'article_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'create_time',
        'update_time',
    ];
}
