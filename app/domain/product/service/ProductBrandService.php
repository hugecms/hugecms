<?php

declare(strict_types=1);

namespace app\domain\product\service;

use app\domain\product\repository\ProductBrandRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ProductBrandService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductBrandRepository
    {
        return ProductBrandRepository::getInstance();
    }
}
