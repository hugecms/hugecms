<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Services;

use App\Domains\Wechat\Repositories\WechatTemplateRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class WechatTemplateService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly WechatTemplateRepository $repository,
    ) {}

    public function getRepository(): WechatTemplateRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
