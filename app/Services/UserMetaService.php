<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserMetaRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class UserMetaService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserMetaRepository $repository,
    ) {}

    public function getRepository(): UserMetaRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
