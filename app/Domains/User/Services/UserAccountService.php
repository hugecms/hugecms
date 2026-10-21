<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserAccountRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAccountService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserAccountRepository $repository,
    ) {}

    public function getRepository(): UserAccountRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
