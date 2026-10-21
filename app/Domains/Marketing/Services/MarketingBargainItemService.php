<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingBargainItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingBargainItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingBargainItemRepository $repository,
    ) {}

    public function getRepository(): MarketingBargainItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
