<?php

declare(strict_types=1);

namespace App\Domains\Email\Services;

use App\Domains\Email\Repositories\EmailSubscriptionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class EmailSubscriptionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly EmailSubscriptionRepository $repository,
    ) {}

    public function getRepository(): EmailSubscriptionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
