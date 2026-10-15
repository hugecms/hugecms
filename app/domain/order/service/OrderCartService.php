<?php

declare(strict_types=1);

namespace app\domain\order\service;

use app\domain\order\repository\OrderCartRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class OrderCartService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderCartRepository
    {
        return OrderCartRepository::getInstance();
    }
}
