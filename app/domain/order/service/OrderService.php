<?php

declare(strict_types=1);

namespace app\domain\order\service;

use app\domain\order\repository\OrderRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class OrderService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderRepository
    {
        return OrderRepository::getInstance();
    }
}
