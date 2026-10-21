<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingGroupBuyItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingGroupBuyItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingGroupBuyItemRepository $repository,
    ) {}

    public function getRepository(): MarketingGroupBuyItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
