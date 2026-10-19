<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentPageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentPageService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentPageRepository $repository,
    ) {}

    public function getRepository(): ContentPageRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
