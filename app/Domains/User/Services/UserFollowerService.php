<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserFollowerRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserFollowerService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserFollowerRepository $repository,
    ) {}

    public function getRepository(): UserFollowerRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
