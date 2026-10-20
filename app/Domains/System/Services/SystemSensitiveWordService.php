<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemSensitiveWordRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemSensitiveWordService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemSensitiveWordRepository $repository,
    ) {}

    public function getRepository(): SystemSensitiveWordRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
