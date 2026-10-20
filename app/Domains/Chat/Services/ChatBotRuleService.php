<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatBotRuleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatBotRuleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatBotRuleRepository $repository,
    ) {}

    public function getRepository(): ChatBotRuleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
