<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentCommentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentCommentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentCommentRepository $repository,
    ) {}

    public function getRepository(): ContentCommentRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
