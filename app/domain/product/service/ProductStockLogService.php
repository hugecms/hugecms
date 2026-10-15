<?php

declare(strict_types=1);

namespace app\domain\product\service;

use app\domain\product\repository\ProductStockLogRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ProductStockLogService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductStockLogRepository
    {
        return ProductStockLogRepository::getInstance();
    }
}
