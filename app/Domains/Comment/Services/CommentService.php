<?php

declare(strict_types=1);

namespace App\Domains\Comment\Services;

use App\Domains\Comment\Repositories\CommentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class CommentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly CommentRepository $repository,
    ) {}

    public function getRepository(): CommentRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
