<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatQuickReplyRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatQuickReplyService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatQuickReplyRepository $repository,
    ) {}

    public function getRepository(): ChatQuickReplyRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
