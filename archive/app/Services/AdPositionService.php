<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AdPositionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AdPositionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AdPositionRepository $repository,
    ) {}

    public function getRepository(): AdPositionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
