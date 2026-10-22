<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteAdRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteAdService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteAdRepository $repository,
    ) {}

    public function getRepository(): SiteAdRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
