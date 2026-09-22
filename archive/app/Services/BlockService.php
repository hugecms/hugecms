<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\BlockRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class BlockService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly BlockRepository $repository,
    ) {}

    public function getRepository(): BlockRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
