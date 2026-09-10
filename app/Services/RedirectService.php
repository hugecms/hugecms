<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RedirectRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class RedirectService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly RedirectRepository $repository,
    ) {}

    public function getRepository(): RedirectRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
