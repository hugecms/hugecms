<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserProfileRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserProfileService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserProfileRepository $repository,
    ) {}

    public function getRepository(): UserProfileRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
