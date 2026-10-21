<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingBargainHelperRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingBargainHelperService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingBargainHelperRepository $repository,
    ) {}

    public function getRepository(): MarketingBargainHelperRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
