<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentCategoryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentCategoryService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentCategoryRepository $repository,
    ) {}

    public function getRepository(): ContentCategoryRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
