<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ModelFieldRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ModelFieldService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ModelFieldRepository $repository,
    ) {}

    public function getRepository(): ModelFieldRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
