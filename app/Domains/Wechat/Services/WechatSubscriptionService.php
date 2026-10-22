<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatSubscriptionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatSubscriptionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatSubscriptionRepository $repository,
    ) {}

    public function getRepository(): WechatSubscriptionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
