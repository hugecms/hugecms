<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserFavoriteRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserFavoriteService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserFavoriteRepository $repository,
    ) {}

    public function getRepository(): UserFavoriteRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
