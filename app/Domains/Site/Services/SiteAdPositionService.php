<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteAdPositionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteAdPositionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteAdPositionRepository $repository,
    ) {}

    public function getRepository(): SiteAdPositionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
