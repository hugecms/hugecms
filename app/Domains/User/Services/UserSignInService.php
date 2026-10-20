<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserSignInRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserSignInService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserSignInRepository $repository,
    ) {}

    public function getRepository(): UserSignInRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
