<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatServiceRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatServiceService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatServiceRepository $repository,
    ) {}

    public function getRepository(): ChatServiceRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
