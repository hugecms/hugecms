<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemAppRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemAppService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemAppRepository $repository,
    ) {}

    public function getRepository(): SystemAppRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
