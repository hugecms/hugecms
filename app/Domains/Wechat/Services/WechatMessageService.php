<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatMessageRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatMessageService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatMessageRepository $repository,
    ) {}

    public function getRepository(): WechatMessageRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
