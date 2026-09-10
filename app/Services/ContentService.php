<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentRepository $repository,
    ) {}

    public function getRepository(): ContentRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
