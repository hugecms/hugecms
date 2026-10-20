<?php

declare(strict_types=1);

namespace App\Domains\Content\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_pages';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'status',
    ];
}
