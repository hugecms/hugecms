<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentCommentLikeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentCommentLikeService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentCommentLikeRepository $repository,
    ) {}

    public function getRepository(): ContentCommentLikeRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
