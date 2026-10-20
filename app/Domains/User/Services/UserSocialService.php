<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserSocialRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserSocialService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserSocialRepository $repository,
    ) {}

    public function getRepository(): UserSocialRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
