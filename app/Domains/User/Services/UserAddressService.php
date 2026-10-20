<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserAddressRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserAddressService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserAddressRepository $repository,
    ) {}

    public function getRepository(): UserAddressRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
