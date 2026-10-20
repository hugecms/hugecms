<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteNavRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteNavService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteNavRepository $repository,
    ) {}

    public function getRepository(): SiteNavRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
