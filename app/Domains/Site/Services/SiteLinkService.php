<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteLinkRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteLinkService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteLinkRepository $repository,
    ) {}

    public function getRepository(): SiteLinkRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
