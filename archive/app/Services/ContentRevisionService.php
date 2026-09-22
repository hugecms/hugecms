<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentRevisionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentRevisionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentRevisionRepository $repository,
    ) {}

    public function getRepository(): ContentRevisionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
