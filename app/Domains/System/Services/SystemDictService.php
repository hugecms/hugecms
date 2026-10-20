<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemDictRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemDictService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemDictRepository $repository,
    ) {}

    public function getRepository(): SystemDictRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
