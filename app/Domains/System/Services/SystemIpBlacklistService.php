<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemIpBlacklistRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemIpBlacklistService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemIpBlacklistRepository $repository,
    ) {}

    public function getRepository(): SystemIpBlacklistRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
