<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentArticleContentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentArticleContentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentArticleContentRepository $repository,
    ) {}

    public function getRepository(): ContentArticleContentRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
