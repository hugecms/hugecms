<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserRankRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserRankService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserRankRepository $repository,
    ) {}

    public function getRepository(): UserRankRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
