<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ShortLinkClickRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShortLinkClickService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShortLinkClickRepository $repository,
    ) {}

    public function getRepository(): ShortLinkClickRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
