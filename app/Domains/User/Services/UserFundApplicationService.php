<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserFundApplicationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserFundApplicationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserFundApplicationRepository $repository,
    ) {}

    public function getRepository(): UserFundApplicationRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
