<?php

declare(strict_types=1);

namespace app\domain\order\service;

use app\domain\order\repository\OrderPaymentRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class OrderPaymentService extends CommonService implements ServiceInterface
{
    public function getRepository(): OrderPaymentRepository
    {
        return OrderPaymentRepository::getInstance();
    }
}
