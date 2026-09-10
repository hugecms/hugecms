<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nav_items';

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
        'menu_id',
        'parent_id',
        'title',
        'link_type',
        'link_value',
        'open_type',
        'icon',
        'is_active',
        'sort',
    ];
}
