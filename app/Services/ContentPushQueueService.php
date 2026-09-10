<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ContentPushQueueRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentPushQueueService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentPushQueueRepository $repository,
    ) {}

    public function getRepository(): ContentPushQueueRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
