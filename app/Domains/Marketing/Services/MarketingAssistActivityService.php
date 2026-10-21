<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingAssistActivityRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingAssistActivityService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingAssistActivityRepository $repository,
    ) {}

    public function getRepository(): MarketingAssistActivityRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
