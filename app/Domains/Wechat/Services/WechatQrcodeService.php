<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatQrcodeRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatQrcodeService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatQrcodeRepository $repository,
    ) {}

    public function getRepository(): WechatQrcodeRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
