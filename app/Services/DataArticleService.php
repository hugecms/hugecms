<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\DataArticleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class DataArticleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly DataArticleRepository $repository,
    ) {}

    public function getRepository(): DataArticleRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
