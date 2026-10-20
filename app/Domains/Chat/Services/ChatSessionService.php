<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatSessionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatSessionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatSessionRepository $repository,
    ) {}

    public function getRepository(): ChatSessionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
