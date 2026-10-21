<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\MarketingPromotionFull;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingPromotionFullDestroyResponse')]
class MarketingPromotionFullDestroyResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'status', description: '状态:1成功，2失败', type: 'integer')]
    private int $status;

    /**
     * 获取状态:1成功，2失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1成功，2失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}
