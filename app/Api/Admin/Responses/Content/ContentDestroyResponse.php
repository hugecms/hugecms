<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Content;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentDestroyResponse')]
class ContentDestroyResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'status', description: '状态:1成功，2失败', type: 'integer')]
    private int $status;

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
