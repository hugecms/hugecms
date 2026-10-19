<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemDictItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemDictItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemDictItemRepository $repository,
    ) {}

    public function getRepository(): SystemDictItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
