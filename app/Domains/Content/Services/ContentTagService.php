<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentTagRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentTagService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentTagRepository $repository,
    ) {}

    public function getRepository(): ContentTagRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
