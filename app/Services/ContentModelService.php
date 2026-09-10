<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentModelRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentModelService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentModelRepository $repository,
    ) {}

    public function getRepository(): ContentModelRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
