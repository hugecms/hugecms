<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatUserRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatUserService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatUserRepository $repository,
    ) {}

    public function getRepository(): WechatUserRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
