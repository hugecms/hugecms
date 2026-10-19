<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentArticleTagRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentArticleTagService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentArticleTagRepository $repository,
    ) {}

    public function getRepository(): ContentArticleTagRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
