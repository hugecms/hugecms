<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemSettingRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemSettingService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemSettingRepository $repository,
    ) {}

    public function getRepository(): SystemSettingRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
