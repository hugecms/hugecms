<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RecycleBinRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class RecycleBinService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly RecycleBinRepository $repository,
    ) {}

    public function getRepository(): RecycleBinRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
