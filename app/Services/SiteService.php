<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SiteRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteRepository $repository,
    ) {}

    public function getRepository(): SiteRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
