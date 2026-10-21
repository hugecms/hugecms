<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingAssistHelperRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingAssistHelperService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingAssistHelperRepository $repository,
    ) {}

    public function getRepository(): MarketingAssistHelperRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
