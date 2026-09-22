<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attachments';

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
        'uploader_id',
        'file_name',
        'file_path',
        'storage_driver',
        'storage_bucket',
        'cdn_url',
        'file_size',
        'mime_type',
        'width',
        'height',
        'alt_text',
        'sort',
    ];
}
