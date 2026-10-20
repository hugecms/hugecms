<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatSubscribeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatSubscribeService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatSubscribeRepository $repository,
    ) {}

    public function getRepository(): WechatSubscribeRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
