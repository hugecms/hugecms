<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserWithdrawOrderRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserWithdrawOrderService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserWithdrawOrderRepository $repository,
    ) {}

    public function getRepository(): UserWithdrawOrderRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
