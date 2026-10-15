<?php

declare(strict_types=1);

namespace app\domain\order\service;

use app\domain\order\repository\OrderShipmentRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class OrderShipmentService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderShipmentRepository
    {
        return OrderShipmentRepository::getInstance();
    }
}
