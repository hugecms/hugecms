<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteBannerRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteBannerService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteBannerRepository $repository,
    ) {}

    public function getRepository(): SiteBannerRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
