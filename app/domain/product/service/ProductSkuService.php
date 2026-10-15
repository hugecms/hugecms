<?php

declare(strict_types=1);

namespace app\domain\product\service;

use app\domain\product\repository\ProductSkuRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ProductSkuService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductSkuRepository
    {
        return ProductSkuRepository::getInstance();
    }
}
