<?php

declare(strict_types=1);

namespace App\Domains\Site\Services;

use App\Domains\Site\Repositories\SiteSearchKeywordRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SiteSearchKeywordService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SiteSearchKeywordRepository $repository,
    ) {}

    public function getRepository(): SiteSearchKeywordRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
