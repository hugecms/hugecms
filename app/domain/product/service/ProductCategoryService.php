<?php

declare(strict_types=1);

namespace app\domain\product\service;

use app\domain\product\repository\ProductCategoryRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ProductCategoryService extends CommonService implements ServiceInterface
{
    public function getRepository(): ProductCategoryRepository
    {
        return ProductCategoryRepository::getInstance();
    }
}
