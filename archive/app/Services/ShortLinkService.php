<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShortLinkRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShortLinkService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShortLinkRepository $repository,
    ) {}

    public function getRepository(): ShortLinkRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
