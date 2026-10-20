<?php

declare(strict_types=1);

namespace App\Domains\Chat\Services;

use App\Domains\Chat\Repositories\ChatFaqRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ChatFaqService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ChatFaqRepository $repository,
    ) {}

    public function getRepository(): ChatFaqRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
