<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SeoMetaRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SeoMetaService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SeoMetaRepository $repository,
    ) {}

    public function getRepository(): SeoMetaRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
