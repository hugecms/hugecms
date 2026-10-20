<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatMessageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatMessageService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatMessageRepository $repository,
    ) {}

    public function getRepository(): ChatMessageRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
