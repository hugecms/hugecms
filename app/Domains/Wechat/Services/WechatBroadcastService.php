<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatBroadcastRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatBroadcastService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatBroadcastRepository $repository,
    ) {}

    public function getRepository(): WechatBroadcastRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
