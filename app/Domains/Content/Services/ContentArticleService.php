<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentArticleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentArticleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentArticleRepository $repository,
    ) {}

    public function getRepository(): ContentArticleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
